<div class="col-2-1">
	<div class="col-left">
		<div class="col-content">
			<?php echo F::app()->widget->render('cms/user_info', array(), true);?>
		</div>
	</div>
	<div class="col-right">
		<div class="col-content">
			<?php echo F::app()->widget->render('cms/js_info');?>
			<?php 
				if($browser[0] == 'msie' && $browser[1] == '6.0'){
			?>
			<div class="box">
				<div class="box-title">
					<a class="handlediv" title="点击以切换"></a>
					<h4>您正在使用很古老的浏览器</h4>
				</div>
				<div class="box-content">
					<img src="<?php echo $this->url()?>images/ie.png" class="fr" style="margin:0 0 10px 10px;" />
					<p>
						似乎您正在使用的
						<a href="http://www.microsoft.com/windows/internet-explorer/">Internet Explorer</a>
						版本严重过时。使用过时的浏览器会降低您计算机的安全性。同时，为了获得最佳体验，请升级您的浏览器。
						<br /><br />
						升级您的
						<a href="http://windows.microsoft.com/zh-cn/windows/upgrade-your-browser">Internet Explorer</a>，或了解一下
						<a href="http://browsehappy.com/?locale=zh_CN">先进的浏览器</a>的有关信息。
					</p>
					<div class="clear"></div>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<div class="clear"></div>
</div>