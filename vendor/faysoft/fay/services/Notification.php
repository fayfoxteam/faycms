<?php
namespace fay\services;

use fay\core\Service;
use fay\models\tables\Users;
use fay\models\tables\Notifications;
use fay\models\tables\UsersNotifications;

class Notification extends Service{
	/**
	 * @param string $class_name
	 * @return Notification
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 发送一条notification
	 * @param int|array $to
	 * @param string $title
	 * @param string $content
	 * @param int $from
	 * @param int $cat_id
	 * @param int|null $publish_time
	 * @return int 消息id
	 */
	public function send($to, $title, $content, $from = Users::ITEM_SYSTEM_NOTIFICATION, $cat_id = 0, $publish_time = null){
		if(!is_array($to)){
			$to = array($to);
		}
		
		$notification_id = Notifications::model()->insert(array(
			'title'=>$title,
			'content'=>$content,
			'create_time'=>\F::app()->current_time,
			'sender'=>$from,
			'cat_id'=>$cat_id,
			'publish_time'=>$publish_time ? $publish_time : \F::app()->current_time,
		));
		foreach($to as $t){
			UsersNotifications::model()->insert(array(
				'user_id'=>$t,
				'notification_id'=>$notification_id,
			));
		}
		return $notification_id;
	}
}