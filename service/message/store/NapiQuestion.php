<?php
/**
 * Question.php
 *
 * 问题存储管理类
 *
 * @author yanruitao<yanruitao@zuoyebang.com>
 * @version 1.0
 * @package Zybang
 */

class Hk_Service_Message_Store_NapiQuestion extends Hk_Service_Message_Store_NapiBase
{
    public $key = [
        "sort" => "NAPP_MSG_USER_LIST_KEY_ID",
        "hash" => "NAPP_MSG_USER_CONTENT_LIST"
    ];

    /**
     * inhreit from base class
     */
    public $limit = 20;

    /**
     * @inherited
     */
	public function getCacheKey($uid, $type = null)
    {
        if (empty($uid)) {
            return false;
        }
		return "{$uid}:".$this->key[$type];
	}

    public function saveMsg($uid, $datas)
    {
        if (empty($uid) || empty($datas) || empty($datas["field"])) {
            return false;
        }

        $datas["create_time"] = intval(microtime(true)*1000);
        $datas["unread_count"] = isset($datas["unread_count"]) ? intval($datas["unread_count"]) : 1;

        //更新hash表
        $microtime = intval(microtime (true) * 1000) / 1000;
        $redisKey = $this->getCacheKey($uid, "hash");

        $item = $this->objRedis->hget($redisKey, $datas["field"]);
        $item = json_decode($item, true);
        $item = empty($item) ? [] : $item;
        if (!empty($item)) {        # 同一个问题的消息合并
            $datas["unread_count"] += $item["unread_count"];
            $datas["create_time"] = $microtime;
        }


        $ret = $this->objRedis->hset($redisKey, $datas["field"], json_encode($datas));
        if (!$ret) {
            Bd_Log::warning("Msg store error. Abstract[hash[{$redisKey}] key[{$datas['field']}] val[".json_encode($datas)."]]. Detail[Question store save hash failed]");
            return false;
        }
        $this->objRedis->expire($redisKey, self::MSG_EXPIRE_TIME);

        //更新有序集
        $redisKey = $this->getCacheKey($uid, "sort");
        $ret = $this->objRedis->zadd($redisKey, $datas['create_time'], $datas["field"]);
        if ($ret)
        {
            $this->objRedis->expire($redisKey, self::MSG_EXPIRE_TIME);
        }

        $this->delExpireMsg($uid);
        if (!$ret) {
            Bd_Log::warning("Msg store error. Abstract[zkey[{$redisKey}] score[{$datas['create_time']}] val[{$datas['field']}]]. Detail[Question store update sorted list failed]");
        }

        return $ret ? true : false;
    }

    public function delExpireMsg($uid)
    {
        $cacheKeySort = $this->getCacheKey($uid, "sort");
        $cacheKeyHash = $this->getCacheKey($uid, "hash");
        $limit = $this->getLimit();

        $datas = $this->objRedis->zrevrange($cacheKeySort, 0, -1);

        $items = [];

        if (count($datas) > $limit) {

            /************************处理bug :删除的时候如果hash删除失败，会一直存在里面，还有之前的垃圾数据一直清除不了*******************************/
            // 后面所有用户的数据清洗完成了可以去掉
            $allMembers = [];
            foreach ($datas as $data) {
                $allMembers[] = $data["member"];    // 获取排序列表中的所有数据
            }

            // 如果cacheKeyHash中的元素数量多于2000，直接删掉，重新填充，为了统一，不做特殊处理，直接删除重新赋值
            $len = $this->objRedis->hlen($cacheKeyHash);
            if ($len > 2000) {
                Bd_Log::warning("Message_Question_Hash_Len_Greater than {$len}, key[{$cacheKeyHash}]");
                $allValues = $this->objRedis->hmget($cacheKeyHash, $allMembers);  // 删除所有的数据，重新填入
                $hmsetData = [
                    "key" => $cacheKeyHash,
                    "fields" => []
                ];

                // 获取所有在排序列表中的数据，拼接成指定格式
                foreach($allValues as $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $arrValue = json_decode($value, true);
                    $arr = [];
                    if (!empty($arrValue["field"])) {
                        $arr["field"] = strval($arrValue["field"]);
                        $arr["value"] = $value;
                        $hmsetData["fields"][] = $arr;
                    }
                }
                if (!empty($hmsetData["fields"]) && $this->objRedis->del($cacheKeyHash)) {
                    // 如果删除原来的数据成功，并且要批量设置的有内容，重试3次很重要 TODO 填充其他消息的时候可能导致冲突，但一个用户处理一次之后，后面应该不会有这种情况了
                    $times  = 1;
                    $flag   = false;

                    $values = array();
                    foreach ($hmsetData["fields"] as $val) {
                        $f  = $val["field"];
                        $v  = $val["value"];
                        $values[$f] = $v;
                    }
                    while($times <= 3) {
                        $times++;
                        $key    = $hmsetData["key"];
                        $flag   = $this->objRedis->hmset($key, $values);
                        if ($flag) {
                            break;
                        }
                        usleep(10000);
                    }
                    if (!$flag) {
                        Bd_Log::warning("Message_Question_Hash_Del_And_Hmset_Failed data [".json_encode($hmsetData)."]");
                    } else {
                        Bd_Log::warning("Message_Question_Hash_Del_And_Hmset_Success data [".json_encode($hmsetData)."]");
                    }
                }
            } else {
                // 1000 以内，在mcpack的65535限制范围之内(大部分时候)
                $hashDatas = $this->objRedis->hkeys($cacheKeyHash);
                if ($hashDatas) {
                    foreach ($hashDatas as $hashField) {
                        if (!in_array($hashField, $allMembers)) {
                            $items[] = $hashField;          // 不在排序列表中的都是删除失败的脏数据，直接清掉
                        }
                    }
                }
            }
            /*********************处理bug end***********************/

            $rems = array_slice($datas, $limit);
            foreach ($rems as $rem) {
                $items[] = $rem["member"];
            }

            Bd_Log::warning("Message_Del_Question_Expire_Msg list [". json_encode($items) ."]");

            if (!$this->objRedis->zrem($cacheKeySort, $items)) {
                Bd_Log::warning("Msg expire error. Abstract[zkey{$cacheKeySort} zrem[".json_encode($items)."]]. Detail[Question store delete sort items failed]");
            }
            if (!$this->objRedis->hdel($cacheKeyHash, $items)) {
                Bd_Log::warning("Msg expire error. Abstract[hkey[{$cacheKeyHash}] hdel[".json_encode($items)."]]. Detail[Question store delete hash items failed]");
            }
        }
        return true;
    }

    public function delMsg($uid, $key)
    {
        $cacheKeySort = $this->getCacheKey($uid, "sort");
        $cacheKeyHash = $this->getCacheKey($uid, "hash");
        if (empty($key)) { #全部删除
            $ret = $this->objRedis->del($cacheKeySort);
            if ($ret) {
                $ret = $this->objRedis->del($cacheKeyHash);
            }
        } else {
            $lists = $this->objRedis->zrevrange($cacheKeySort, 0, -1);
            foreach ($lists as $item) {
                if ($key == $item["member"]) {
                    $member = $item["member"];
                    break;
                }
            }
            $ret = $this->objRedis->zrem($cacheKeySort, $member);
            if ($ret) {
                $ret = $this->objRedis->hdel($cacheKeyHash, $key);
            }
        }

        return $ret ? true : false;
    }

    public function getMsgCount($uid, $onlyUnread = true)
    {
        $count = 0;
        if ($onlyUnread) {
            $items = $this->getMsgList($uid);
            foreach ($items as $item) {
                $item = json_decode($item, true);
                $count += $item["unread_count"];
            }
        } else {
            $cacheKey = $this->getCacheKey($uid, "sort");
            $count = $this->objRedis->zcount($cacheKey);
        }
        return intval($count);
    }
    public function getMsgList($uid, $param = [])
    {
        $ret = [];
        $count = empty($param["count"]) ? $this->getLimit() : $param["count"];
        $offset = empty($param["offset"]) ? 0 : $param["offset"];
        $min = empty($param["min"]) ? false : $param["min"];
        $cacheKey = $this->getCacheKey($uid, "sort");
        $items = $this->objRedis->zrevrange($cacheKey, $offset, $count, $min);
        if (!empty($items)) {
            foreach ($items as $item) {
                $keys[] = $item["member"];
            }
            $cacheKey = $this->getCacheKey($uid, "hash");
            $ret = $this->objRedis->hmget($cacheKey, $keys);
        }
        return empty($ret) ? [] : $ret;
    }

    public function changeMsgRead($uid, $field)
    {
        $cacheKey = $this->getCacheKey($uid, "hash");
        $json = $this->objRedis->hget($cacheKey, $field);
        $data = json_decode($json, true);
        if (empty($data)) {
            Bd_Log::warning("Msg tag read error. Abstract[get value failed] Detail[hash{$cacheKey} key{$field} value{$json}]");
            return false;
        }
        $data["unread_count"] = 0;
        $ret = $this->objRedis->hset($cacheKey, $field, json_encode($data));
        return $ret ? true : false;
    }
}
