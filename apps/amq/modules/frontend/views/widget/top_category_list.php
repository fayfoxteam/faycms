<ul class="amc-nav hidden-xs">
    <li><a href="<?php echo $this->url()?>" class="act">首页</a></li>
    <?php foreach($cats as $cat){?>
    <li><a href="<?php echo $cat['link']?>"><?php echo \fay\helpers\HtmlHelper::encode($cat['title'])?></a></li>
    <?php }?>
</ul>