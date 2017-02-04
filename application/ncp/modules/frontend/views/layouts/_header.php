<?php
use fay\helpers\HtmlHelper;
?>
<!--头部-->
<div class="header_hd">
	<div class="header">
		<div class="logo">
			<img src="<?php echo $this->appAssets('img/h1_logo.gif')?>">
		</div>
	</div>
</div>
<!--END头部-->
<!--菜单-->
<div class="mainNav">
	<div class="nav">
		<div id="mune">
			<h2></h2>
			<div class="mune_info">
				<ul>
					<li>
						<h3>
							<a href="#">粮油农产</a>
						</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>水果农产</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>茶叶农产</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>高山农产</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>蔬菜农产</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>禽畜牧业</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
					</li>
					<li>
						<h3>中药农产</h3>
						<div class="mune_list">
							<ul>
								<li><span><a href="#">米类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">豆类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
								<li><span><a href="#">油类</a></span> <a href="#">红米</a> <a
									href="#">小米</a> <a href="#">大米</a></li>
							</ul>
						</div>
			
			</div>
		</div>
		<ul class="nav_info fl">
			<li><?php echo HtmlHelper::link('农产品', array('product'), array(
				'target'=>'_blank',
			))?></li>
			<li><?php echo HtmlHelper::link('农旅游', array('travel'), array(
				'target'=>'_blank',
			))?></li>
			<li><?php echo HtmlHelper::link('农专题', array('special'), array(
				'target'=>'_blank',
			))?></li>
			<li><?php echo HtmlHelper::link('农美食', array('food'), array(
				'target'=>'_blank',
			))?></li>
			<li><?php echo HtmlHelper::link('农资讯', array('news'), array(
				'target'=>'_blank',
			))?></li>
		</ul>
	</div>
</div>
<!--END菜单-->