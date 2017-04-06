<?php
return array(
	'/^work\/(\d+)$/'=>'post/item:id=$1',
	'/^post\/(\d+)$/'=>'post/item:id=$1',
	'/^cat\/(\d+)$/'=>'index/index:cat=$1',
	'/^about$/'=>'page/item:alias=about',
	'/^post$/'=>'index/index:type=post',
	'/^work$/'=>'index/index:type=work',
);