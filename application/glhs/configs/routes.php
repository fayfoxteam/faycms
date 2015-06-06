<?php
return array(
	'/^about$/'=>'page/item/alias/about',
	'/^contact$/'=>'contact/index',
	'/^teacher$/'=>'teacher/index',
	'/^search$/'=>'search/index',
	'/^search\/(.*)$/'=>'search/index/keywords/$1',
	
	'/^([a-zA-Z0-9]+)$/'=>'post/cat/alias/$1',
	'/^([a-zA-Z0-9]+)-(\d+)$/'=>'post/item/cat/$1/id/$2',
);