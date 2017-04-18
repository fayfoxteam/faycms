<?php

/**
 * @var $this \fay\core\View
 * @var $history array
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/post-preview.css')?>" />
    <?php echo $this->getCss()?>
    
    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
    <![endif]-->
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.assets_url = '<?php echo \F::config()->get('assets_url')?>';
        system.user_id = <?php echo \F::app()->current_user?>;
    </script>
    <title><?php echo $history['title']?> | <?php echo \cms\services\OptionService::get('site:sitename')?>后台</title>
</head>
<body>
<div class="post-preview">
    <h2 class="post-title"><?php echo \fay\helpers\HtmlHelper::encode($history['title'])?></h2>
    <div class="post-type">
        <span><?php echo \fay\helpers\HtmlHelper::encode($history['category']['title'])?></span>
    </div>
    <div class="post-info">
        <div class="history-options">
            <a href="javascript:" title="恢复到此版本" class="btn btn-grey post-history-revert-link" data-id=""><i class="fa fa-undo"></i></a>
            <a href="javascript:" title="删除此版本" class="btn btn-grey post-history-remove-link" data-id=""><i class="fa fa-trash"></i></a>
        </div>
        <?php echo \fay\helpers\HtmlHelper::img($history['user']['user']['avatar']['thumbnail'], 1, array(
            'class'=>'avatar',
        ))?>
        <span class="user"><?php echo \fay\helpers\HtmlHelper::encode($history['user']['user']['nickname'] ? $history['user']['user']['nickname'] : $history['user']['user']['username'])?></span>
        <time class="time" title=""><?php echo \fay\helpers\DateHelper::format($history['create_time'])?></time>
    </div>
    <div class="post-body">
        <div class="post-thumbnail"><?php echo \fay\helpers\HtmlHelper::img($history['thumbnail'])?></div>
        <div class="post-abstract"><?php echo \fay\helpers\HtmlHelper::encode($history['abstract'])?></div>
        <div class="post-content"><?php echo $history['content']?></div>
    </div>
</div>
</body>
</html>