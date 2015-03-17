<?php
use fay\models\Option;
use fay\helpers\Html;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php if (!empty($title)){
		echo $title, ' - ';
		}
		echo Option::get('sitename');
		?></title>
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
		<link href="<?php echo $this->staticFile('css/style.css')?>" rel="stylesheet" type="text/css"  media="all" />
		<link rel="stylesheet" href="<?php echo $this->staticFile('css/responsiveslides.css')?>">
		<script src="<?php echo $this->staticFile('js/jquery-1.8.3.min.js')?>"></script>
		<script src="<?php echo $this->staticFile('js/responsiveslides.min.js')?>"></script>
		  <script>
		    // You can also use "$(window).load(function() {"
			    $(function () {
			      // Slideshow 1
			      $("#slider1").responsiveSlides({
			        maxwidth: 2500,
			        speed: 600
			      });
			});
		  </script>
	</head>
	<body>
	
	<?php include '_header.php';?>
	<?php echo $content;?>
	
	<?php include '_footer.php';?>
	

	
</body>
</html>

