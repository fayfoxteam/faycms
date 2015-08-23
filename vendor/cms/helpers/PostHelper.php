<?php 
namespace cms\helpers;

use fay\models\tables\Posts;

class PostHelper{
	
	public static function getStatus($status, $delete, $coloring = true){
		if($delete == 1){
			return '回收站';
		}
		switch ($status) {
			case Posts::STATUS_PUBLISHED:
				return '已发布';
				break;
			case Posts::STATUS_DRAFT:
				if($coloring)
					return '<span class="fc-blue">草稿</span>';
				else
					return '草稿';
				break;
			case Posts::STATUS_PENDING:
				if($coloring)
					return '<span class="fc-orange">待审核</span>';
				else
					return '待审核';
				break;
			case Posts::STATUS_REVIEWED:
				if($coloring)
					return '<span class="fc-green">通过审核</span>';
				else
					return '通过审核';
				break;
		}
	}
}