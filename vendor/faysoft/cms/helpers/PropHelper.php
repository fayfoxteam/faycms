<?php 
namespace cms\helpers;

use fay\models\tables\PropsTable;

class PropHelper{
	/**
	 * 获取文章状态
	 * @param int $status 文章状态码
	 * @param int $delete 是否删除
	 * @param bool $coloring 是否着色（带上html标签）
	 */
	public static function getElement($element){
		switch($element){
			case PropsTable::ELEMENT_TEXT:
				echo '文本框';
				break;
			case PropsTable::ELEMENT_RADIO:
				echo '单选框';
				break;
			case PropsTable::ELEMENT_SELECT:
				echo '下拉框';
				break;
			case PropsTable::ELEMENT_CHECKBOX:
				echo '多选框';
				break;
			case PropsTable::ELEMENT_TEXTAREA:
				echo '文本域';
				break;
			case PropsTable::ELEMENT_NUMBER:
				echo '数字文本框';
				break;
			case PropsTable::ELEMENT_IMAGE:
				echo '图片';
				break;
		}
	}
}