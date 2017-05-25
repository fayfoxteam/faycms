<?php
/**
 * @var $files array
 */
?>
<!--广告start-->
<div id="carousel-example-generic2" class="carousel slide amc-ad" data-ride="carousel">
    <ol class="carousel-indicators amc-circle2">
        <?php foreach($files as $key => $file){?>
            <li data-target="#carousel-example-generic2" data-slide-to="<?php echo $key?>" <?php if(!$key) echo 'class="active"'?>></li>
        <?php }?>
    </ol>
    
    <div class="carousel-inner" role="listbox">
        <?php foreach($files as $key => $file){?>
            <div class="item <?php if(!$key)echo 'active'?>">
                <a href="<?php echo $file['link']?>" target="_blank"><img src="<?php echo $file['src']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($file['title'])?>"></a>
            </div>
        <?php }?>
    </div>
</div>
<!--广告over-->
