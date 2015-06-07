<?php
use fay\models\Option;
use fay\helpers\Html;
$css_url = $this->staticFile('css');
$js_url = $this->staticFile('js');
$img_url = $this->staticFile('images');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php if(!empty($title)){echo $title, ' - ';}echo Option::get('sitename')?></title>
    <meta name="keywords" content="<?php
    if(isset($keywords)){
        echo Html::encode($keywords);
    }else{
        echo Option::get('seo_index_keywords');
    }?>" />
    <meta name="description" content="<?php
    if(isset($description)){
        echo Html::encode($description);
    }else{
        echo Option::get('seo_index_description');
    }?>" />
    <link href="<?= $css_url ?>/base.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/index.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/util.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= $this->url('js/jquery-1.8.3.min.js') ?>"></script>
    <script type="text/javascript" src="<?= $js_url ?>/jquery.SuperSlide.2.1.1.js"></script>

    
</head>

<body>

<?php include '_header.php' ?>

<?php echo $content ?>

<?php include '_footer.php' ?>

<script src="<?= $this->staticFile('js/Msclass.js') ?>"></script>
<script src="<?= $this->staticFile('js/base.js') ?>"></script>


</body>
</html>
