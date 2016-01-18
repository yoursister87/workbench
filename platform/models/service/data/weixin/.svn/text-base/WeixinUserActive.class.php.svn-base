<?php

/**
 * Class Service_Data_Weixin_WeixinUserActive
 * @codeCoverageIgnore
 * 只有一个方法 ， 取大于某个时间的所有用户，因结果是动态变化的，因此无法测试正确与否，在调用处加异常处理
 */
class Service_Data_Weixin_WeixinUserActive
{
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function getUserList( $maxTimeStamp = 0, $major_catgory = 1 ){
        $arrRet = $this->arrRet;
        $daoWeixinUserActive = Gj_LayerProxy::getProxy("Dao_Weixin_WeixinUserActive" );
        $data = $daoWeixinUserActive->getUserList( $maxTimeStamp, $major_catgory );
        if( is_array($data) && count( $data)>0  ){
            $arrRet['data'] = $data;
        }else{
            $arrRet['errorno']  = ErrorConst::E_DATA_NOT_EXIST_CODE;
            $arrRet['errormsg'] = ErrorConst::E_DATA_NOT_EXIST_MSG;
        }

        return $arrRet;

    }

}