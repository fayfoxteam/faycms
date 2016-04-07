<?php 
namespace apidoc\helpers;

use apidoc\models\tables\Apis;

class ApiHelper{
	/**
	 * 获取接口状态
	 * @param int $status 接口状态码
	 * @param bool $coloring 是否着色（带上html标签）
	 */
	public static function getStatus($status, $coloring = true){
		switch ($status) {
			case Apis::STATUS_DEVELOPING:
				if($coloring)
					return '<span class="fc-orange">开发中</span>';
				else
					return '开发中';
				break;
				break;
			case Apis::STATUS_BETA:
				if($coloring)
					return '<span class="fc-green">测试中</span>';
				else
					return '测试中';
				break;
			case Apis::STATUS_STABLE:
				return '已上线';
				break;
			case Apis::STATUS_DEPRECATED:
				if($coloring)
					return '<span class="fc-red">已弃用</span>';
				else
					return '已弃用';
				break;
			default:
				if($coloring)
					return '<span class="fc-yellow">未知的状态</span>';
				else
					return '未知的状态';
				break;
		}
	}
}