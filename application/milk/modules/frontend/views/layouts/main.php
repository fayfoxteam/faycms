<?php 
use fay\helpers\Html;
use fay\models\Option;

?>

<!DOCTYPE html>
<html>
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">

    <title>
    <?php 
        if (!empty($title)){
            echo $title . ' - ';
        }
        echo Option::get('sitename');
    ?>
    </title>
    <meta name="description" content="<?php if ($description !== ''){
                                                echo Html::encode($description);
                                                }else {
                                                    echo Option::get('seo_index_description');
                                                }      
                                            ?>">
    <meta name="keywords" content="<?php 
									if($keywords !== ''){
										echo Html::encode($keywords);
									}else{
										echo Option::get('seo_index_keywords');
									}?>"/>

    <meta name="viewport" content="width=device-width">

    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="<?php echo $this->staticFile('css/grid.css')?>">
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>">
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/normalize.css')?>">

    <script src="<?php echo $this->staticFile('js/jquery.min.js')?>"></script>
    <script>window.jQuery || document.write('<script src="<?php echo $this->staticFile('js/jquery-1.8.3.min.js')?>"><\/script>')</script>
    <script src="<?php echo $this->staticFile('js/html5.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/main.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/radio.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/checkbox.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/selectBox.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.carouFredSel-5.2.2-packed.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.jqzoom-core.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.transit.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.easing.1.2.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.anythingslider.js')?>"></script>
    <script src="<?php echo $this->staticFile('js/jquery.anythingslider.fx.js')?>"></script>
</head>
<body>

  <?php include '_header.php';?>

  <?php echo $content;?>

   <?php include '_footer.php';?>
</body>
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
</html>
