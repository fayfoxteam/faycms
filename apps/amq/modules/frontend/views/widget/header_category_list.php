<ul class="amc-nav hidden-xs">
    <li><a href="<?php echo $this->url()?>" <?php if(!F::input()->get('cat') && !F::input()->get('page'))echo 'class="act"'?>>首页</a></li>
    <?php foreach($cats as $cat){?>
    <li><a href="<?php echo $cat['link']?>" <?php if(F::input()->get('cat') == $cat['alias']) echo 'class="act"'?>><?php echo \fay\helpers\HtmlHelper::encode($cat['title'])?></a></li>
    <?php }?>
    <li><a href="<?php echo $this->url('tougao.html')?>" <?php if(F::input()->get('page') == 'tougao')echo 'class="act"'?>>爆料与投稿</a></li>
</ul>