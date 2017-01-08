<?php
namespace fay\services;

use fay\core\Session;

class FlashService{
	/**
	 * 通过flash设置一条即显消息
	 * @param string $message
	 * @param string $status
	 */
	public static function set($message, $status='error'){
		$notification = Session::getInstance()->getFlash('notification');
		if(!is_array($notification)){
			$notification = array();
		}
		$notification[$status][] = $message;
		Session::getInstance()->setFlash('notification', $notification);
	}
	
	/**
	 * 获取flash中的即显消息
	 * @return string
	 */
	public static function get(){
		$notification = Session::getInstance()->getFlash('notification');
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
	public static function clear(){
	 	Session::getInstance()->remove('flash_notification');
	}
}