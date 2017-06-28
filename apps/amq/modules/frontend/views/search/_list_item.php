<?php
/**
 * @var $data array
 * @var $keywords string
 */
use cms\helpers\LinkHelper;
use cms\services\file\FileService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

$thumbnail = FileService::getUrl($data['thumbnail'], FileService::PIC_RESIZE, array(
    'dw'=>260,
    'dh'=>160,
    'spare'=>'default',
));
?>
<li>
    <a href="<?php echo LinkHelper::generatePostLink($data)?>">
        <img src="<?php echo $thumbnail?>" />
        <div class="newslist-text">
            <h5 class="newslist-text-title"><?php
                echo str_replace($keywords, '<span class="fc-red">'.$keywords.'</span>', HtmlHelper::encode($data['title']))
            ?></h5>
            <p class="newslist-text-article"><?php
                echo str_replace($keywords, '<span class="fc-red">'.$keywords.'</span>', HtmlHelper::encode($data['abstract']))
            ?></p>
            <div class="newslist-text-tip clearfix">
                <?php if(!empty($data['source'])){?>
                    <div class="newslist-text-from">来源：<?php echo HtmlHelper::encode($data['source'])?></div>
                <?php }?>
                <div class="newslist-text-time"><?php echo DateHelper::niceShort($data['publish_time'])?></div>
            </div>
        </div>
    </a>
</li>