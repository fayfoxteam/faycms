<?php
namespace youdao\plugins;

use fay\core\FBase;

class AdminMenu extends FBase{
	public function run(){
		if(method_exists(\F::app(), 'addMenuTeam')){
			\F::app()->removeMenuTeam('message');
			\F::app()->removeMenuTeam('goods');
			\F::app()->removeMenuTeam('voucher');
			\F::app()->removeMenuTeam('notification');
			\F::app()->removeMenuTeam('bill');
			
			//删除原来的站点参数，换个新的上去
			\F::app()->removeMenuTeam('site', 0);
			\F::app()->addMenuItem(array(
				'label'=>'站点参数',
				'router'=>'admin/ysite/options',
			), 'site', 0);
		}
	}
}