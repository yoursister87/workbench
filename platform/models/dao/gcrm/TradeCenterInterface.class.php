<?php
/**
 * Created by PhpStorm.
 * User: zhuyaohui
 * Date: 2014/10/27
 * Time: 17:18
 */

Class Dao_Gcrm_TradeCenterInterface{
    public function getBalances($param){
        //为了把类TC_Client加载进来
        $obj = new Util_TradeCenterUtil();
        $client = TC_Client::getClient('house','balance');
        $result = $client->getBalances(new TCThriftTypes_BalanceSearch($param));
        return $result;
    }

    //这个方法输入参数不一致。
    public function addUserBalance($param,$creator,$creatorName,$remark){
        $obj = new Util_TradeCenterUtil();
        $client = TC_Client::getClient('house','balance');
        $result = $client->addUserBalance(new TCThriftTypes_Balance($param), $creator, $creatorName, $remark);
        return $result;
    }

    public function consume($param){
        $obj = new Util_TradeCenterUtil();
        $client = TC_Client::getClient('house','balance');
        $result = $client->consume(new TCThriftTypes_BalanceConsume($param));
        return $result;
    }
}
