<?php
namespace fay\services\oauth\weixin;

use fay\models\tables\UserConnectsTable;
use fay\services\oauth\UserAbstract;

class QQUser extends UserAbstract{
	/**
	 * @see UserAbstract::getType()
	 * @return int
	 */
	public function getType(){
		return UserConnectsTable::TYPE_QQ;
	}
}