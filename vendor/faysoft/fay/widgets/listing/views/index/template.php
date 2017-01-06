<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \fay\widgets\friendlinks\controllers\IndexController
 * @var $data array
 */

echo HtmlHelper::encode($widget->config['title']);
foreach($widget->config['data'] as $d){
	echo HtmlHelper::tag('p', array(), HtmlHelper::encode($d));
}
?>