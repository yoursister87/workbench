<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_Gcc_CallOuttaskExport{
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    //{{{remoteApi
    /**
     * syncGcc
     * @param $arrConds
     * @return array
     */
    public function remoteApi($arrConds){
        try {
            $tgApiUrl = InterfaceConfig::GCC_ADD_CALL;
            $postData = "input=" . $arrConds;
            $objCurl = new Gj_Util_Curl();
            $res = $objCurl->post($tgApiUrl, $postData, true);
            $resArr = json_decode($res,true);
            if($resArr['Ret']){
                $this->data['errorno'] = 0;
            }else{
                $this->data['errorno'] = 2122;
            }
            $this->data['data'] = $resArr['Message'];
            $this->data['errormsg'] = $resArr['Message'];
        } catch (Exception $e) {
            $this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
            $this->data['errormsg'] = ErrorConst::E_DB_FAILED_MSG;
        }
        return $this->data;
    }//}}}
    //{{{checkSpamKeyword
    /**
     * 实时防垃圾
     * @param $keyword
     * @return array
     */
    public function checkSpamKeyword($keyword){
        if(!class_exists('SpamDefenceNamespace')){
            Gj\Gj_Autoloader::addClassMap(array('SpamDefenceNamespace'=>CODE_BASE2 . '/util/spam_defence/SpamDefenceNamespace.class.php'));
        }
        try {
            $res = SpamDefenceNamespace::checkSpamKeyword($keyword, 'Content', 'HousePort');
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if(!$res){
            $this->data = ErrorCode::returnData(2123);
        }
        return $this->data;
    }//}}}
}
