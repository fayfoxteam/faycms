<?php 
use fay\models\Option;
use fay\helpers\Html;
$cssUrl = $this->staticFile('css/');
$jsUrl = $this->staticFile('js/');
$imgUrl = $this->staticFile('images/'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<title><?php
    if (!empty($title))
    {
        echo $title. ' - ';
    }
    echo Option::get('sitename');
    ?>
</title>
<meta name="Keywords" content="
<?php
if($keywords !== ''){
    echo Html::encode($keywords);
}else{
    echo Option::get('seo_index_keywords');
}?>
"/>
<meta name="Description" content="
<?php
if($keywords !== ''){
    echo Html::encode($description);
}else{
    echo Option::get('seo_index_description');
}?>
"/>
<!-- Le styles -->
<link rel="stylesheet" type="text/css" href="<?php echo $cssUrl ?>basic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $cssUrl ?>index.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $cssUrl ?>util.css"/>

<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $jsUrl ?>jquery.lazyload.mini.js"></script>
<script type="text/javascript" src="<?php echo $jsUrl ?>bioV4.min.js"></script>
<script src="<?php echo $jsUrl?>hq.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
    system.base_url = '<?php echo $this->url()?>';
    system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo $jsUrl ?>DD_belatedPNG.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('img,.ie6png');
</script>
<![endif]--> 

</head>
<body>
<script type="text/javascript" src="<?php echo $jsUrl ?>borsertocss.js"> </script><!-- 判断在IE9以下浏览器中根据像素的不同而设置的样式 -->		


<?php include '_header.php'; ?>

<?php echo $content; ?>

<?php include '_footer.php'; ?>






</body>
</html>