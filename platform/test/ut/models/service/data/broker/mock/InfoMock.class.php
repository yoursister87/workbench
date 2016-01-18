<?php
class InfoMock extends Service_Data_Broker_Info {

    public function getCacheDataByAccountId(){
        return parent::getCacheDataByAccountId();
    }
    public function getUserBangbangYears(){
        return parent::getUserBangbangYears();
    }
    public function saveAccountInfoToCache(){
        return parent::saveAccountInfoToCache();
    }
}
