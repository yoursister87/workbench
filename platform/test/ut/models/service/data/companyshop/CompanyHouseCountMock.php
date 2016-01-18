<?php
/*
 * File Name:CompanyHouseCountMock.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class CompanyHouseCountMock extends Service_Data_CompanyShop_CompanyHouseCount
{
    public function getManagerAccountData($companyId){
        return parent::getManagerAccountData($companyId);
    }

    public function getCompanyOrgReportByOrgData($orgId,$date){
        return parent::getCompanyOrgReportByOrgData($orgId,$date);
    }
}
