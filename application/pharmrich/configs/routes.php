<?php
return array(
	/**
	 * 隐藏真实后台
	 */
	'/^admin$/'=>'404',
	'/^admin\/$/'=>'404',
	
	'/^product\/(\d+)$/'=>'product/item/id/$1',
	'/^product\/([\w-]+)$/'=>'product/cat/alias/$1',
	
	'/^cook-recipe\/(\d+)$/'=>'cook-recipe/item/id/$1',
	'/^cook-recipe\/([\w-]+)$/'=>'cook-recipe/cat/alias/$1',
	
	'/^news\/(\d+)$/'=>'news/item/id/$1',
	'/^news\/([\w-]+)$/'=>'news/cat/alias/$1',

	'/^about$/'=>'page/item/alias/about',
	'/^shipment$/'=>'page/item/alias/shipment',
);