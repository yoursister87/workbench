<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @author               $Author:   zhangrong <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * 
 */
require_once CODE_BASE2. '/util/hibase/config.php';
require_once SE_HIBASE . '/cate/adfang.index.class.php';

class Util_HouseHibase
{


    public function createQueryParam($hibaseId, $queryConfigArr){
        $queryFilterArr = $queryConfigArr['queryFilter'];
        $indexConfigName = ucfirst($hibaseId)."IndexConfig"; 
        $hibaseIndexList = new HibaseIndexList(new $indexConfigName());
        $hibaseFilterBuilder = new HibaseFilterBuilder();

        $inFilter = new InFilter();
        $rangeFilter = new RangeFilter();

        $inFilter->filter($hibaseIndexList->fields['category_script_index'], array(2));//2表示房产
        if(is_array($queryFilterArr['city_code'] )){
            $inFilter->filter($hibaseIndexList->fields['city'], $queryFilterArr['city_code']);
        }
        if(isset($queryFilterArr['major_category_script_index']) && !empty($queryFilterArr['major_category_script_index']) ){
            $inFilter->filter($hibaseIndexList->fields['major_category'], array($queryFilterArr['major_category_script_index']));
        }
        if(is_array($queryFilterArr['listing_status'] )){
            $inFilter->filter($hibaseIndexList->fields['listing_status'],  $queryFilterArr['listing_status']);
        }
        if(is_array($queryFilterArr['biz_type'] )){
            $inFilter->filter($hibaseIndexList->fields['biz_type'],  $queryFilterArr['biz_type']);
        }
        if(is_array($queryFilterArr['premier_status'] )){
            $inFilter->filter($hibaseIndexList->fields['premier_status'],  $queryFilterArr['premier_status']);
        }
        if(is_array($queryFilterArr['post_at'] )){
             $rangeFilter->filter($hibaseIndexList->fields['post_at'],  $queryFilterArr['post_at'][0], $queryFilterArr['post_at'][1]);
         }
        //$inFilter->filter($hibaseIndexList->fields['puid'], array(1451963406,1471232456,1471232912));
        $hibaseFilterBuilder->addFilter($inFilter);
        $hibaseFilterBuilder->addFilter($rangeFilter);

        //排序字段
        $orderByFilter = new OrderByFilter();
        $orderByFilter->add($hibaseIndexList->fields['post_at']);
        $hibaseFilterBuilder->addFilter($orderByFilter);
        //关键字检索字段
        if(isset($queryFilterArr['keyword'])){
            $fieldsFilter = new FieldsFilter(FieldsFilterType::$FieldFilter);
            $fieldsFilter->filter($hibaseIndexList->fields['title'])
                ->filter($hibaseIndexList->fields['phone'])
                ->filter($hibaseIndexList->fields['description']);
            $hibaseFilterBuilder->addFilter($fieldsFilter);
        }

       //查询的字段 
        $display = new HibaseDisplay();
        if(is_array($queryConfigArr['queryField']) && !empty($queryConfigArr['queryField'])){
            foreach((array)$queryConfigArr['queryField'] as $v){
                if(isset($hibaseIndexList->fields[$v])) {
                    $display->add($hibaseIndexList->fields[$v]);
                }
            }
            $displayFields = $display->value();
        } else {
            $displayFields = $display->allFields($hibaseIndexList);
        }
        $queryParams['hibaseFilterBuilder'] = $hibaseFilterBuilder;
        $queryParams['display'] = $displayFields;
        $queryParams['order'] =  HibaseConfig::$order_type[$queryFilterArr['orderby']];//排序类型
        $queryParams['size']   = $queryFilterArr['page_size'] >1000 ?1000:$queryFilterArr['page_size'];
        $queryParams['offset'] = $queryFilterArr['page_no']>0 ? ($queryFilterArr['page_no']-1)*$queryParams['size']:0;
        $queryParams['query'] = $queryFilterArr['keyword'];
        return $queryParams;

    }
    
    /*{{{ search */
      /*
        $queryParams的参数如下：
         * 搜索
         * @param url                    查询url
         * @param query                  查询关键字
         * @param source                 查询来源
         * @param not                    不希望在查询结果中出现的关键字
         * @param dbid                   数据库id
         * @param secid                  分段id
         * @param offset                 查询记录位置的偏移量，从0开始
         * @param size                   每页数量
         * @param eagleFilterBuilder     搜索结果过滤器
         * @param booland                是否完全匹配
         * @param sortType               排序类型，升序或者降序
         * @param display                显示字段
         * @param debug                  是否为调试模式，默认为1
         * @param lifetime               缓存周期，以秒为单位
         * @param qid                    查询id，供调试使用，默认为0
         * @param ntop                   查询返回结果最多个数，最大亦不超过2000个
         * @throws Exception
         */
    public function search($searchServiceName, $hibaseId, $queryParams){
        try{
            if($queryParams['offset'] >= 2000){
                $queryParams['ntop'] = $queryParams['offset'] + $queryParams['size'];
            }
            $queryStr = AbstractSearch::search('/s?', $queryParams['query'], $hibaseId, $queryParams);
            if( $queryParams['ntop'] && !strstr($queryStr, 'ntop=')){
                $queryStr.="&ntop=".$queryParams['ntop'];
            }
            if($_GET['hibase_debug']==1){
                echo $queryStr;
            }
            $hosts = array(
                array( 'host'=>'10.1.4.156', 'port'=>4830,'weight'=>1)
            );
            $this->finagleRequest = new FinagleRequest($searchServiceName /*,$hosts*/);        
            $data = $this->finagleRequest->get($queryStr);
            $ret = array(
                'errorno' => 0,
                'data' => $this->formatData($hibaseId,$data),
            ); 
        } catch(Exception $e){
           $ret = array(
                            'errorno' => $e->getCode(),
                            'errormsg' => $e->getMessage(),
                        );  
        }
        return $ret;
        
    }
    /*}}}*/
   
    /*{{{ formatData */ 
    protected  function formatData($hibaseId, $data){
        $indexConfigName = ucfirst($hibaseId)."IndexConfig"; 
        $ret = array('list'=>array(),'total'=>0);
        if(!empty($data->records)){
            foreach((array)$data->records as $k => $v){
                foreach((array)$v['summary'] as $kk => $vv){
                   if(isset($indexConfigName::$INDEX_MAP[$kk])){
                        $key =  $indexConfigName::$INDEX_MAP[$kk]; 
                        $tmp[$key] = $vv; 
                    }                  
                } 
                $ret['list'][] = $tmp;
            }
            $ret['total'] = $data->total;
        }
        
        return $ret;
    } 
   /* }}}*/
}
