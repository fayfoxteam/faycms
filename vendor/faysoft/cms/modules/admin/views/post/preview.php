<?php
use cms\models\tables\PostsTable;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $post array
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/post-preview.css')?>" />

    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.js')?>"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
    <![endif]-->
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.assets_url = '<?php echo \F::config()->get('assets_url')?>';
    </script>
    <title><?php echo HtmlHelper::encode($post['post']['title'])?> | <?php echo \cms\services\OptionService::get('site:sitename')?>后台</title>
</head>
<body>
<div class="post-preview p30">
    <h2 class="post-title"><?php echo HtmlHelper::encode($post['post']['title'])?></h2>
    <div class="post-type">
        <span><?php echo HtmlHelper::encode($post['category']['title'])?></span>
        <?php
            if($post['post']['delete_time']){
                echo '<span class="bg-reg">回收站</span>';
            }else if($post['post']['status'] == PostsTable::STATUS_DRAFT){
                echo '<span class="bg-yellow">草稿</span>';
            }else if($post['post']['status'] == PostsTable::STATUS_PENDING){
                echo '<span class="bg-yellow">待审核</span>';
            }else if($post['post']['status'] == PostsTable::STATUS_REVIEWED){
                echo '<span class="bg-yellow">待复审</span>';
            }else if($post['post']['status'] == PostsTable::STATUS_PUBLISHED){
                echo '<span class="bg-green">已发布</span>';
            }
        ?>
    </div>
    <div class="post-info">
        <?php echo HtmlHelper::img($post['user']['user']['avatar']['thumbnail'], 1, array(
            'class'=>'avatar',
        ))?>
        <span class="user"><?php echo HtmlHelper::encode($post['user']['user']['nickname'] ? $post['user']['user']['nickname'] : $post['user']['user']['username'])?></span>
        <time class="time" title=""><?php echo DateHelper::format($post['post']['publish_time'])?></time>
    </div>
    <div class="post-body">
        <div class="post-thumbnail"><?php
            if($post['post']['thumbnail']['id']){
                echo HtmlHelper::link(
                    HtmlHelper::img($post['post']['thumbnail']['thumbnail']),
                    $post['post']['thumbnail']['url'],
                    array(
                        'encode'=>false,
                        'title'=>'缩略图',
                        'data-fancybox'=>null,
                    )
                );
            }
        ?></div>
        <?php if($post['post']['abstract']){?>
            <div class="post-abstract"><?php echo nl2br(HtmlHelper::encode($post['post']['abstract']))?></div>
        <?php }?>
        <?php if($post['post']['content']){?>
            <div class="post-content"><?php echo $post['post']['content']?></div>
        <?php }?>
        <?php if($post['files']){?>
            <div class="post-files">
                <h3><i class="fa fa-link"></i>附件</h3>
                <ul>
                <?php foreach($post['files'] as $file){?>
                    <li><?php
                        echo HtmlHelper::link(
                            HtmlHelper::img($file['thumbnail']),
                            $file['url'],
                            array(
                                'encode'=>false,
                                'title'=>HtmlHelper::encode($file['description']),
                                'data-fancybox'=>$file['is_image'] ? 'images' : false,
                                'data-caption'=>HtmlHelper::encode(HtmlHelper::encode($file['description'])),
                            )
                        ),
                        HtmlHelper::tag('p', array(
                            'class'=>'file-desc',
                        ), HtmlHelper::encode($file['description']));
                    ?></li>
                <?php }?>
                </ul>
            </div>
        <?php }?>
    </div>
</div>
</body>
</html>