<?php 
use fay\models\Option;
use fay\helpers\Html;
?>
<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title><?php if(!empty($title)){
	echo $title, ' - ';
}
echo Option::get('sitename')?></title>
<meta name="description" content="<?php 
									if($keywords !== ''){
										echo Html::encode($description);
									}else{
										echo Option::get('seo_index_description');
									}?>" />
<meta name="keywords" content="<?php 
									if($keywords !== ''){
										echo Html::encode($keywords);
									}else{
										echo Option::get('seo_index_keywords');
									}?>" " />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<link href="<?php echo $this->staticFile('css/master.css')?>" type="text/css" rel="stylesheet" />
<link href="<?php echo $this->staticFile('css/base.css')?>" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $this->staticFile('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.SuperSlide.2.1.1.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
            system.base_url = '<?php echo $this->url()?>';
            system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
        </script>
        <?php echo $this->getCss()?>
</head>


<body>
    <?php include '_header.php'; ?>
	<?php echo $content ?>
	<?php include '_footer.php'; ?>


<script src="<?php echo $this->staticFile('js/all.js')?>" type="text/javascript"></script>
</body>
</html>