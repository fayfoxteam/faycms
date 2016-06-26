<?php
use fay\services\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" />
<?php echo $this->getCss()?>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.7.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<link type="image/x-icon" href="<?php echo $this->assets('favicon.ico" rel="shortcut icon')?>" />
<!--[if IE 6]>
	<script type="text/javascript" src="<?php echo $this->assets('js/DD_belatedPNG_0.0.8a-min.js')?>"></script>
<![endif]-->
<meta content="<?php echo Option::get('site:seo_index_keywords')?>" name="keywords" />
<meta content="<?php echo Option::get('site:seo_index_description')?>" name="description" />
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<title><?php if(!empty($title))echo $title . ' | '?><?php echo Option::get('site:sitename')?></title>
</head>
<body>
<div class="wrapper">
	<?php include '_header.php'?>
	<?php echo $content?>
	<?php include '_footer.php'?>
</div>
<script>
$(".titlediv").each(function(i){
	if($(this).find("input").val() != ""){
		$(this).find(".title-prompt-text").hide();
	}
});
$(".titlediv input").focus(function(){
	$(this).parent().find(".title-prompt-text").hide();
}).blur(function(){
	if($(this).val()==""){
		$(this).parent().find(".title-prompt-text").show();
	}
});
if($.browser.msie && $.browser.version == 6){
	DD_belatedPNG.fix(".fixpng");
}
</script>
</body>
</html>