<?php 
namespace fay\common;

use fay\core\Db;
use fay\core\ErrorException;
use fay\core\Sql;
use fay\core\Exception;
use fay\helpers\UrlHelper;

class ListView{
    public $current_page = null;
    public $page_size = 10;
    public $page_key = 'page';//当前页参数
    public $offset;
    public $start_record;
    public $end_record;
    public $total_records;
    public $total_pages;
    public $reload = null;//加载地址，对于重写过的url，需要设置此项
    public $adjacents = 2;//前后显示页数

    /**
     * @var Sql|string
     */
    private $count_sql;
    
    /**
     * @var string 分页条view
     */
    private $pager_view = 'common/pager';

    /**
     * @var string 单行记录渲染view
     */
    private $item_view = '_list_item';
    
    /**
     * @var string 无记录时展示文本
     */
    private $empty_text = '无相关记录！';
    
    /**
     * @var Sql
     */
    private $sql;
    
    /**
     * @var Db
     */
    private $db;
    
    /**
     * ListView constructor.
     * @param Sql $sql
     * @param array $config
     */
    public function __construct($sql, $config = array()){
        foreach($config as $k => $c){
            if($c && property_exists($this, $k)){
                $this->{$k} = $c;
            }
        }
        if($sql !== null){
            $this->setSql($sql);
            $this->db = $sql->db;
        }else{
            $this->db = Db::getInstance();
        }
    }
    
    public function init(){
        if($this->current_page === null){
            $this->current_page = \F::app()->input->get($this->page_key, 'intval', 1);
        }
        
        if($this->total_records === null){
            //有时候也可以在初始化的时候直接指定total_records值，例如粉丝数、关注数这些会有地方记录着，比COUNT()要快
            $this->total_records = $this->count();
        }
        $this->total_pages = intval(ceil($this->total_records / $this->page_size));
        if($this->current_page > $this->total_pages){
            $this->current_page = $this->total_pages;
        }
        if($this->current_page < 1){
            $this->current_page = 1;
        }
        $this->offset = ($this->current_page - 1) * $this->page_size;
        $this->start_record = $this->total_records ? $this->offset + 1 : 0;
        $this->offset + $this->page_size > $this->total_records ? $this->end_record = $this->total_records : $this->end_record = $this->offset + $this->page_size;
    }
    
    public function showData($view_data = array()){
        if($this->total_records === null){
            $this->init();
        }
        
        $results = $this->sql->limit($this->page_size, $this->offset)->fetchAll();
        if($results){
            $i = 0;
            foreach ($results as $data){
                $i++;
                $view_data['index'] = $i;
                $view_data['data'] = $data;
                \F::app()->view->renderPartial($this->item_view, $view_data);
            }
        }else{
            echo $this->empty_text;
        }
    }
    
    public function getData(){
        if($this->total_records === null){
            $this->init();
        }
        
        return $this->sql->limit($this->page_size, $this->offset)->fetchAll();
    }
    
    public function showPager($view_data = array()){
        if($this->total_records === null){
            $this->init();
        }
        
        if($this->reload === null){
            $folder = dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            //所有斜杠都以正斜杠为准
            $folder = str_replace('\\', '/', $folder);
            if(substr($folder, -7) == '/public'){
                $folder = substr($folder, 0, -7);
            }
            if($folder && substr($folder, 0, 1) != '/'){
                //由于配置关系，有的DOCUMENT_ROOT最后有斜杠，有的没有
                $folder = '/'.$folder;
            }
            if($folder == '/'){
                //仅剩一根斜杠的时候（把根目录设到public目录下的情况），设为空
                $folder = '';
            }
            $request = substr($_SERVER['REQUEST_URI'], strlen($folder) + 1);
            //去掉问号后面的部分
            $pos = strpos($request, '?');
            if($pos !== false){
                $request = substr($request, 0, $pos);
            }
            
            $gets = $_GET;
            unset($gets[$this->page_key]);
            if($gets){
                $this->reload = UrlHelper::createUrl($request) . '?' . http_build_query($gets);
            }else{
                $this->reload = UrlHelper::createUrl($request);
            }
        }
        $view_data['listview'] = $this;
        \F::app()->view->renderPartial($this->pager_view, $view_data);
    }
    
    public function getPager(){
        if($this->total_records === null){
            $this->init();
        }
        return array(
            'current_page'=>$this->current_page,
            'page_size'=>$this->page_size,
            'empty_text'=>$this->empty_text,
            'offset'=>$this->offset,
            'start_record'=>$this->start_record,
            'end_record'=>$this->end_record,
            'total_records'=>$this->total_records,
            'total_pages'=>$this->total_pages,
            'adjacents'=>$this->adjacents,
            'page_key'=>$this->page_key,
        );
    }
    
    private function count(){
        if(!$this->count_sql){
            return $this->sql->count();
        }else if($this->count_sql instanceof Sql){
            return $this->count_sql->count();
        }else if(is_string($this->count_sql)){
            $result = $this->db->fetchRow($this->count_sql);
            return intval(array_shift($result));
        }else{
            throw new ErrorException('无法识别的count_sql');
        }
    }
    
    public function setSql($sql){
        if(!$sql instanceof Sql){
            throw new Exception('ListView::setSql方法传入的参数必须是Sql类实例');
        }
        $this->sql = $sql;
    }
    
}