<?php
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $history array
 * @var $preview_history array
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/post-preview.css')?>" />
    <?php echo $this->getCss()?>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
    <![endif]-->
    <title><?php echo HtmlHelper::encode($history['title'])?> | <?php echo \cms\services\OptionService::get('site:sitename')?>后台</title>
</head>
<body>
<div class="post-preview">
    <h2 class="post-title"><?php
        if($preview_history){
            $title_diff = new Icap\HtmlDiff\HtmlDiff($preview_history['title'], $history['title'], true);
            echo $title_diff->outputDiff()->toString();
        }else{
            echo $history['title'] ? HtmlHelper::encode($history['title']) : '--无标题--';
        }
        ?></h2>
    <div class="post-type">
        <span class="<?php
        if(!empty($preview_history['cat_id']) && $preview_history['cat_id'] != $history['cat_id']){
            echo 'bg-green';
        }?>"><?php echo HtmlHelper::encode($history['category']['title'])?></span>
    </div>
    <div class="post-info">
        <div class="history-options">
            <a href="javascript:" title="恢复到此版本" class="btn btn-grey post-history-revert-link" onclick="parent.post.revertHistory(<?php echo $history['id']?>)"><i class="fa fa-undo"></i></a>
            <a href="javascript:" title="删除此版本" class="btn btn-grey post-history-remove-link" onclick="parent.post.removeHistory(<?php echo $history['id']?>)"><i class="fa fa-trash"></i></a>
        </div>
        <?php echo HtmlHelper::img($history['user']['user']['avatar']['thumbnail'], 1, array(
            'class'=>'avatar',
        ))?>
        <span class="user"><?php echo HtmlHelper::encode($history['user']['user']['nickname'] ? $history['user']['user']['nickname'] : $history['user']['user']['username'])?></span>
        <time class="time" title=""><?php echo \fay\helpers\DateHelper::format($history['create_time'])?></time>
    </div>
    <div class="post-body">
        <div class="post-thumbnail"><?php echo HtmlHelper::img($history['thumbnail'], 0, array(
            'class'=>(!empty($preview_history['thumbnail']) && $preview_history['thumbnail'] != $history['thumbnail']) ? 'bg-green' : false,
        ))?></div>
        <?php if(!empty($preview_history['abstract'])){?>
            <div class="post-abstract"><?php
                $abstract_diff = new Icap\HtmlDiff\HtmlDiff($preview_history['abstract'], $history['abstract'], true);
                echo $abstract_diff->outputDiff()->toString();
            ?></div>
        <?php }else if($history['abstract']){?>
            <div class="post-abstract"><?php echo HtmlHelper::encode($history['abstract'])?></div>
        <?php }?>
        <?php if(!empty($preview_history['content'])){?>
            <div class="post-content"><?php
                $content_diff = new Icap\HtmlDiff\HtmlDiff($preview_history['content'], $history['content'], true);
                echo $content_diff->outputDiff()->toString();
            ?></div>
        <?php }else if($history['content']){?>
            <div class="post-content"><?php echo $history['content']?></div>
        <?php }?>
    </div>
</div>
</body>
</html>