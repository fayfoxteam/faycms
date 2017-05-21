<ul class="amc-nav hidden-xs">
    <li><a href="<?php echo $this->url()?>" <?php if(!F::input()->get('cat'))echo 'class="act"'?>>首页</a></li>
    <?php foreach($cats as $cat){?>
    <li><a href="<?php echo $cat['link']?>" <?php if(F::input()->get('cat') == $cat['alias']) echo 'class="act"'?>><?php echo \fay\helpers\HtmlHelper::encode($cat['title'])?></a></li>
    <?php }?>
</ul>