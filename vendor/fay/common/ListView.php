<?php 
namespace fay\common;

use fay\core\Db;
use fay\core\Sql;
use fay\core\Exception;

class ListView{
	public $current_page = 1;
	public $page_size = 10;
	public $item_view = '_list_item';
	public $sql;
	public $count_sql;
	public $page_key = 'page';//当前页参数
	public $empty_text = '无相关记录！';
	public $offset;
	public $start_record;
	public $end_record;
	public $total_records;
	public $total_pages;
	public $reload = null;//加载地址，对于重写过的url，需要设置此项
	public $adjacents = 2;//前后显示页数
	public $params = array();
	public $pager_view = 'common/pager';
	/**
	 * @var Db
	 */
	private $db;
	
	public function __construct($sql = null, $config = array()){
		foreach($config as $k => $c){
			if(isset($this->{$k})){
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
		$this->current_page = \F::app()->input->get($this->page_key, 'intval', 1);
		
		$this->total_records = $this->count();
		$this->total_pages = ceil($this->total_records / $this->page_size);
		$this->current_page > $this->total_pages ? $this->current_page = $this->total_pages : '';
		$this->current_page < 1 ? $this->current_page = 1 : '';
		$this->offset = ($this->current_page - 1) * $this->page_size;
		$this->start_record = $this->total_records ? $this->offset + 1 : 0;
		$this->offset + $this->page_size > $this->total_records ? $this->end_record = $this->total_records : $this->end_record = $this->offset + $this->page_size;
	}
	
	public function showData($view_data = array()){
		if($this->total_records === null){
			$this->init();
		}
		
		$sql = $this->sql." LIMIT {$this->offset}, {$this->page_size}";
		$results = $this->db->fetchAll($sql, $this->params);
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
		
		$sql = $this->sql." LIMIT {$this->offset}, {$this->page_size}";
		return $this->db->fetchAll($sql, $this->params);
	}
	
	public function showPager($view_data = array()){
		if($this->total_records === null){
			$this->init();
		}
		
		if($this->reload === null){
			$gets = \F::app()->input->get();
			unset($gets[$this->page_key]);
			$this->reload = \F::app()->view->url(\F::app()->uri->router, $gets);
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
		if(isset($this->count_sql)){
			$sql = $this->count_sql;
		}else{
			$sql = preg_replace('/^SELECT[\s\S]*FROM/i', 'select COUNT(*) FROM', $this->sql);
			$sql = preg_replace('/ORDER BY[\s\S]*/i', '', $sql);
			$sql = preg_replace('/GROUP BY[\s\S]*/i', '', $sql);
		}
		$result = $this->db->fetchRow($sql, $this->params);
		return array_shift($result);
	}
	
	public function setSql($sql){
		if(!$sql instanceof Sql){
			throw new Exception('ListView::setSql方法传入的参数必须是Sql类实例');
		}
		$this->sql = $sql->getSql();
		$this->count_sql = $sql->getCountSql();
		$this->params = $sql->getParams();
	}
	
}