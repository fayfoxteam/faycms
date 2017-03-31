<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
?>
<style>
#login-panel table{padding:13px 0;}
#login-panel{margin-bottom:10px;}
</style>
<div id="banner">
	<?php F::widget()->load('index-slides')?>
</div>
<div class="g-con">
	<div class="w1000 clearfix bg-white">
		<div class="w300 fr">
			<?php $this->renderPartial('common/_login_panel')?>
			<?php //$this->renderPartial('common/_contact')?>
			<?php //F::widget()->load('friendlinks')?>
			<div class="box" id="quick-guide">
				<div class="box-title">
					<h3>快速导航</h3>
				</div>
				<div class="box-content">
					<div class="st"><div class="sl"><div class="sr"><div class="sb">
						<div class="p16 clearfix">
							<p><?php F::widget()->load('index-quickguide-1')?></p>
							<p><?php F::widget()->load('index-quickguide-2')?></p>
							<p><?php F::widget()->load('index-quickguide-3')?></p>
							<p><?php F::widget()->load('index-quickguide-4')?></p>
						</div>
					</div></div></div></div>
				</div>
			</div>
		</div>
		<div class="mr310">
			<section class="mb10 fl wp100">
				<div class="wp37 fl">
					<?php F::widget()->load('index-1-1')?>
				</div>
				<div class="wp62 fr">
					<div class="box" id="index-about">
						<div class="box-title">
							<h3>课程介绍</h3>
						</div>
						<div class="box-content">
							<div class="st"><div class="sl"><div class="sr"><div class="sb">
								<div class="p16 clearfix">
									<div id="index-page">
										<?php echo HtmlHelper::link(HtmlHelper::img($about['thumbnail'], FileService::PIC_ORIGINAL, array(
											'width'=>false,
											'height'=>false,
										)), array('page/'.$about['id']), array(
											'encode'=>false,
											'title'=>$about['title'],
											'alt'=>$about['title'],
										))?>
										<p><?php
											echo HtmlHelper::encode($about['abstract']);
											echo HtmlHelper::link('[查看详细]', array('page/'.$about['id']), array(
												'title'=>false,
											));
										?></p>
									</div>
								</div>
							</div></div></div></div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</section>
			<section class="clearfix">
				<div class="wp37 fl">
					<?php F::widget()->load('index-2-1')?>
				</div>
				<div class="wp62 fr">
					<?php F::widget()->load('index-2-2')?>
				</div>
			</section>
		</div>
	</div>
	<div class="w1000 bg-white">
		<section class="clearfix pt20">
			<?php F::widget()->load('index-bottom-gallery')?>
		</section>
	</div>
</div>
<script src="<?php echo $this->assets('js/jquery.kxbdmarquee.js')?>"></script>
<script>
var app = {
	'workList':function(){
		$(".box-gallery-container").kxbdMarquee({
			'direction':'left',
			'scrollDelay':20
		});
	},
	'leftTop':function(){
		$("#index-1-1 .gallery-container").kxbdMarquee({
			'direction':'up',
			'scrollDelay':50
		});
	},
	'init':function(){
		this.workList();
		this.leftTop();
	}
};
$(function(){
	app.init();
})
</script>