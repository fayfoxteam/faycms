<?php
use fay\helpers\HtmlHelper;
?>
<div class="w1000 clearfix col-2">
    <div class="col-2-left">
        <nav class="left-menu">
            <ul>
                <li><a href="<?php echo $this->url('news')?>">新闻中心</a></li>
                <?php foreach($children as $c){?>
                <li><a href="<?php echo $this->url('news/'.$c['alias'])?>"><?php echo HtmlHelper::encode($c['title'])?></a></li>
                <?php }?>
            </ul>
        </nav>
    </div>
    <div class="col-2-right">
        <div class="page-item">
            <header>
                <span class="title"><?php echo HtmlHelper::encode($cat['title'])?></span>
                <span class="dashed"></span>
            </header>
            <div class="content">
                <article class="post-item">
                    <header class="post-header">
                        <h1 class="post-title"><?php echo HtmlHelper::encode($post['title'])?></h1>
                    </header>
                    <div class="post-content">
                        <?php echo $post['content']?>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->assets('faycms/js/fayfox.fixcontent.js')?>"></script>
<script>
$(function(){
    $(".left-menu").fixcontent();
});
</script>