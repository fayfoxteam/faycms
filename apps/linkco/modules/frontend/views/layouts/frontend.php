<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $content string
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
    echo $title, '_';
}
echo OptionService::get('site:sitename')?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/animate/animate.min.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/style.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
</head>
<body>
<div class="logo-container">
    <a href="<?php echo $this->url()?>"><img src="<?php echo $this->appAssets('images/logo.png')?>"></a>
</div>
<nav class="top-nav animated fadeInUp">
    <ul>
        <li><a href="">首页</a></li>
        <li><a href="">我们</a></li>
        <li><a href="">案例</a></li>
        <li><a href="">服务</a></li>
    </ul>
</nav>
<div class="wrap">
<?php echo $content?>
</div>
<div class="social-container animated fadeInUp">
    <a href="">Behance</a>
    <span class="cut-off">/</span>
    <a href="">微信</a>
    <span class="cut-off">/</span>
    <a href="">微博</a>
</div>
<div class="language-container animated fadeInUp">
    <a href="">中文</a>
    <span class="cut-off">/</span>
    <a href="">EN</a>
</div>
</body>
</html>