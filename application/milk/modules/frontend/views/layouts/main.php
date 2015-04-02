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
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/until.css')?>" media="all" />
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/grid.css')?>">
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/style.css')?>">
    <link rel="stylesheet" href="<?php echo $this->staticFile('css/normalize.css')?>">
<?php echo $this->getCss()?>
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
   
<div class="go-top dn" id="go-top">
    <a href="javascript:;" class="uc-2vm"></a>
	<div class="uc-2vm-pop dn">
		<div class="logo-2wm-box">
			<img src="<?php echo $this->staticFile('img/gototop/weixin.jpg')?>" alt="" width="240" height="240">
		</div>
	</div>
    <a href="#" target="_blank" class="feedback"></a>
    <a href="javascript:;" class="go"></a>
</div>

<script>
$(function(){
	$(window).on('scroll',function(){
		var st = $(document).scrollTop();
		if( st>0 ){
			if( $('#main-container').length != 0  ){
				var w = $(window).width(),mw = $('#main-container').width();
				if( (w-mw)/2 > 70 )
					$('#go-top').css({'left':(w-mw)/2+mw+20});
				else{
					$('#go-top').css({'left':'auto'});
				}
			}
			$('#go-top').fadeIn(function(){
				$(this).removeClass('dn');
			});
		}else{
			$('#go-top').fadeOut(function(){
				$(this).addClass('dn');
			});
		}	
	});
	$('#go-top .go').on('click',function(){
		$('html,body').animate({'scrollTop':0},500);
	});

	$('#go-top .uc-2vm').hover(function(){
		$('#go-top .uc-2vm-pop').removeClass('dn');
	},function(){
		$('#go-top .uc-2vm-pop').addClass('dn');
	});
});
</script>
</body>
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
</html>
