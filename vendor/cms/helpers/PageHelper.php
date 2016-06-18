<?php 
namespace cms\helpers;

use fay\models\tables\Pages;

class PageHelper{
	/**
	 * 获取页面状态
	 * @param int $status 页面状态码
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
			case Pages::STATUS_PUBLISHED:
				return '已发布';
				break;
			case Pages::STATUS_DRAFT:
				if($coloring)
					return '<span class="fc-blue">草稿</span>';
				else
					return '草稿';
				break;
		}
	}
}