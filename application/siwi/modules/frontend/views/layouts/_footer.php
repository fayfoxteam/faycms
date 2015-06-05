<?php
use fay\models\Option;
?>
<footer class="g-ft">
	<div class="w1190">
		<div class="box m-about">
			<h3>关于思唯</h3>
			<div class="box-content"><?php echo F::app()->widget->load('footer_about')?></div>
		</div>
		<div class="box m-msg">
			<h3>信息</h3>
			<div class="box-content">
				<ul>
					<li><a href="">版权申明</a></li>
					<li><a href="">关于隐私</a></li>
					<li><a href="">免责声明</a></li>
					<li><a href="">网站地图</a></li>
					<li><a href="">常见问题</a></li>
				</ul>
			</div>
		</div>
		<div class="box m-contact">
			<h3>联系</h3>
			<div class="box-content">
				<ul>
					<li><a href="">在线留言</a></li>
					<li><a href="">联系我们</a></li>
					<li><a href="">关注我们</a></li>
				</ul>
			</div>
		</div>
		<div class="box m-weixin">
			<h3>我们的微信</h3>
			<div class="box-content">
				<img src="<?php echo $this->url()?>static/siwi/images/weixin.png" />
				<p>关注我们的微信公众号，每天都有新鲜的设计，最新的资讯，灵感由你掌握。</p>
			</div>
		</div>
	</div>
	<div class="g-fcp">
		<div class="w1190">
			<p class="tip">最佳分辨率1280*800，建议使用Chrome、Firefox、Safari、ie10版本浏览器</p>
			<p class="cp"><?php echo Option::get(site.copyright)?></p>
		</div>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->staticFile('js/common.js')?>"></script>
<script>common.init();</script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/analyst.min.js"></script>
<script>_fa.init();</script>
<?php echo F::app()->flash->get()?>