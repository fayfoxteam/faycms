<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
use cms\services\post\PostService;
use fay\helpers\StringHelper;
?>
<div class="page-title">
    <div class="container">
        <h1>师资力量</h1>
        <div class="breadcrumbs">
            <ol>
                <li><?php echo HtmlHelper::link(OptionService::get('site:sitename'))?></li>
                <li>师资力量</li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="page-content">
        <div class="teacher-description"><?php echo StringHelper::nl2p(HtmlHelper::encode($cat_teacher['description']))?></div>
        <div class="teacher-list">
            <ul class="cf"><?php foreach($teachers as $t){
                echo HtmlHelper::link(HtmlHelper::img($t['thumbnail'], FileService::PIC_RESIZE, array(
                    'dw'=>180,
                    'dh'=>228,
                    'alt'=>HtmlHelper::encode($t['title']),
                    'after'=>array(
                        'tag'=>'span',
                        'text'=>HtmlHelper::encode($t['title']),
                        'class'=>'name',
                    ),
                )), 'javascript:;', array(
                    'encode'=>false,
                    'title'=>HtmlHelper::encode($t['title']),
                    'wrapper'=>array(
                        'tag'=>'li',
                        'append'=>array(
                            'tag'=>'span',
                            'text'=>PostService::service()->getPropValueByAlias('teacher_job', $t['id']),
                            'class'=>'job',
                        ),
                    ),
                ));
            }?></ul>
        </div>
    </div>
</div>