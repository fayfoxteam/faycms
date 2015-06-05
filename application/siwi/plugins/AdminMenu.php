<?php
namespace siwi\plugins;

use fay\core\FBase;

class AdminMenu extends FBase{
	public static function run(){
		if(method_exists(\F::app(), 'removeMenuTeam')){
			\F::app()->removeMenuTeam('exam-question');
			\F::app()->removeMenuTeam('exam-paper');
		}
	}
}