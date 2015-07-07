<?php
use fay\models\Option;
?>
<header class="g-hd">
	<div class="w1000">
		<img src="<?php echo $this->appStatic('images/logo.png')?>" alt="<?php echo Option::get('site:sitename')?>" class="hd-logo" />
		<div class="hd-options">
			<a onclick="homePage(this)" href="#">设为首页</a>
			|
			<a onclick="AddFavorite('<?php echo $this->url()?>', '<?php echo Option::get('site:sitename')?>')" href="javascript:void(0);">加入收藏</a>
			|
			<a href="">联系我们</a>
		</div>
	</div>
</header>
<script>
function AddFavorite(sURL, sTitle){
	try{
		window.external.addFavorite(sURL, sTitle);
	}catch (e){
		try{
			window.sidebar.addPanel(sTitle, sURL, "");
		}catch (e){
			alert("你的浏览器不支持加入收藏功能，请快捷键来CTRL+D来加入<?php echo Option::get('site:sitename')?>");
		}
	}
};

</script>