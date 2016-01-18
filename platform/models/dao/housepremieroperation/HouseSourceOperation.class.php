<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */
class Dao_Housepremieroperation_HouseSourceOperation extends Gj_Base_MysqlDao {
    protected $dbName = 'house_premier_operation';
    protected $dbNameAlias = 'hp_op';
    protected $dbHandler;
    protected $tableName;
    protected $tablePrefix = "house_source_operation_";
    protected $table_fields = array('HouseId','Type','OperationType','CityId','Status','Message','CreatorId','CreatorName','CreatedTime','ModifierId','ModifierName','ModifiedTime','UserAgent',);
    protected $index = "FORCE INDEX(creatorid)";

    public function insertOp($arrRow){

        $this->setTableName();
        return  $this->insert($arrRow);

    }

    private  function setTableName($intDay=null){
        if(empty($intDay)){
            $curDate = getdate();
            $intDay = $curDate['mday'];
        }
        $table_sign = sprintf("%02d", $intDay);
        $this->tableName = $this->tablePrefix.$table_sign;
    }
    public function getSelectBySplit($arrFields,$arrConds,$strBeginTime,$strEndTime,$strAppends = null){
        $curTime = date('Y-m-d',strtotime($strBeginTime));
        $table_count = 0;
        $arrRet = array();
        while(true){
            $intDay = date("j",strtotime($curTime));
            $this->setTableName($intDay);
            $arrDbRet = $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strAppends);
            $arrRet = array_merge($arrRet,$arrDbRet);
            $table_count++;
            if($curTime == date('Y-m-d',strtotime($strEndTime)) ||$table_count >7){
                break;
            }
            $curTime = date('Y-m-d',strtotime("$curTime +1 day"));
        }
        return $arrRet;
    }
    public function getOPStatByOperationType($arrFields,$arrConds,$strBeginTime,$strEndTime){

        $strAppends = "group by OperationType";

        return $this->getSelectBySplit($arrFields,$arrConds,$strBeginTime,$strEndTime,$strAppends);
    }

    public function getOpList($strFields,$strConds,$strBeginTime,$strEndTime,$strLimit){
        $curTime = date('Y-m-d',strtotime($strBeginTime));
        $table_count = 0;
        $arrDay = array();
        while(true){
            $arrDay[] = date("j",strtotime($curTime));
            $table_count++;
            if($curTime == date('Y-m-d',strtotime($strEndTime)) ||$table_count >7){
                break;
            }
            $curTime = date('Y-m-d',strtotime("$curTime +1 day"));
        }

        $arrSql = array();
        foreach($arrDay as $intDay){
            $table_sign = sprintf("%02d", $intDay);
            $arrSql[] = 'select '.$strFields.' from '.$this->tablePrefix.$table_sign." ".$this->index.' where '.$strConds.' and `OperationType`!="user-add"';
        }
        $strSql = implode(" union all ",$arrSql);
        $strSql = $strSql. " ".$strLimit;
        $data  = $this->dbHandler->query($strSql);

        return $data;

    }

    public function getTotalCount($arrConds,$strBeginTime,$strEndTime){
        $arrFields = array('count(1) as num');
        $arrTotalNum =  $this->getSelectBySplit($arrFields,$arrConds,$strBeginTime,$strEndTime);
        $intRet = 0;
        foreach($arrTotalNum as $value){
            $intRet +=  $value['num'];
        }
        return $intRet;
    }


}
