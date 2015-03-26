<?php 
namespace cms\helpers;

use fay\models\tables\Posts;

class PostHelper{
	
	public static function getStatus($status, $delete){
		if($delete == 1){
			return '回收站';
		}
		switch ($status) {
			case Posts::STATUS_PUBLISH:
				return '已发布';
				break;
			case Posts::STATUS_DRAFT:
				return '<span class="color-blue">草稿</span>';
				break;
			case Posts::STATUS_PENDING:
				return '<span class="fc-orange">待审核</span>';
				break;
		}
	}
}