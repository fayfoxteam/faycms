<?php
namespace jxsj\plugins;

class AdminMenu{
	public static function run(){
		if(method_exists(\F::app(), 'removeMenuTeam')){
			\F::app()->removeMenuTeam('goods');
			\F::app()->removeMenuTeam('voucher');
			\F::app()->removeMenuTeam('notification');
			\F::app()->removeMenuTeam('bill');
			\F::app()->removeMenuTeam('menu');
			\F::app()->removeMenuTeam('template');
		}
	}
}