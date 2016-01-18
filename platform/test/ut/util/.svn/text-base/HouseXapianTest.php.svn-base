<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:  lijie2$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */

class Util_HouseXapianBuild {

    public function setBetweenFilter($fieldName, $value){

        $fieldName .= '|setBetweenFilter';

        $this->$fieldName = $value;
    }

    public function setInFilter($fieldName, $value){

        $fieldName .= '|setInFilter';

        $this->$fieldName = $value;
    }

    public function setEqualFilter($fieldName, $value){

        $fieldName .= '|setEqualFilter';

        $this->$fieldName = $value;
    }

    public function setFields($fields){
        $this->fields = $fields;
    }

    public function setLimit($start, $end){
        $this->limit = array($start, $end);
    }

    public function setDescOrderBy($field){
        $this->desc[] = $field;
    }

    public function setTextFilter($keyword, $textFilter){

        $keyword .= '|setEqualFilter';

        $this->$keyword = $textFilter;   
    }

    public function setGroupBy($groupby){
        $this->groupby = $groupby;
    }
}

class Util_HouseXapianMock extends Util_HouseXapian {

    public function formatQueryFilter($queryFilterArr){
        return parent::formatQueryFilter($queryFilterArr);
    }

    public function transToQueryParams($builder, $queryFilterArr, $xapianFieldArr){
        return parent::transToQueryParams($builder, $queryFilterArr, $xapianFieldArr);
    }
} 

class Util_HouseXapianMockTest extends Testcase_PTest {

    public function providerCreateQueryBuilder(){

        return array(

            array(
                1, array(), array(), array(), array(), false,
            ),

            array(
                1,
                array(
                    "queryFilter"       =>  array(
                        "city_code"     =>  0,
                        "offset_limit"  =>  array(0,10),
                        "textFilter"    =>  array(array(), array()),
                        "latlng"        =>  "11111.2222",
                        "keyword"       =>  "aaaa"
                    ),
                    "queryField"        =>  array("a","b"),
                    "order_field"       =>  array( "aaaaaaa" ),              //条件
                    "orderField"        =>  array( "aaa"=>"desc" )
                 ), 
                array(), 
                array(), 
                array(),
                array(                                                        //期望返回
                    "category|setEqualFilter"   =>  1,
                    "fields"                    =>  array('a','b'),
                    "city|setEqualFilter"       =>  0,
                    "limit"                     =>  array(0, 10),
                    "groupby"                   => '',
                    "desc"                      =>  array( "aaa" ),
                    "aaaa|setEqualFilter"       =>  array( array("latlng"=>"11111.2222"), array() )
                ) 
            ),
            
            array(
                1,
                array(
                    "queryFilter"       =>  array(
                        "city_code"     =>  0,
                        "offset_limit"  =>  array(0,10),
                        "textFilter"    =>  array(array(), array()),
                        "latlng"        =>  "11111.2222",
                        "keyword"       =>  "aaaa"
                    ),
                 ), 
                array(), 
                array(), 
                array(),
                array(                                                        //期望返回
                    "category|setEqualFilter"   =>  1,
                    "fields"                    =>  array(),
                    "city|setEqualFilter"       =>  0,
                    "limit"                     =>  array(0, 10),
                    "groupby"                   => '',
                    "aaaa|setEqualFilter"       =>  array( array("latlng"=>"11111.2222"), array() )
                ) 
            ),
        );
    }

    /**
     * @dataProvider providerCreateQueryBuilder 
     */
    public function testCreateQueryBuilder($xapianId, $queryConfigArr, $xapianFieldArr, $defaultQueryFieldArr, $defaultOrderFieldArr, $wanted){

        $obj = $this->getMockBuilder("Util_HouseXapianMock")
                    ->setMethods(array("getPostListBuilder","formatQueryFilter", "transToQueryParams"))
                    ->getMock();

        $obj->expects($this->any())
            ->method('getPostListBuilder')
            ->willReturn(new Util_HouseXapianBuild());

        $obj->expects($this->any())
            ->method('formatQueryFilter')
            ->will($this->returnArgument(0));

        $obj->expects($this->any())
            ->method('transToQueryParams')
            ->will($this->returnArgument(0));

        $ret = $obj->createQueryBuilder($xapianId, $queryConfigArr, $xapianFieldArr, $defaultQueryFieldArr, $defaultOrderFieldArr);        

        $ret = is_object($ret) ? (array)$ret : $ret;

        $this->assertEquals($ret, $wanted);
    }
    
    public function providerFormatQueryFilter(){
        return array(
            //agent
            array(
                array("agent" => 1),
                array("agent" => 0)
            ),
            array(
                array("agent" => 3 ),
                array()
            ),

            //district
            array(
                array("street_list"=>array()),
                array("street_list"=>array())
            ),
            array(
                array(
                    "street_list"=>array(
                        array("script_index"=>0), 
                        array("script_index"=>1)
                    )
                ),
                array(
                    "street_list"=>array(
                        array("script_index"=>0), 
                        array("script_index"=>1)
                    ),
                    "street_id" => array(0,1)
                )
            ),

            //post_at
            array(
                array(
                    "postat_b" => 11.11,
                    "postat_e" => 22.22
                ),
                array(
                    "postat_b" => 11.11,
                    "postat_e" => 22.22,
                    "post_at" => array(11.11, 22.22)
                )
            ),
            //price
            array(
                array("price" => 6, "city_domain" => "bj", "major_category_script_index" => 5),
                array("price" => array(1500000,2000000), "city_domain" => "bj", "major_category_script_index" => 5)
            ),
            array(
                array("price_b" => 3000, "price_e" => 5000),
                array("price_b" => 3000, "price_e" => 5000, "price" => array(3000, 5000)),
            ),
            
            //area
            array(
                array("area_b"=>100,"area_e"=>200),
                array("area_b"=>100,"area_e"=>200, "area" => array(100, 200)),
            ),

            //niandai
            array(
                array('niandai'=> 100),
                array('niandai' => array(date('Y',time()), date('Y', time()))),
            ),

            //楼层
            array(
                array(
                    "ceng" => 2
                ),
                array("ceng" => array(2, 2))
            ),

            array(
                array(
                    "ceng" => 4
                ),
                array("ceng" => array(4, 6))
            ),

            array(
                array(
                    "ceng" => 6
                ),
                array("ceng" => array(7, 100))
            ),

            //shopping
            array(
                array("shopping"=>'a' , "city_domain"=>'bj'),
                array("shopping"=>'aaaaa' , "city_domain"=>'bj')
            ),

            //image_count
            array(
                array("image_count"=>111),
                array("image_count"=>array(1,999))
            )
        );
    }
    
    /**
     * @dataProvider providerFormatQueryFilter
     * 
     */
    public function testFormatQueryFilter($queryFilterArr, $wanted){

        $BusinessDistrictNamespace = $this->genStaticMock("BusinessDistrictNamespace", array("getBusinessDistrictIdByUrl"));

        $BusinessDistrictNamespace->expects($this->any())
                                  ->method("getBusinessDistrictIdByUrl")
                                  ->will($this->returnValue("aaaaa"));  

        $obj = new Util_HouseXapianMock();

        $ret = $obj->formatQueryFilter($queryFilterArr);
        $this->assertEquals($ret, $wanted);
    }

    public function providerTransToQueryParams(){

        $builder = new Util_HouseXapianBuild();

        return array(
            
            array(
                $builder, 
                array(
                    "a"=>1,
                    "b"=>"a",
                    "c"=>"aaaaaa"
                ),
                array(
                    "n"=>array(
                        "a"=>array(1),
                        "b"=>array(2),
                        "c"=>array(1)
                    ),
                    "f"=>array(
                        "x"=>array(),
                        "y"=>array()
                    )
                ),
                'a|setBetweenFilter',
                array(1, 1)
            ),
            
            array(
                $builder, 
                array(
                    "a"=>1,
                    "b"=>"a",
                ),
                array(
                    "n"=>array(
                        "x"=>array(),
                        "y"=>array()
                    ),
                    "f"=>array(
                        "a"=>array(2,2),
                        "b"=>array(3)
                    )
                ),
                'a|setInFilter',
                array(1)
            ),

            array(
                $builder, 
                array(
                    "a"=>1,
                    "b"=>"a",
                ),
                array(
                    "n"=>array(
                        "x"=>array(),
                        "y"=>array()
                    ),
                    "f"=>array(
                        "a"=>array(2),
                        "b"=>array(3)
                    )
                ),
                'a|setEqualFilter',
                1
            ),

            array(
                $builder, 
                array(
                    "a"=>array("a","a"),
                    "b"=>"a",
                ),
                array(
                    "n"=>array(
                        "x"=>array(),
                        "y"=>array()
                    ),
                    "f"=>array(
                        "a"=>array(2),
                        "b"=>array(3)
                    )
                ),
                'a|setInFilter',
                array("a","a")
            )
        );
    }
    
    /**
     * @dataProvider providerTransToQueryParams
     */
    public function testTransToQueryParams($builder, $queryFilterArr, $xapianFieldArr, $wantKey, $wantValue){
        $obj = new Util_HouseXapianMock();

        $ret = $obj->transToQueryParams($builder, $queryFilterArr, $xapianFieldArr);

        $this->assertEquals($ret->$wantKey, $wantValue);
    }
}


