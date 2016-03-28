<?php 
namespace cms\helpers;

use fay\models\tables\Feeds;

class FeedHelper{
	/**
	 * 获取文章状态
	 * @param int $status 文章状态码
	 * @param int $delete 是否删除
	 * @param bool $coloring 是否着色（带上html标签）
	 */
	public static function getStatus($status, $delete, $coloring = true){
		if($delete == 1){
			if($coloring)
				return '<span class="fc-red">回收站</span>';
			else
				return '回收站';
		}
		switch ($status) {
			case Feeds::STATUS_DRAFT:
				if($coloring)
					return '<span class="fc-blue">草稿</span>';
				else
					return '草稿';
				break;
			case Feeds::STATUS_PENDING:
				if($coloring)
					return '<span class="fc-orange">待审核</span>';
				else
					return '待审核';
				break;
			case Feeds::STATUS_REVIEWED:
				if($coloring)
					return '<span class="fc-green">通过审核</span>';
				else
					return '通过审核';
				break;
		}
	}
}