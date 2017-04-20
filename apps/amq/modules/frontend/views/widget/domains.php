<?php
/**
 * @var $widget cms\widgets\options\controllers\IndexController
 * @var $title string
 * @var $data array
 */
?>
<!--域名推荐start-->
<div class="amc-hot">
    <h5 class="newslist-title"><span class="orange-underline"><?php use fay\helpers\HtmlHelper;
        
            echo HtmlHelper::encode($widget->config['title'])?></span><a href="https://am.22.cn/" class="morelink">全部></a></h5>
    
    <ul class="amc-ym-list">
        <?php foreach($data as $d){?>
            <?php $key = explode('|', $d['key'])?>
        <li class="clearfix">
            <div class="amc-ym"><?php echo HtmlHelper::encode($key[0])?></div>
            <div class="amc-price"><?php
                if(empty($key[1])){
                    echo '点击查看';
                }else{
                    echo '￥', $key[1];
                }
            ?></div>
            <a href="<?php echo HtmlHelper::encode($d['value'])?>" class="lookit">查看</a>
        </li>
        <?php }?>
    </ul>
</div>
<!--域名推荐over-->