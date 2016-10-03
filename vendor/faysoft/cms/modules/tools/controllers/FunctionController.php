<?php
namespace cms\modules\tools\controllers;

use cms\library\ToolsController;
use fay\core\Loader;
use fay\core\Response;

class FunctionController extends ToolsController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'function';
	}
	
	public function unserialize(){
		$this->layout->subtitle = 'unserialize';
		
		$this->view->result = unserialize($this->input->post('key'));
		$this->view->render();
	}
	
	public function evalAction(){
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
		
		$this->layout->subtitle = 'eval';
		$this->view->key = $this->input->post('key');
		$this->view->render();
	}
	
	public function json(){
		$this->layout->subtitle = 'JSON';
		
		$this->view->render();
	}
	
	public function url(){
		$this->layout->subtitle = 'urldecode';
		
		$this->view->result = urldecode($this->input->post('key'));
		$this->view->render();
	}
	
	public function date(){
		$this->layout->subtitle = 'date';
		
		$this->view->render();
	}
	
	public function string(){
		$this->layout->subtitle = 'string';
		
		$this->view->render();
	}
	
	public function ip(){
		$this->layout->subtitle = 'ip';
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function doEval(){
		$code = $this->input->post('code', '', '');
		echo eval('?>'. $code);
	}
	
	/**
	 * 对用户输入的php代码进行json_decode后返回
	 * 返回的是php代码
	 * @parameter string $code JSON字符串
	 */
	public function jsonDecode(){
		$code = $this->input->request('code');
		if($code){
			$arr = json_decode($this->input->request('code'), true);
			if($arr === null && strtolower($code) != 'null'){
				Response::json('', 0, 'JSON格式异常');
			}else{
				Response::json(array(
					'code'=>var_export($arr, true)
				));
			}
		}else{
			Response::json(array(
				'code'=>null,
			));
		}
	}
	
	/**
	 * 对用户输入的php代码进行json_encode后返回
	 * 这个方法是有风险的，因为用了eval函数
	 * @parameter string $code php array代码
	 */
	public function jsonEncode(){
		//登陆检查，仅超级管理员可访问本模块
		$this->isLogin();
		
		$array = $this->input->request('code');
		if(version_compare(phpversion(), '5.4.0', '>=')){
			Response::json(array(
				'code'=>json_encode(eval('return '.$array.';'), JSON_UNESCAPED_UNICODE),
			));
		}else{
			//低版本php不做复杂处理，直接返回unicode后的中文
			Response::json(array(
				'code'=>json_encode(eval('return '.$array.';'))
			));
		}
	}
	
	/**
	 * 将提交过来的时间戳格式化为日期返回
	 * @parameter string $timestamps 时间戳，一行一个
	 */
	public function datetime(){
		$timestamps = explode("\n", $this->input->request('timestamps'));
		$dates = array();
		foreach($timestamps as $t){
			$t = intval(trim($t));
			if($t){
				$dates[] = date('Y-m-d H:i:s', $t);
			}else{
				$dates[] = '';
			}
		}
		
		Response::json(array(
			'dates'=>implode("\r\n", $dates),
		));
	}
	
	public function strtotime(){
		$dates = explode("\n", $this->input->request('dates'));
		$timestamps = array();
		foreach($dates as $d){
			$d = trim($d);
			if($d){
				$timestamps[] = strtotime($d);
			}else{
				$timestamps[] = '';
			}
		}
		
		Response::json(array(
			'timestamps'=>implode("\r\n", $timestamps),
		));
	}
}