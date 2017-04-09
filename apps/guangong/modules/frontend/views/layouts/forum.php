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
    
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/animate/animate.min.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('js/swiper/css/swiper.min.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/fontello.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/style.css')?>" >
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/forum.css')?>" >
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
</head>
<body>
<?php echo $content?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/fayfox.block.js')?>"></script>
</body>
</html>