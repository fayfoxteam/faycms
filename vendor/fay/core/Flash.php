<?php
namespace fay\core;

use fay\core\FBase;

class Flash extends FBase{
	public function __construct(){
		$this->session = Session::getInstance();
	}
	
	/**
	 * 通过flash设置一条即显消息
	 * @param string $message
	 * @param string $status
	 */
	public function set($message, $status='error'){
		$notification = $this->session->getFlash('notification');
		if(!is_array($notification)){
			$notification = array();
		}
		$notification[$status][] = $message;
		$this->session->setFlash('notification', $notification);
	}
	
	/**
	 * 获取flash中的即显消息
	 * @return string
	 */
	public function get(){
		$notification = $this->session->getFlash('notification');
		$html = '';
		if(is_array($notification)){
			$html = \F::app()->view->renderPartial('common/notification', array(
				'notification'=>$notification,
			), -1, true);
		}
		return $html;
	}
	
	/**
	 * 清楚flash中的即显消息
	 */
	public function clear(){
	 	$this->session->remove('flash_notification');
	}
}