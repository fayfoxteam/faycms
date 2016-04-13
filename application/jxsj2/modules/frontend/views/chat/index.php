<?php
use fay\helpers\Html;
use fay\models\tables\Users;
?>
<div id="banner">
	<?php \F::widget()->load('index-slides')?>
</div>
<div class="w1000 clearfix bg-white">
	<div class="w230 fl">
		<?php
		//直接引用widget
		\F::widget()->render('fay/category_posts', array(
			'title'=>'最新发布',
			'order'=>'publish_time',
			'template'=>'frontend/widget/category_posts',
		));
		//$this->renderPartial('common/_login_panel')?>
	</div>
	<div class="ml240">
		<div class="box fr wp100">
			<div class="box-title">
				<h3>互动交流</h3>
			</div>
			<div class="box-content">
				<div class="st"><div class="sl"><div class="sr"><div class="sb">
					<div class="p16">
						<section class="clearfix create-message-container">
							<h2>我要留言</h2>
							<form id="create-message-form" action="<?php echo $this->url('chat/create')?>" method="post">
								<?php echo Html::inputHidden('parent', 0)?>
								<textarea name="content"></textarea>
								<label>您的姓名</label><input type="text" name="realname" />
								<input type="submit" value="发表评论" />
							</form>
						</section>
						<section class="clearfix message-container">
							<ul class="message-list"><?php $listview->showData()?></ul>
							<div class="clear"></div>
						</section>
						<?php $listview->showPager();?>
					</div>
				</div></div></div></div>
			</div>
		</div>
	</div>
</div>