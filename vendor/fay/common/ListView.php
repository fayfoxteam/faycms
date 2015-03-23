<?php 
namespace fay\common;

use fay\core\FBase;
use fay\core\Db;
use fay\core\Sql;
use fay\core\Exception;

class ListView extends FBase{
	public $currentPage = 1;
	public $pageSize = 10;
	public $itemView = '_list_item';
	public $sql;
	public $countSql;
	public $id;
	public $emptyText = '无相关记录！';
	public $offset;
	public $startRecord;
	public $endRecord;
	public $totalRecords;
	public $totalPages;
	public $reload = 'index';//加载地址，对于重写过的url，需要设置此项
	public $adjacents = 2;//前后显示页数
	public $params = array();
	public $viewPartial = 'common/pager';
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
		if(isset($this->id))
			$this->currentPage = \F::app()->input->get($this->id.'_page', 'intval', 1);
		else
			$this->currentPage = \F::app()->input->get('page', 'intval', 1);
		
		$this->totalRecords = $this->count();
		$this->totalPages = ceil($this->totalRecords / $this->pageSize);
		$this->currentPage > $this->totalPages ? $this->currentPage = $this->totalPages : '';
		$this->currentPage < 1 ? $this->currentPage = 1 : '';
		$this->offset = ($this->currentPage - 1) * $this->pageSize;
		$this->startRecord = $this->totalRecords ? $this->offset + 1 : 0;
		$this->offset + $this->pageSize > $this->totalRecords ? $this->endRecord = $this->totalRecords : $this->endRecord = $this->offset + $this->pageSize;
	}
	
	public function showData($view_data = array()){
		if($this->totalRecords === null){
			$this->init();
		}
		
		$sql = $this->sql." LIMIT {$this->offset}, {$this->pageSize}";
		$results = $this->db->fetchAll($sql, $this->params);
		if(isset($results[0])){
			$i = 0;
			foreach ($results as $data){
				$i++;
				$view_data['index'] = $i;
				$view_data['data'] = $data;
				\F::app()->view->renderPartial($this->itemView, $view_data);
			}
		}else{
			echo $this->emptyText;
		}
	}
	
	public function getData(){
		if($this->totalRecords === null){
			$this->init();
		}
		
		$sql = $this->sql." LIMIT {$this->offset}, {$this->pageSize}";
		return $this->db->fetchAll($sql, $this->params);
	}
	
	public function showPager($view_data = array()){
		if($this->totalRecords === null){
			$this->init();
		}
		$view_data['listview'] = $this;
		\F::app()->view->renderPartial($this->viewPartial, $view_data);
	}
	
	public function getPager(){
		if($this->totalRecords === null){
			$this->init();
		}
		return array(
			'currentPage'=>$this->currentPage,
			'pageSize'=>$this->pageSize,
			'emptyText'=>$this->emptyText,
			'offset'=>$this->offset,
			'startRecord'=>$this->startRecord,
			'endRecord'=>$this->endRecord,
			'totalRecords'=>$this->totalRecords,
			'totalPages'=>$this->totalPages,
			'adjacents'=>$this->adjacents,
		);
	}
	
	private function count(){
		if(isset($this->countSql)){
			$sql = $this->countSql;
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
		$this->countSql = $sql->getCountSql();
		$this->params = $sql->getParams();
	}
	
}