<?php
use fay\models\Option;
?>
<div class="site-nav">
	<div class="w1000">
		<div class="fl">
			<a onclick="homePage(this)" href="#">设为首页</a>
			<span class="dp">|</span>
			<a href="javascript:;" onclick="AddFavorite('<?php echo $this->url()?>', '<?php echo Option::get('site:sitename')?>')">加入收藏</a>
			<span class="dp">|</span>
			<a href="">联系我们</a>
		</div>
	</div>
</div>
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
$('#email-form-submit').on('click', function(){
	loginRequest()
	//$('#email-form').submit();
	return false;
});
</script>