<?php
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
    <?php if(!empty($canonical)){?>
        <link rel="canonical" href="<?php echo $canonical?>" />
    <?php }?>
    <title><?php if(!empty($title)){
        echo $title;
    }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
    <?php echo $this->getCss()?>
    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.user_id = '<?php echo \F::app()->current_user?>';
    </script>
    <style>
        h1{color:#231915;font-size:20px;text-align:center;margin:10px 0 16px}
        .wrapper{padding:20px}
        .post-content{margin-bottom:16px}
        .post-content p{line-height:1.5;}
        .post-content img{max-width:95%}
        .return-container{text-align:center;margin-bottom:20px}
        .btn{background-color:#E6212A;color:#fff;padding:8px 26px;display:inline-block;text-decoration:none}
    </style>
</head>
<body>
<div class="wrapper">
    <h1><?php echo HtmlHelper::encode($post['post']['title'])?></h1>
    <div class="post-content"><?php echo $post['post']['content']?></div>
    <div class="return-container"><a href="<?php echo \fay\helpers\UrlHelper::createUrl('api/post/log-read', array(
        'id'=>$post['post']['id'],
    ))?>" class="btn">已&nbsp;读</a></div>
</div>
</body>
</html>