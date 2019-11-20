<?php
/**
 * NapiSystem.php
 *
 * Napi系统消息存储管理类
 *
 * @author yanruitao<yanruitao@zuoyebang.com>
 * @version 1.0
 * @package Zybang
 */

class Hk_Service_Message_Store_NapiSystem extends Hk_Service_Message_Store_NapiBase
{
    /**
     * 全局系统消息前缀
     */
    const GLOBAL_SYSMSG_PREFIX = "globalsysmsg:";

    /**
     * inherit from base class
     */
    public $key = "NAPP_SYSMSG_USER_CONTENT_LIST";

    /**
     * inhreit from base class
     */
    public $limit = 10;

    public function saveMsg($uid, $data)
    {
        if (empty($uid) || empty($data)) {
            return false;
        }
        $cacheKey = $this->getCacheKey($uid);
        Bd_Log::addNotice("cacheKey", $cacheKey);

        $data["create_time"] = intval(microtime(true)*1000);
        $data["unread_count"] = isset($data["unread_count"]) ? intval($data["unread_count"]) : 1;

        $ret = $this->objRedis->zadd($cacheKey, $data["create_time"], json_encode($data));
        if ($ret) {
            $this->objRedis->expire($cacheKey, self::MSG_EXPIRE_TIME);
        }

        $this->delExpireMsg($uid);
        if (!$ret) {
            Bd_Log::warning("Msg store error: " . json_encode($data));
        }

        return $ret;
    }

    public function delExpireMsg($uid)
    {
        $cacheKey = $this->getCacheKey($uid);
        $limit = $this->getLimit();

        $datas = $this->objRedis->zrevrange($cacheKey, 0, -1);
        if (count($datas) > $limit) {
            $rems = array_slice($datas, $limit);
            foreach ($rems as $rem) {
                $items[] = $rem["member"];
            }
            return $this->objRedis->zrem($cacheKey, $items);   
        }
        return true;
    }

    public function delMsg($uid, $key)
    {
        $cacheKey = $this->getCacheKey($uid);
        if (empty($key)) { #全部删除
            $ret = $this->objRedis->del($cacheKey);
        } else {
            $ret = $this->objRedis->zremrangebyscore($cacheKey, $key, $key);    
        }

        return $ret;
    }

    public function getMsgCount($uid, $onlyUnread = true)
    {
        $cacheKey = $this->getCacheKey($uid);
        $count = 0;
        if ($onlyUnread) {
            $items = (array)$this->objRedis->zrevrange($cacheKey, 0, -1);
            foreach ($items as $item) {
                $item = json_decode($item["member"], true);
                $count += $item["unread_count"];
            }
        } else {
            $count = $this->objRedis->zcount($cacheKey, 0, intval(microtime(true)*1000));
        }
        return intval($count);
    }

    public function getMsgList($uid, $param = [])
    {
        $count = empty($param["count"]) ? $this->getLimit() : $param["count"];
        $offset = empty($param["offset"]) ? 0 : $param["offset"];
        $min = empty($param["min"]) ? false : $param["min"];
        $cacheKey = $this->getCacheKey($uid);
        $ret = [];
        $items = $this->objRedis->zrevrange($cacheKey, $offset, $count, $min);
        if (!empty($items)) {
            foreach ($items as $item) {
                $ret[] = $item["member"];
            }
        }
        return $ret;
    }

    public function changeMsgRead($uid, $field)
    {
        $cacheKey = $this->getCacheKey($uid);
		$ret = true;
        if ($field) {
			$data = $this->objRedis->zrevrange($cacheKey, 0, 1, $field);
			if (empty($data)) {
				return true;
			}
			$data = array_pop($data); 
			$this->objRedis->zrem($cacheKey, $data['member']);
			$msgInfo = json_decode($data['member'], true);
			$msgInfo['unread_count'] = 0;
			if ($data['score'])
			{
				$ret = $this->objRedis->zadd($cacheKey, $data['score'], json_encode($msgInfo));
			}
        } else {
            $lists = $this->getMsgList($uid, [
                "count" => -1,
                "offset" => 0
            ]);
			foreach ($lists as $item) {
				if ($this->objRedis->zrem($cacheKey, $item)) {
					$item = json_decode($item, true);
					$item["unread_count"] = 0;
					$ret = $this->objRedis->zadd($cacheKey, $item["create_time"], json_encode($item));               
				}
        	}
		}
		return $ret ? true : false;
    }
}
