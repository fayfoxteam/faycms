<?php
use fay\helpers\Html;

/**
 * @var $widget \fay\widgets\friendlinks\controllers\IndexController
 * @var $data array
 */

echo Html::encode($widget->config['title']);
foreach($widget->config['data'] as $d){
	echo Html::tag('p', array(), Html::encode($d));
}
?>