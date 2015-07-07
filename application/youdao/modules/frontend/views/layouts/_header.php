<header id="masthead">
	<hgroup>
		<h1 class="fixpng"><?php echo Option::get('site:sitename')?></h1>
		<h2 class="fixpng">诚信为本 合作共赢 专业团队 验厂无忧</h2>
	</hgroup>
	<form id="searchform" method="get" action="<?php echo $this->url('post/index')?>">
		<div id="searchform-container" class="titlediv">
			<label for="searchform-input" class="title-prompt-text">搜索</label>
			<input type="text" id="searchform-input" name="k" value="<?php echo $this->input->get('k')?>" />
			<a href="javascript:;" id="searchform-submit"><img src="<?php echo $this->appStatic('images/search.png')?>" /></a>
		</div>
	</form>
	<div id="header-options">
		<a onclick="homePage(this)" href="#">设为首页</a>
		|
		<a onclick="AddFavorite('<?php echo $this->url()?>', '<?php echo Option::get('site:sitename')?>')" href="javascript:void(0);">加入收藏</a>
	</div>
	<?php include '_navigation.php'?>
</header>
<script>
$(function(){
	$("#searchform-submit").click(function(){
		$("#searchform").submit();
	});
});
</script>