<?php   
/******************************
  * @Author      : wangjun5@ganji.com
  * @Date        : 2014-10-16
  * @Filename    : HouseReportPage.class.php
  * @Description : housing app
  *****************************/	 
class Util_HouseReport_Page{
    /*
     * Exp.
        $PAGE = new HouseReportPage(array('total_rows'=>300,'list_rows'=>15,'now_page'=>7,'method'=>'ajax','func_name'=>'alert','parameter'=>"'foo'"));
        $page_show = $PAGE->show();
     */
    public     $first_row;        //起始行数
    public     $list_rows;        //列表每页显示行数    
    protected  $total_pages;      //总页数
    protected  $total_rows;       //总记录数     
    protected  $now_page;         //当前页数     
    protected  $method;			  //'ajax'，get方式（默认方式）
    protected  $ajax_func_name;   //ajax方法名
	protected  $parameter;        //ajax自定义参数
	protected  $page_name ;       //分页参数的名称，默认page  
    public     $plus = 5;         //分页偏移量    
    protected  $url;			  //URL
    protected  $is_total = false; //是否显示总页数
    protected  $total_str = "共%条记录"; //%为要替换的总页数

    /**
     * 构造函数
     * @codeCoverageIgnore
     *
     * @param unknown_type $data
     */
    public function __construct($data = array())
    {
        $this->total_rows        = $data['total_rows'];
        $this->parameter         = !empty($data['parameter']) ? $data['parameter'] : '';
        $this->list_rows         = !empty($data['list_rows']) && $data['list_rows'] <= 100 ? $data['list_rows'] : 15;
        $this->total_pages       = ceil($this->total_rows / $this->list_rows);
        $this->page_name         = !empty($data['page_name']) ? $data['page_name'] : 'page';
        $this->ajax_func_name    = !empty($data['func_name']) ? $data['func_name'] : '';         
        $this->method            = !empty($data['method']) ? $data['method'] : '';
        $this->is_total          = ($data['is_total'] === true)?true:false;
        $this->total_str         = !empty($data['total_str'])?$data['total_str']:$this->total_str;
        /* 当前页面 */
        if(!empty($data['now_page']))
        {
            $this->now_page = intval($data['now_page']);
        }else{
            $this->now_page   = !empty($_GET[$this->page_name]) ? intval($_GET[$this->page_name]):1;
        }
        $this->now_page   = $this->now_page <= 0 ? 1 : $this->now_page;
     
         
        if(!empty($this->total_pages) && $this->now_page > $this->total_pages)
        {
            $this->now_page = $this->total_pages;
        }
        $this->first_row = $this->list_rows * ($this->now_page - 1);
    }  
     
    /**
     * @codeCoverageIgnore
     *
     * 得到当前连接
     * @param $page
     * @param $text
     * @return string
     */
    protected function _get_link($page,$text,$style='')
    {

		switch ($this->method) {
            case 'ajax':
                $parameter = '';
                if($this->parameter)
                {
                    $parameter = ','.$this->parameter;
                }
                return '<li><a onclick="' . $this->ajax_func_name . '(\'' . $page . '\''.$parameter.')" href="javascript:void(0)" '.$style.'><span>' . $text . '</span></a></li>' . "\n";
            break; 
            default:
                return '<li><a href="' . $this->_get_url($page) . '" '.$style.'><span>' . $text . '</span></a></li>' . "\n";
            break;
        }
    }
     
     
    /**
     * @codeCoverageIgnore
     *
     * 设置当前页面链接
     */
    protected function _set_url()
    {
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$this->page_name]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        if(!empty($params))
        {
            $url .= '&';
        }
        $this->url = $url;
    }
     
    /**
     * @codeCoverageIgnore
     *
     * 得到$page的url
     * @param $page 页面
     * @return string
     */
    protected function _get_url($page)
    {
        if($this->url === NULL)
        {
            $this->_set_url();  
        }
    //  $lable = strpos('&', $this->url) === FALSE ? '' : '&';
        return $this->url . $this->page_name . '=' . $page;
    }

    protected function _get_total_str(){
        $resturn = '';
        if ($this->is_total === true) {
            if (strpos($this->total_str,'%')!==false) {
                $return .= str_replace('%',$this->total_rows,$this->total_str);
            }
        }
        return $return;
    }    
     
    /**
     * 得到第一页
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function first_page($name = '首页')
    {
        if($this->now_page > 5)
        {
            return $this->_get_link('1', $name,'class="prev"');
        }
          
        return '';
    }
     
    /**
     * 最后一页
     * @codeCoverageIgnore
     * @param $name
     * @return string
     */
    public function last_page($name = '末页')
    {
        if($this->now_page < $this->total_pages - 5)
        {
            return $this->_get_link($this->total_pages, $name,'class="next"');
        }  
        return '';
    } 
     
    /**
     * 上一页
     * @codeCoverageIgnore
     * @return string
     */
    public function up_page($name = '上一页')
    {
        if($this->now_page != 1)
        {
            return $this->_get_link($this->now_page - 1, $name,'class="prev"');
        }
        return '';
    }
     
    /**
     * 下一页
     * @codeCoverageIgnore
     * @return string
     */
    public function down_page($name = '下一页')
    {
        if($this->now_page < $this->total_pages)
        {
            return $this->_get_link($this->now_page + 1, $name,'class="next"');
        }
        return '';
    }
 
    /**
     * @codeCoverageIgnore
     * 分页样式输出
     * @param $param
     * @return string
     */
     
    public function show()
    {
        if($this->total_rows < 1 || $this->total_pages <2)
            
        {
            $return = $this->_get_total_str();
            return $return;
        }
		$plus = $this->plus;
        if( $plus + $this->now_page > $this->total_pages)
        {
            $begin = $this->total_pages - $plus * 2;
        }else{
            $begin = $this->now_page - $plus;
        }
         
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return .= $this->first_page();
        $return .= $this->up_page();
        for ($i = $begin; $i <= $begin + $plus * 2;$i++)
        {
            if($i>$this->total_pages)
            {
                break;
            }
            if($i == $this->now_page)
            {
                $return .= '<li><a class="c linkOn"><span>'.$i.'</span></a></li>'."\n";
            }
            else
            {
                $return .= $this->_get_link($i, $i,'') . "\n";
            }
        }
        $return .= $this->down_page();
        $return .= $this->last_page();
        $return .= $this->_get_total_str();
        return $return;
    }
     
}
