<?php
/**
 * @var $widget cms\widgets\options\controllers\IndexController
 * @var $title string
 * @var $data array
 */

use fay\helpers\HtmlHelper;
?>
<!--推荐域名start-->
<div class="m-amc-recommend">
    <div class="m-amc-recommendpic"><?php
        echo HtmlHelper::encode($widget->config['title'])
    ?></div>
    <div class="m-amc-recommendmain">
        <div class="col-xs-4 m-amc-yuming">pinyin.com</div>
        <div class="col-xs-4 m-amc-price">￥120,000</div>
        <div class="col-xs-4 m-amc-look"><a href="">查看</a></div>
    </div>
</div>
<!--推荐域名over-->