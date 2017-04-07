<?php
return array(
	/**
	 * 隐藏真实后台
	 */
	'/^admin$/'=>'404',
	'/^admin\/$/'=>'404',
	'/^dowish$/'=>'cms/admin/login/index',
	
	'/^cat-(\d+)$/'=>'post/index/cat_id/$1',
	'/^post-(\d+)$/'=>'post/item/id/$1',
	'/^post\/(\d+)$/'=>'post/item/id/$1',
	'/^page-(\d+)$/'=>'page/item/id/$1',
);