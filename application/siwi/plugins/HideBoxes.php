<?php
namespace siwi\plugins;

class HideBoxes{
	public static function run(){
		//移除分类选择，该系统不需要多分类体系
		\F::app()->removeBox('category');
	}
}