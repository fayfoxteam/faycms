<?php
return array(
	//充值军费
	\fay\services\payment\trade\TradePaymentService::EVENT_PAID => array(
		'handler'=>function(\fay\services\payment\trade\TradePaymentItem $tradePaymentItem){
			$trade = $tradePaymentItem->getTrade();
			$refers = $trade->getRefers();
			if(count($refers) != 1){
				//按业务逻辑，充值军费只能是自己给自己重置，如果记录数不等于1条，肯定不是正常的充值
				return false;
			}
			
			if($refers[0]['type'] == \guangong\models\PaymentModel::TYPE_MILITARY){
				//增加用户军费（理论上应该有张重置记录表，懒得弄了）
				\guangong\models\tables\GuangongUserExtraTable::model()->update(array(
					'military'=>new \fay\core\db\Expr('military + ' . $trade['paid_fee']),
				), $trade);
				
				return true;
			}
			
			return false;
		}
	),
	//关公点兵用户注册
	\fay\services\user\UserService::EVENT_CREATED => array(
		'handler'=>function($user_id){
			\guangong\models\tables\GuangongUserExtraTable::model()->insert(array(
				'user_id'=>$user_id,
			));
		}
	)
);