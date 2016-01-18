<?php
class Service_Data_Xiaoqu_News
{
    /**addXiaoquNews 添加小区新鲜事 {{{ */
    /**
     * @params $data array()
     * @return array()
     */
    public function addXiaoquNews($data){
        try {
            $obj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNews');
            $id = $obj->addXiaoquNews($data);
            if (FALSE != $id && (!empty($data['imageList']) && is_array($data['imageList']))) {
                foreach ($data['imageList'] as $item) {
                    $imageObj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNewsImage');
                    $imageRet = $imageObj->addXiaoquNewsImage($id, $data['xiaoquId'], $item);
                }   
            }   
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $id,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getAndPatchNewsImagesListByNewsId{{{*/
    /**
     * @params $newsId 小区新鲜事id
     * @return array()
     */
    protected function getAndPatchNewsImagesListByNewsId($newsId){
        $imageList = array();
        $imageData = $this->getXiaoquNewsImagesListByNewsId($newsId);
        if (ErrorConst::SUCCESS_CODE === $imageData['errorno']) {
            $imageList = $imageData['data'];
        }
        return $imageList;
    }//}}}
    /** getXiaoquNewsList 获取小区新鲜事 {{{ */
    /**
     * 获取小区新鲜事
     * @param  int $limit  需要几条数据
     * @param  int $status  小区动态状态
     * @return array 小区动态列表
     */
    public function getXiaoquNewsList($status = 3, $limit = 10) {
        try {
            $obj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNews');
            $list = $obj->getXiaoquNewsByStatus($status, $limit);
            if (!empty($list) && is_array($list)) {
                foreach ($list as $key => &$item) {
                    $item['image_list'] = $this->getAndPatchNewsImagesListByNewsId($item['id']);
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $list,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /** getXiaoquNewsListByAccountId 通过accountId获取小区动态列表 {{{ */
    /**
     * 获取小区新鲜事
     * @param  int $accountid  用户account_id
     * @param  int $status  小区动态状态
     * @param  int $limit  取多少条数据
     * @return array 小区动态列表
     */
    public function getXiaoquNewsListByAccountId($accountId, $status = null, $limit = 10) {
        try {
            $obj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNews');
            $list = $obj->getXiaoquNewsByAccountId($accountId, $status, $limit);
            if (!empty($list) && is_array($list)) {
                foreach ($list as $key => &$item) {
                    $item['image_list'] = $this->getAndPatchNewsImagesListByNewsId($item['id']);
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $list,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /* getXiaoquNewsListByXiaoquId 通过xiaoquId获取小区动态列表 {{{*/
    /**
     * 获取小区新鲜事
     * @param  int $xiaoquId  小区id
     * @param  int $status  小区动态状态
     * @param  int $limit  取多少条数据
     * @return array 小区动态列表
     */
    public function getXiaoquNewsListByXiaoquId($xiaoquId, $status = 3, $limit = 10) {
        try {
            $obj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNews');
            $list = $obj->getXiaoquNewsByXiaoquId($xiaoquId, $status, $limit);
            if (!empty($list) && is_array($list)) {
                foreach ($list as $key => &$item) {
                    $item['image_list'] = $this->getAndPatchNewsImagesListByNewsId($item['id']);
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $list,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /** getXiaoquNewsImagesListByNewsId 通过xiaoqu_news_id获取相关的小区图片 {{{ */
    /**
     * @params $newsId int 小区新鲜事id
     * @return array()
     */
    public function getXiaoquNewsImagesListByNewsId($newsId){
        try {
            $imageObj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNewsImage');
            $images =  $imageObj->getXiaoquNewsImageListByNewsId($newsId);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $images,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /* updateXiaoquNewsStatus 修改小区新鲜事 {{{ */
    /**
     * 修改小区新鲜事
     * @param  int $id    小区新鲜事id
     * @param  int $status  小区新鲜事状态
     * @param  string $reason  审核拒绝原因
     * @return bool 是否更改成功
     */
    public function updateXiaoquNewsStatus($id, $status, $reason = ''){
        try {
            $obj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquNews');
            $ret = $obj->updateXiaoquNewsStatusById($id, $status, $reason);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $ret,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    // {{{ just for test
    /**
     * @brief 
     * @param $name
     * @param $value
     * @codeCoverageIgnore
     */
    public function __set($name, $value) {
        if (Gj_LayerProxy::$is_ut === true) {
            $this->$name = $value;
        }
    }
    /**
     * @brief 
     * @param $func
     * @param $args
     * @codeCoverageIgnore
     */
    public function __call($func, $args) {
        if (Gj_LayerProxy::$is_ut === true) {
            switch (count($args)) {
                case 1:
                    return $this->$func($args[0]);
                case 2:
                    return $this->$func($args[0], $args[1]);
                default :
                    return $this->$func();
            }
        }
    }
    //}}}
}
