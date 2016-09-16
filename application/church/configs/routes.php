<?php
return array(
	//文章
	'/^post\/(\d+)$/'=>'post/item/id/$1',
	//分类
	'/^cat\/([\w-]+)$/'=>'cat/item/cat/$1',
	//标签
	'/^tag\/(.*)$/'=>'tag/item/tag/$1',
);