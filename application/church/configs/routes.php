<?php
return array(
	//文章
	'/^(\d+)-(\d+)$/'=>'post/item:cat=$1&id=$2',
	//分类
	'/^cat\/([\w-]+)$/'=>'cat/item:cat=$1',
	//标签
	'/^tag\/(.*)$/'=>'tag/item:tag_title=$1',
);