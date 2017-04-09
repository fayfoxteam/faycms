<?php
use fay\helpers\HtmlHelper;
use cms\services\post\PostService;
?>
<li class="disc">
    <a href="<?php echo $this->url('news/'.$data['id']);?>">
        <time class="fr"><?php echo date('Y-m-d', $data['publish_time'])?></time>
        <span><?php echo HtmlHelper::encode($data['title'])?></span>
    </a>
</li>