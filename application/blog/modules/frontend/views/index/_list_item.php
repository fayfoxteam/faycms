<?php
use fay\models\Category;

if(Category::model()->isChild($data['cat_id'], $work_cat)){
	$this->renderPartial('index/_work_item', array('data'=>$data));
}else{
	$this->renderPartial('index/_post_item', array('data'=>$data));
}