<?php
namespace ncp\plugins;

use fay\core\FBase;

class HideBoxes extends FBase{
	public function run(){
		\F::app()->removeBox('alias');
		\F::app()->removeBox('tags');
		\F::app()->removeBox('keywords');
		\F::app()->removeBox('gather');
		\F::app()->removeBox('category');
		\F::app()->removeBox('main-category');
		\F::app()->removeBox('files');
		\F::app()->removeBox('likes');
	}
}