<?php
return array(
	'/^blog$/'=>'blog/index',
	'/^blog\/(\d+)$/'=>'blog/item/id/$1',
	'/^blog\/(\d+-\d+-\d+-\d+-\d+-\d+)$/'=>'blog/index/params/$1',
	
	'/^material$/'=>'material/index',
	'/^material\/(\d+)$/'=>'material/item/id/$1',
	'/^material\/(\d+-\d+-\d+-\d+-\d+-\d+)$/'=>'material/index/params/$1',
	
	'/^u\/(\d+)$/'=>'u/index/id/$1',
	'/^login-mini$/'=>'login/mini',
	'/^register-mini$/'=>'register/mini',
);