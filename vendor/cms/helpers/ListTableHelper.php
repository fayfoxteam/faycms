<?php 
namespace cms\helpers;

use fay\helpers\Html;

class ListTableHelper{
	/**
	 * 仅适用于跟list-table th中的排序
	 * @param string $field
	 * @param string $label
	 */
	public static function getSortLink($field, $label){
		$text = "<span class='fl'>{$label}</span><span class='sorting-indicator'></span>";

		$class = \F::app()->input->get('order') == 'desc' ? 'sortable desc' : 'sortable asc';
		if(\F::app()->input->get('orderby') == $field){
			$class .= ' sorted';
		}
		return Html::link($text, array(\F::app()->uri->router, array(
			'orderby'=>$field,
			'order'=>\F::app()->input->get('order') == 'desc' ? 'asc' : 'desc',
			'page'=>1,
		)+\F::app()->input->get()), array(
			'class'=>$class,
			'encode'=>false,
			'title'=>\F::app()->input->get('order') == 'desc' ? '点击升序' : '点击降序',
		));
	}
}