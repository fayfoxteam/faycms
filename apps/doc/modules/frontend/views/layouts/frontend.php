<?php
use fay\helpers\HtmlHelper;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if(!empty($canonical)){?>
<link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<title><?php echo $title ? $title : ''?></title>
<meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
<meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/style.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
</script>
<!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
</head>
<body>
<div class="wrapper">
    <?php echo $this->renderPartial('layouts/_sidebar_menu')?>
    <div class="main-content">
        <div class="cf main-title">
            <h1 class="fl"><?php echo isset($page_title) ? $page_title : ''?></h1>
            <?php if(isset($breadcrumb)){?>
            <ol class="fr breadcrumb">
                <li>
                    <a href="<?php echo $this->assets('"><i class="icon-home')?>"></i>主页</a>
                </li>
                <?php foreach($breadcrumb as $b){?>
                <li><?php echo HtmlHelper::link($b['text'], $b['href'])?></li>
                <?php }?>
            </ol>
            <?php }?>
        </div>
        <div class="main-content-inner"><?php echo $content?></div>
        <?php echo $this->renderPartial('layouts/_footer')?>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->appAssets('js/common.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prefixfree.min.js')?>"></script>
<script>
$(function(){
    common.init();
});
</script>
</body>
</html>