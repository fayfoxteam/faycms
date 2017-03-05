<?php
/**
 * @var $this \fay\core\View
 */
?>
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

<?php echo $this->getCss()?>
<style>
    html,body,h1{padding:0;margin:0}
    body{background-color:#F6F6F5;}
    .btn{color:#fff;padding:7px 10px;display:inline-block;text-align:center;border-radius:3px;font-size:12px;text-decoration:none}
    .btn-red{background-color:#E50012}
    .btn-blue{background-color:#008CD6}

    .wrapper{padding:20px 0}
    .top-title{font-size:12px;text-align:center}
    .top-title-img{background-color:#E50012;padding:10px 20px;margin:0 auto;margin-top:6px;width:94%;text-align:center}
    .top-title-img img{width:100%}

    .img-box{border:1px solid #717070;width:84%;margin:0 auto;margin-top:20px;height:400px;position:relative}
    .img-box-title{position:absolute;bottom:10px;width:90%;left:5%}
    .img-box-title img{width:100%}

    .desc{color:#B50006;text-align:center;margin-top:14px}

    .share-box .t3{font-weight:bold;font-size:14px;line-height:1.2}
    .share-box{width:96%;margin:0 auto;border-top:2px solid #888889;border-bottom:2px solid #888889;padding:12px 20px;margin-top:15px;position:relative}
    .share-box .btns{margin-top:16px}
    .share-box .yin{width:22%;bottom:-20px;right:118px;position:absolute;}
    .share-box .qr-code{height:83px;float:right;margin-right:4px}
    .share-box .qr-code-desc{height:81px;float:right}
</style>
</head>
<body>
	<div class="wrapper">
		<h1 class="top-title">关公点兵—关公文化体验旅游主题产品</h1>
		<h2 class="top-title-img"><img src="<?php echo $this->appAssets('images/speak/t1.png')?>"></h2>
		<div class="img-box">
			<img src="">
			<div class="img-box-title"><img src="<?php echo $this->appAssets('images/speak/t2.png')?>"></div>
		</div>
		<div class="desc">夏循涛贵为关羽军团第0000001位代言人</div>
		<div class="share-box">
            <img src="<?php echo $this->appAssets('images/speak/qr-desc.png')?>" class="qr-code-desc">
            <img src="<?php echo $this->appAssets('images/speak/qr.jpg')?>" class="qr-code">
			<div class="t3">若每天一点正能量，<br>青春路上有阳光。</div>
			<div class="btns">
				<a href="" class="btn btn-red">分享</a>
				<a href="" class="btn btn-blue">我也要代言</a>
			</div>
			<img src="<?php echo $this->appAssets('images/speak/yin.png')?>" class="yin">
            <br class="clear">
		</div>
	</div>
</body>
</html>