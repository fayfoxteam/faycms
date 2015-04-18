<!doctype html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>教学名师评选</title>
	<link rel="stylesheet" href="<?php echo $this->staticFile('css/bootstrap.min.css')?>" />
	<link rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>" />
    <script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.1.11.1.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.user_id = '<?php echo F::app()->session->get('id', 0) ?>';
    </script>
</head>
<body>
<div class="container">

	
	<?php include '_header.php';?>
	
	<?php echo $content?>

</div>
	
	<?php include '_footer.php';?>


	<script src="<?php echo $this->staticFile('js/bootstrap.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->staticFile('js/vote.js')?>"></script>


</body>
</html>