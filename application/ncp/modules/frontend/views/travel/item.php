<?php
use fay\helpers\Html;
use ncp\helpers\FriendlyLink;
use fay\services\FileService;
use fay\helpers\StringHelper;

$this->appendCss($this->appStatic('css/travel.css'));
?>
<div class="container containerbg">
	<div class="curnav">
		<strong>当前位置：</strong>
		<?php echo Html::link('首页', $this->url())?>
		&gt;
		<?php echo Html::link('农产品', array('product'))?>
		&gt;
		<span><?php echo Html::encode($post['title'])?></span>
	</div>
	<div id="J-detail">
		<div class="tm-clear">
			<div class="tm-warp">
				<div class="tb-property">
					<div class="tb-warp">
						<div class="tb-hd">
							<h1><?php echo Html::encode($post['title'])?></h1>
						</div>
						<div class="tb-info">
							<p><?php echo Html::encode($post['abstract'])?></p>
						</div>
						<ul class="tb-s">
							<li><label>分类：</label> <span><?php echo $post['cat_title']?></span></li>
							<li><label>产地：</label> <span><?php echo $area['title']?></span></li>
						</ul>
						<div class="go-bth">
						</div>
					</div>
				</div>
			</div>
			<div class="tm-ser">
				<?php echo Html::img($post['thumbnail'], FileService::PIC_RESIZE, array(
					'dw'=>400,
					'dh'=>300,
				))?>
			</div>
			<div class="tm-tuijian">
				<h3>景点推荐</h3>
				<ul>
				<?php foreach($right_top_posts as $p){?>
					<li><?php echo Html::link(Html::img($p['thumbnail'], FileService::PIC_RESIZE, array(
						'dw'=>180,
						'dh'=>135,
					)), FriendlyLink::getProductLink(array(
						'id'=>$p['id'],
					)), array(
						'encode'=>false,
						'title'=>Html::encode($p['title']),
					))?></li>
				<?php }?>
				</ul>
			</div>
		</div>
	</div>
	<div class="in_adv">
		<?php echo F::widget()->load('travel-item-ad')?>
	</div>
	<div class="detail-info mt10">
		<div class="d-fl">
			<div class="detail-hd">
				<h3 class="cur">景点介绍</h3>
				<h3>路线攻略</h3>
				<h3><?php echo $area['title']?>美食</h3>
				<h3><?php echo $area['title']?>特产</h3>
			</div>
			<div class="detail-p">
				<div class="s_in">
					<?php echo $post['content']?>
				</div>
				<div class="s_luxian">
					<h4>路线</h4>
					<?php foreach($routes as $k=>$r){?>
					<div class="luxian">
					 	<div class="luxian_title"><h5><i></i>路线<?php echo $k+1?></h5></div>
						<div class="luxian_info">
							<?php echo StringHelper::nl2p($r['route'])?>
						</div>
					 </div>
					 <?php }?>
				</div>
				<div class="s_meishi">
					<ul class="product">
					<?php foreach($food_posts as $p){?>
						<li>
							<div class="p-img">
								<?php echo Html::link(Html::img($p['thumbnail'], FileService::PIC_RESIZE, array(
									'dw'=>280,
									'dh'=>210,
									'alt'=>Html::encode($p['title']),
								)), FriendlyLink::getFoodLink(array(
									'id'=>$p['id'],
								)), array(
									'encode'=>false,
									'title'=>Html::encode($p['title']),
									'target'=>'_blank',
								))?>
							</div>
							<div class="p-name">
								<?php echo Html::link($p['title'], FriendlyLink::getFoodLink(array(
									'id'=>$p['id'],
								)), array(
									'target'=>'_blank',
								))?>
							</div>
							<div class="p-maoshu"><?php echo Html::encode($p['abstract'])?></div>
							<div class="p-st">
								<span class="fl"><?php echo $p['views']?></span>
								<span class="fr"><?php echo Html::link('我要吃', FriendlyLink::getFoodLink(array(
									'id'=>$p['id']
								)), array(
									'class'=>'gowhere',
									'target'=>'_blank',
								))?></span>
							</div>
						</li>
					<?php }?>
					</ul>
				</div>
				<div class="s_tec">
					<ul class="product">
					<?php foreach($product_posts as $p){?>
						<li>
							<div class="p-img">
								<?php echo Html::link(Html::img($p['thumbnail'], FileService::PIC_RESIZE, array(
									'dw'=>280,
									'dh'=>210,
								)), FriendlyLink::getProductLink(array(
									'id'=>$p['id'],
								)), array(
									'encode'=>false,
									'title'=>Html::encode($p['title']),
									'target'=>'_blank',
								))?>
							</div>
							<div class="p-name">
								<?php echo Html::link($p['title'], FriendlyLink::getProductLink(array(
									'id'=>$p['id'],
								)), array(
									'target'=>'_blank',
								))?>
							</div>
							<div class="p-maoshu"><?php echo Html::encode($p['abstract'])?></div>
							<div class="p-st">
								<span class="fl"><?php echo $p['views']?></span>
								<span class="fr"><?php echo Html::link('我要买', FriendlyLink::getProductLink(array(
									'id'=>$p['id']
								)), array(
									'class'=>'gowhere',
								))?></span>
							</div>
						</li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-fr">
			<h3>推荐农产品</h3>
			<ul>
			<?php foreach($right_posts as $p){?>
				<li>
					<p class="p-img">
						<?php echo Html::link(Html::img($p['thumbnail'], FileService::PIC_RESIZE, array(
							'dw'=>180,
							'dh'=>135,
						)), FriendlyLink::getProductLink(array(
							'id'=>$p['id'],
						)), array(
							'encode'=>false,
							'title'=>Html::encode($p['title']),
						))?>
					</p>
					<p class="p-name">
						<?php echo Html::link($p['title'], FriendlyLink::getProductLink(array(
							'id'=>$p['id'],
						)))?>
					</p>
				</li>
			<?php }?>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".detail-hd h3").bind("click", function (){
		var index = $(this).index(index);
		var divs = $(".detail-p > div");
		$(this).parent().children("h3").attr("class", "no");//将所有选项置为未选中
		$(this).attr("class", "cur");	 //设置当前选中项为选中样式
		divs.hide(); 
		divs.eq(index).show();
	});
	$(".detail-p > div").css({"display":"none"});
	$(".detail-p > div:first").css({"display":"block"})
  </script>