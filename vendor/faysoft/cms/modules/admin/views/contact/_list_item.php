<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;

/**
 * @var $data array
 * @var $iplocation IpLocation
 */
?>
<li class="contact-item" id="contact-<?php echo $data['id']?>">
    <div class="ci-options"><?php
        echo HtmlHelper::link('<i class="fa fa-pencil"></i>', array('cms/admin/contact/edit', array(
            'id'=>$data['id'],
        )), array(
            'data-id'=>$data['id'],
            'class'=>'btn btn-grey edit-link',
            'encode'=>false,
            'title'=>'编辑',
        ), true);
        if(F::app()->checkPermission('cms/admin/contact/reply')){
            echo HtmlHelper::link('<i class="fa fa-reply-all"></i>', 'javascript:', array(
                'data-id'=>$data['id'],
                'class'=>'btn btn-grey reply-link',
                'encode'=>false,
                'title'=>'回复',
                'data-src'=>'#contact-reply-dialog',
                'data-caption'=>'',
            ));
        }
        echo HtmlHelper::link('<i class="fa fa-trash"></i>', array('cms/admin/contact/remove', array(
            'id'=>$data['id'],
        )), array(
            'data-id'=>$data['id'],
            'class'=>'btn btn-grey remove-link',
            'encode'=>false,
            'title'=>'删除',
        ), true);
    ?></div>
    <h3><?php echo HtmlHelper::encode($data['title'])?></h3>
    <div class="ci-header"><?php
        if(in_array('name', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-name',
                'title'=>'称呼',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-user',
                    'text'=>'',
                ),
            ), $data['name'] ? HtmlHelper::encode($data['name']) : '匿名');
        }
        if(in_array('create_time', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-time',
                'title'=>DateHelper::format($data['create_time']),
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-calendar',
                    'text'=>'',
                ),
            ), (empty($settings['display_time']) || $settings['display_time'] == 'short') ? DateHelper::niceShort($data['create_time']) : DateHelper::format($data['create_time']));
        }
        if(in_array('country', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-country',
                'title'=>'国家',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-location-arrow',
                    'text'=>'',
                ),
            ), $data['country'] ? HtmlHelper::encode($data['country']) : '未填写');
        }
        if(in_array('mobile', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-mobile',
                'title'=>'电话',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-mobile-phone',
                    'text'=>'',
                ),
            ), $data['mobile'] ? HtmlHelper::encode($data['mobile']) : '未填写');
        }
        if(in_array('email', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-email',
                'title'=>'邮箱',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-envelope-o',
                    'text'=>'',
                ),
            ), $data['email'] ? HtmlHelper::encode($data['email']) : '未填写');
        }
        if(in_array('area', $settings['cols'])){
            echo HtmlHelper::tag('span', array(
                'class'=>'ci-area',
                'prepend'=>array(
                    'tag'=>'i',
                    'class'=>'fa fa-map-marker',
                    'text'=>'',
                ),
                'title'=>long2ip($data['ip_int']),
            ), $iplocation->getCountry(long2ip($data['ip_int'])));
        }
        if(in_array('ip', $settings['cols'])){
            echo '(', long2ip($data['ip_int']), ')';
        }
    ?></div>
    <div class="ci-content"><?php echo nl2br(HtmlHelper::encode($data['content']))?></div>
    <div class="ci-reply"><?php if($data['reply']){
        echo HtmlHelper::tag('strong', array(), '管理员回复：'), HtmlHelper::tag('span', array(
            'class'=>'ci-reply-container',
        ), nl2br(HtmlHelper::encode($data['reply'])));
    }else{
        echo '未回复';
    }?></div>
</li>