<?php
namespace ncp\plugins;

use fay\core\FBase;

class AdminMenu extends FBase{
	public function run(){
		if(method_exists(\F::app(), 'removeMenuTeam')){
			\F::app()->removeMenuTeam('goods');
			\F::app()->removeMenuTeam('voucher');
			\F::app()->removeMenuTeam('notification');
			\F::app()->removeMenuTeam('message');
			\F::app()->removeMenuTeam('bill');
			\F::app()->removeMenuTeam('menu');
			\F::app()->removeMenuTeam('template');
			\F::app()->removeMenuTeam('exam-question');
			\F::app()->removeMenuTeam('exam-paper');
		}
	}
}