<?php
return array(
	/**
	 * 隐藏真实后台
	 */
	'/^admin$/'=>'404',
	'/^admin\/$/'=>'404',
	
	'/^product\/(\d+)$/'=>'product/item/id/$1',
	'/^page-(\d+)$/'=>'page/item/id/$1',
);