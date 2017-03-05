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
		html,body,h1,ul,li,fieldset{padding:0;margin:0;border:0 none;font-size:12px}
		body{background-color:#F6F6F5;}
		a{text-decoration:none}
		.btn{color:#fff;padding:7px 10px;display:inline-block;text-align:center;border-radius:3px;font-size:12px;text-decoration:none;letter-spacing:2px}
		.btn-red{background-color:#E50012}
		.wrapper{padding-top:20px}
		.top-title{font-size:12px;text-align:center}
		.top-title-img{background-color:#E50012;padding:10px 20px;margin:0 auto;margin-top:6px;width:80%;text-align:center;box-sizing:border-box}
		.top-title-img img{width:100%}
		.top-title-3{margin-top:26px;text-align:center}
		.top-title-3 img{width:84%}
		.form{margin-top:10px}
		.form fieldset{text-align:center;width:80%;margin:0 auto 20px}
		.form fieldset input{padding:5px 10px;font-size:12px;}
		.form fieldset #avatar{width:86px;height:104px;border:1px solid #888889}
		.form fieldset .upload-link{font-size:12px;color:#888889}
		.form fieldset textarea{width:100%;height:60px;padding:5px}
		.form fieldset label{display:block;text-align:left;margin-bottom:6px;}
		.form fieldset .desc{color:#888889;text-align:center;margin-top:2px}
	</style>
</head>
<body>
<div class="wrapper">
	<h1 class="top-title">关公点兵—关公文化体验旅游主题产品</h1>
	<div class="top-title-img"><img src="<?php echo $this->appAssets('images/speak/c-t1.png')?>"></div>
	<div class="top-title-3"><img src="<?php echo $this->appAssets('images/speak/c-t2.png')?>"></div>
	<div class="form">
		<fieldset><input type="text" name="name" placeholder="填写您的名字"></fieldset>
		<fieldset>
			<div id="avatar-container">
				<img src="" id="avatar">
			</div>
			<a href="" class="upload-link">点击+上传一张您的帅气英雄照</a>
		</fieldset>
		<fieldset>
			<label>我的代言口号</label>
			<textarea name="words" placeholder="一句话，由心生，正能量，短有力。（15字内）"></textarea>
		</fieldset>
		<fieldset>
			<a href="" class="btn btn-red">立现我的代言海报</a>
			<div class="desc">代言后即履行承诺加入关羽军团</div>
		</fieldset>
	</div>
</div>
</body>
</html>