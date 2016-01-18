<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   renyajing$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Dao_Xapian_Broker
{
    const XAPIAN_ID = 1006;
    protected static $xapianFieldArr = array(
        'f' => array(
            'user_id' => array(0, 0),
            'account_id' => array(1, 0),
            'name' => array(1, 3),
            'image' => array(0, 0),
            'gender' => array(0, 0),
            'phone' => array(1, 0),
            'tel' => array(1, 0),
            'city' => array(1, 0),
            'company_id' => array(1, 0),
            'company_name' => array(1, 0),
            'company_short_name' => array(1, 0),
            'customer_name' => array(1, 0),
            'pos_info' => array(0, 0),
            'district_ids' => array(1, 2),
            'street_ids' => array(1, 2),
            'xiaoqu_names' => array(1, 2),
            'fang6' => array(0, 0),
            'fang7' => array(0, 0),
            'fang8' => array(0, 0),
            'fang9' => array(0, 0),
            'fang11' => array(0, 0),
            'update_at' => array(0, 0)

        ),
        'n' => array(
            'credit_score' => array(1, 0),
            'status' => array(1, 0),
            'company_id' => array(1, 0),
            'customer_id' => array(1, 0),
            'fang1' => array(1, 0),
            'fang3' => array(1, 0),
            'fang5' => array(1, 0),
            'fang12' => array(1, 0),
            'create_at' => array(1, 0)
        )
    );
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
            'user_id',
            'account_id',
            'name',
            'image',
            'gender',
            'phone',
            'tel',
            'credit_score',
            'city',
            'status',
            'company_name',
            'company_short_name',
            'company_id',
            'customer_name',
            'customer_id',
            'district_ids',
            'street_ids',
            'xiaoqu_names',
            'pos_info',
            'fang1',
            'fang3',
            'fang5',
            'fang6',
            'fang7',
            'fang8',
            'fang9',
            'fang11',
            'fang12'
    );//}}}
    protected static $defaultOrderFieldArr = array('credit_score' => 'desc');
    protected static $defaultQueyrFilterArr = array('status' => array(1, 1));
    /**getSourceList{{{*/
    public function getSourceList($queryConfigArr){
        $result = array();
        // create builder
        if (!empty($queryConfigArr['queryFilter']) && is_array($queryConfigArr['queryFilter'])) {
            $queryConfigArr['queryFilter'] = array_merge($queryConfigArr['queryFilter'], self::$defaultQueyrFilterArr);
        }
        $searchUtil = Gj_LayerProxy::getProxy('Util_HouseXapian');
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
        // sendQueryAndGetResult
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil');
        $searchId = $searchHandle->query($builder->getQueryString());
        if (is_numeric($searchId) && $searchId != 0) {
            $result = $searchHandle->getResult($searchId);
        } else {
            throw new Exception("检索searchId返回错误 :" . $searchId, ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
} 
