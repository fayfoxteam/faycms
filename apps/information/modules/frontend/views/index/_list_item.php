<?php
use cms\services\CategoryService;

if(CategoryService::service()->isChild($data['cat_id'], $work_cat)){
    echo $this->renderPartial('index/_work_item', array('data'=>$data));
}else{
    echo $this->renderPartial('index/_post_item', array('data'=>$data));
}