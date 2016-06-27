<?php
use fay\services\Category;

if(Category::service()->isChild($data['cat_id'], $work_cat)){
	$this->renderPartial('index/_work_item', array('data'=>$data));
}else{
	$this->renderPartial('index/_post_item', array('data'=>$data));
}