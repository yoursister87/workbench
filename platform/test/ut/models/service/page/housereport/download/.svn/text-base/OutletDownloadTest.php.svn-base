<?php
/**
 * File Name:OutletDownloadTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
*/
class OutletDownloadTest extends Testcase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    public function testgetTodayDownladIndex(){
        $params['date']['sDate'] = '2015-01-19';
        $params['date']['eDate'] = '2015-01-20';

        $params['accountIds'] = array(1,2);

        $ret = array(
            '2015-01-19_1',
            '2015-01-20_1',
            '2015-01-19_2',
            '2015-01-20_2',
        );

        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $res = $obj->getTodayDownladIndex($params);
		$this->assertEquals($res,$ret);
    }

    public function testgetAllOutlet(){
        $data = array(
            'data'=>array('list'=>array(array('customer_id'=>123))),
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getChildTreeByOrgId"));
        $obj->expects($this->any())
            ->method("getChildTreeByOrgId")
            ->will($this->returnValue($data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $ret = $obj->getAllOutlet(123);
        $res = array (
            0 => 123,
        );
		$this->assertEquals($res,$ret);
    }

    protected $getDownloadData = array (
        '日均表格' => 
        array (
            'title' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'title1',
                        'width' => 1,
                    ),
                    1 => 
                    array (
                        'name' => 'title2',
                        'width' => 1,
                    ),
                ),
            ),
            'data' => 
            array (
                0 => 
                array (
                    0 => 'data_title1',
                    1 => 'data_title2',
                ),
                1 => 1,
                2 => 2,
                3 => 3,
            ),
        ),
    );

    public function testgetDownLoadData(){
        $downData = array(
            array(
                'title'=>'title1',
                'title_data'=>array('data_title1'),
                'total_data'=>array(1=>1,2=>2,3=>3),
            ),
            array(
                'title'=>'title2',
                'title_data'=>array('data_title2'),
            ),

        );
        
        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $ret = $obj->getDownLoadData($downData);
        $res = $this->getDownloadData;
        $this->assertEquals($res,$ret);
    }

    public function testgroupOutletData(){
	/*
        $params = array(
            'stype'=>1,
            'keyword'=>'haha'
        );
        $dataServiceRet = array(
            'data'=>array(
                array('AccountId'=>123),
            ),
        );

        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",
            array("SearchAgent"));
        $obj->expects($this->any())
            ->method("SearchAgent")
            ->will($this->returnValue($dataServiceRet));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",$obj);

        $accountIdList = array(
            'data'=>array(
                array(
                    'AccountId'=>123,
                    'AccountName'=>'haha',
                    'CellPhone'=>'15812341234',
                    'CustomerId'=>1,
                    'OwnerType'=>2,
                	'UserId'=>2,
                    'Status'=>1,
                )
            ),
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",
            array("getAccountInfoById"));
        $obj->expects($this->any())
            ->method("getAccountInfoById")
            ->will($this->returnValue($accountIdList));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $ret = $obj->groupOutletData($params);
        $res = array (
            'accountIds' => 
            array (
                123 => 123,
            ),
            'accountName' => 
            array (
                123 => 'haha',
            ),
            'cellPhone' => 
            array (
                123 => '15812341234',
            ),
            'customerId' => 
            array (
                123 => 1,
            ),
            'ownerType' => 
            array (
                123 => 2,
            ),
        		'user_id' =>
        		array (
        				123 => 2,
        		),
            'status' => 
            array (
                123 => 1,
            ),
        );

		$this->assertEquals($res,$ret);
        $params = array('userId'=>123,'businessScope'=>1);
        
        $obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",
            array("getAllOutlet"));
        $obj->expects($this->any())
            ->method("getAllOutlet")
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);


        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",
            array("getAccountListByCompanyId"));
        $obj->expects($this->any())
            ->method("getAccountListByCompanyId")
            ->will($this->returnValue($accountIdList));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);

        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $ret = $obj->groupOutletData($params);
		$this->assertEquals($res,$ret);

        $params = array('userId'=>123,'businessScope'=>1,'islost'=>true);
        
        $obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",
            array("getAllOutlet"));
        $obj->expects($this->any())
            ->method("getAllOutlet")
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);


        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",
            array("getAccountListByCompanyId"));
        $obj->expects($this->any())
            ->method("getAccountListByCompanyId")
            ->will($this->returnValue($accountIdList));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);

        $obj = new Service_Page_HouseReport_Download_OutletDownload();
        $ret = $obj->groupOutletData($params);
		$this->assertEquals($res,$ret);
	 */
    }

    public function testexecute(){
        $arrInput = array(
            'downloadType'=>1,
            'date'=>array(
                'sDate'=>'2015-01-27',
                'eDate'=>'2015-01-28',
            ),
            'businessScope'=>array(1),
            'houseType'=>array(1,3,5),
            'product'=>array(1),
            'dtype'=>array(2),
        );
        
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletSumDownloadData",
            array("groupDownLoadData"));
        $obj->expects($this->any())
            ->method("groupDownLoadData")
            ->will($this->returnValue(array(1234)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletSumDownloadData",$obj);

        $matchData  = array(
            array(
                'title'=>'title1',
                'title_data'=>array('data_title1'),
                'total_data'=>array(1=>1,2=>2,3=>3),
            ),
            array(
                'title'=>'title2',
                'title_data'=>array('data_title2'),
            ),

        );

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",
            array("matchTableData"));
        $obj->expects($this->any())
            ->method("matchTableData")
            ->will($this->returnValue($matchData));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj);
        
        $accountData = array(
            'accountIds'=>array(1),
            'accountName'=>array(1),
            'cellPhone'=>array(1),
            'customerId'=>array(1),
            'ownerType'=>array(1),
            'status'=>array(1),
            'date_AccountId'=>array(1234)
        );
        #测试方法一
        $obj = $this->genObjectMock("Service_Page_HouseReport_Download_OutletDownload",
            array("groupOutletData",'createCsv'),array(),'',true);
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue($accountData));
        $obj->expects($this->any())
            ->method("createCsv")
            ->will($this->returnValue(1234));

        $ret = $obj->execute($arrInput);
        $res = array (
            'data' => 
            array (
            ),
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
        );
        $this->assertEquals($res,$ret);
        //测试方法二
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletAvgDownloadData",
            array("groupDownLoadData"));
        $obj->expects($this->any())
            ->method("groupDownLoadData")
            ->will($this->returnValue(array(1234)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletAvgDownloadData",$obj);


        $arrInput['downloadType'] = 2;

        $obj = $this->genObjectMock("Service_Page_HouseReport_Download_OutletDownload",
            array("groupOutletData",'createCsv'),array(),'',true);
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue($accountData));
        $obj->expects($this->any())
            ->method("createCsv")
            ->will($this->returnValue(1234));
        $ret = $obj->execute($arrInput);
        $res = array (
            'data' => 
            array (
            ),
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
        );
        $this->assertEquals($res,$ret);
        
        //测试方法三
        $arrInput['downloadType'] = 3;

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletTodayDownloadData",
            array("groupDownLoadData"));
        $obj->expects($this->any())
            ->method("groupDownLoadData")
            ->will($this->returnValue(array(1234)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletTodayDownloadData",$obj);


        $obj = $this->genObjectMock("Service_Page_HouseReport_Download_OutletDownload",
            array("groupOutletData",'createCsv'),array(),'',true);
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue($accountData));
        $obj->expects($this->any())
            ->method("createCsv")
            ->will($this->returnValue(1234));
        $ret = $obj->execute($arrInput);

        $res = array (
            'data' => 
            array (
            ),
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
        );
        $this->assertEquals($res,$ret);
        //测试方法4
        $arrInput['downloadType'] = 4;

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountHoursDownloadData",
            array("groupDownLoadData"));
        $obj->expects($this->any())
            ->method("groupDownLoadData")
            ->will($this->returnValue(array(1234)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupAccountHoursDownloadData",$obj);


        $obj = $this->genObjectMock("Service_Page_HouseReport_Download_OutletDownload",
            array("groupOutletData",'createCsv'),array(),'',true);
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue($accountData));
        $obj->expects($this->any())
            ->method("createCsv")
            ->will($this->returnValue(1234));
        $ret = $obj->execute($arrInput);

        $res = array (
            'data' => 
            array (
            ),
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
        );
        $this->assertEquals($res,$ret);
        
        //测试方法5
        $arrInput['downloadType'] = 6;

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletSumDownloadData",
            array("groupDownLoadData"));
        $obj->expects($this->any())
            ->method("groupDownLoadData")
            ->will($this->returnValue(array(1234)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupAccountHoursDownloadData",$obj);


        $obj = $this->genObjectMock("Service_Page_HouseReport_Download_OutletDownload",
            array("groupOutletData",'createCsv'),array(),'',true);
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue(array(1234)));
        $obj->expects($this->any())
            ->method("createCsv")
            ->will($this->returnValue(1234));
        $ret = $obj->execute($arrInput);

        $res = array (
            'data' => 
            array (
            ),
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
        );
        $this->assertEquals($res,$ret);
    }
}
