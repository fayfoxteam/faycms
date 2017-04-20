<?php
/**
 * @var $files array
 */
?>
<!--轮播start-->
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators amc-circle">
        <?php foreach($files as $key => $file){?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $key?>" <?php if(!$key) echo 'class="active"'?>></li>
        <?php }?>
    </ol>
    
    <div class="carousel-inner" role="listbox">
        <?php foreach($files as $key => $file){?>
        <div class="item <?php if(!$key)echo 'active'?>">
            <a href="<?php echo $file['link']?>"><img src="<?php echo $file['src']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($file['title'])?>"></a>
            <div class="carousel-caption amc-banner-text"><?php echo \fay\helpers\HtmlHelper::encode($file['title'])?></div>
        </div>
        <?php }?>
    </div>
</div>
<!--轮播over-->