<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<li class="contact-item" id="contact-<?php echo $data['id']?>">
    <h3><?php echo HtmlHelper::encode($data['title'])?></h3>
    <div class="ci-header"><?php
        echo HtmlHelper::tag('span', array(
            'class'=>'ci-name',
            'title'=>'Name',
            'prepend'=>array(
                'tag'=>'i',
                'class'=>'fa fa-user',
                'text'=>'',
            ),
        ), HtmlHelper::encode($data['name']));
        echo HtmlHelper::tag('span', array(
            'class'=>'ci-time',
            'title'=>DateHelper::format($data['publish_time']),
            'prepend'=>array(
                'tag'=>'i',
                'class'=>'fa fa-calendar',
                'text'=>'',
            ),
        ), date('d M Y', $data['publish_time']));
        if($data['country']){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-country',
                'title'=>'Country',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-location-arrow',
                    'text'=>'',
                ),
            ), HtmlHelper::encode($data['country']));
        }
        echo HtmlHelper::tag('span', array(
            'class'=>'ci-time',
            'prepend'=>array(
                'tag'=>'i',
                'class'=>'fa fa-map-marker',
                'text'=>'',
            ),
        ), preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/', '$1.$2.*.*', long2ip($data['show_ip_int'])));
    ?></div>
    <div class="ci-content"><?php echo nl2br(HtmlHelper::encode($data['content']))?></div>
    <?php if($data['reply']){?>
        <div class="ci-reply"><?php if($data['reply']){
            echo HtmlHelper::tag('strong', array(), 'Reply：'), HtmlHelper::tag('span', array(
                'class'=>'ci-reply-container',
            ), nl2br(HtmlHelper::encode($data['reply'])));
        }?></div>
    <?php }?>
</li>