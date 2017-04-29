<?php
/**
 * @var $post
 */
$props = \fay\helpers\ArrayHelper::column($post['props'], null, 'alias');

\F::app()->layout->assign(array(
    'title'=>$post['post']['title'],
    'subtitle'=>isset($props['subtitle']['value']) ? $props['subtitle']['value'] : '',
    'header_bg'=>!empty($post['post']['thumbnail']['id']) ? $post['post']['thumbnail']['url'] : '',
));
?>
<main class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="post-meta">
                <span class="time"><?php echo date('F j, Y', $post['post']['publish_time'])?></span>
                <span class="dot"> · </span>
                <?php foreach($post['tags'] as $tag){?>
                    <a href="<?php echo $this->url('tag/'.urlencode($tag['tag']['title']))?>" class="tag"><?php echo \fay\helpers\HtmlHelper::encode($tag['tag']['title'])?></a>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="post-content">
                <?php echo $post['post']['content']?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 center">
            <div class="separator-container">
                <div class="separator"></div>
            </div>
            <a href="<?php echo $this->url()?>#section-contact" class="btn btn-blue">CONTACT US</a>
        </div>
    </div>
    <div class="row">
        <nav class="cf post-nav">
            <?php if($post['nav']['prev'] || $post['nav']['next']){?>
            <div class="col-md-6 previous">
                <?php if($post['nav']['prev']){?>
                <a href="<?php echo $this->url('post/'.$post['nav']['prev']['id'])?>">
                    <i class="fa fa-angle-left"></i>
                    Previous
                </a>
                <a href="<?php echo $this->url('post/'.$post['nav']['prev']['id'])?>" class="post-title">
                    <?php echo \fay\helpers\HtmlHelper::encode($post['nav']['prev']['title'])?>
                </a>
                <?php }?>
            </div>
            <div class="col-md-6 next">
                <?php if($post['nav']['next']){?>
                <a href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>">
                    Next
                    <i class="fa fa-angle-right"></i>
                </a>
                <a href="<?php echo $this->url('post/'.$post['nav']['next']['id'])?>" class="post-title">
                    <?php echo \fay\helpers\HtmlHelper::encode($post['nav']['next']['title'])?>
                </a>
                <?php }?>
            </div>
            <?php }?>
        </nav>
    </div>
</main>