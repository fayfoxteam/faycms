<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php if(!empty($canonical)){?>
        <link rel="canonical" href="<?php echo $canonical?>" />
    <?php }?>
    <title><?php if(!empty($title)){
            echo $title, ' | ';
        }
        echo OptionService::get('site:sitename')?></title>
    <meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
    <meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
    <link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/style.css')?>" >
    <?php echo $this->getCss()?>
    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-2.2.4.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.user_id = '<?php echo \F::app()->current_user?>';
    </script>
</head>
<body>
<div class="wrapper">
    <?php echo $this->renderPartial('layouts/_header')?>
    <?php echo $this->renderPartial('layouts/_mobile_header')?>
    <?php if($show_banner){
        F::widget()->load('banner');
    }?>
    <?php echo $content?>
    <?php echo $this->renderPartial('layouts/_footer');?>
    <div class="page-copyright">
        <span><?php echo OptionService::get('site:copyright')?></span>
    </div>
</div>
<a href="#" class="scroll-to-top" title="回到顶部"></a>
<script type="text/javascript" src="<?php echo $this->appAssets('js/common.js')?>"></script>
<script>
$(function(){
    common.init();
});
</script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>
</body>
</html>