<?php
/**
 * @var $files array
 */
?>
<div class="tougao-wx">
    <p>如有重大爆料，欢迎骚扰小编的微信</p>
    <?php foreach($files as $file){?>
        <div class="xbtp">
            <img src="<?php echo $file['src']?>" alt="<?php echo \fay\helpers\HtmlHelper::encode($file['title'])?>">
            <div class="xbwxh"><?php echo \fay\helpers\HtmlHelper::encode($file['title'])?></div>
        </div>
    <?php }?>
</div>
