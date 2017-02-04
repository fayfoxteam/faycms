<?php
use fay\helpers\HtmlHelper;
use siwi\helpers\FriendlyLink;

$this->appendCss($this->appAssets('css/material.css'));
?>
<div class="g-sub-hd clearfix">
	<div class="shot-menu">
		<ul>
			<li>
				<?php echo HtmlHelper::link($cat['title'], 'javascript:;', array(
					'class'=>'menu-item',
				))?>
				<ul class="sub">
					<li>
						<?php echo HtmlHelper::link('全部', FriendlyLink::get('material', 0, 0), array(
							'class'=>'sub-1',
						));?>
					</li>
				<?php foreach($cat_tree as $c){?>
					<li>
						<?php echo HtmlHelper::link($c['title'], FriendlyLink::get('material', $c['id']), array(
							'class'=>'sub-1',
						));?>
						<ul class="sub-sub">
							<?php foreach($c['children'] as $c2){?>
								<li><?php echo HtmlHelper::link($c2['title'], FriendlyLink::get('material', $c['id'], $c2['id']), array(
									'class'=>'sub-2',
								));?></li>
							<?php }?>
						</ul>
					</li>
				<?php }?>
				</ul>
			</li>
			<li>
				<?php echo HtmlHelper::link($time, 'javascript:;', array(
					'class'=>'menu-item',
				))?>
				<ul class="sub">
					<li><?php echo HtmlHelper::link('不限', FriendlyLink::get('material', -1, -1, 0), array(
						'class'=>'sub-1',
					))?>
					<li><?php echo HtmlHelper::link('三天内', FriendlyLink::get('material', -1, -1, 3), array(
						'class'=>'sub-1',
					))?>
					<li><?php echo HtmlHelper::link('一周内', FriendlyLink::get('material', -1, -1, 7), array(
						'class'=>'sub-1',
					))?>
					<li><?php echo HtmlHelper::link('一个月内', FriendlyLink::get('material', -1, -1, 30), array(
						'class'=>'sub-1',
					))?>
					<li><?php echo HtmlHelper::link('一年内', FriendlyLink::get('material', -1, -1, 365), array(
						'class'=>'sub-1',
					))?>
				</ul>
			</li>
		</ul>
	</div>
	<div class="right">
		<a href="<?php echo $this->url('user/material/create')?>" class="action-link">分享你的素材</a>
	</div>
</div>
<div class="clearfix col1">
	<section class="clearfix work-list">
		<?php $listview->showData()?>
	</section>
	<?php $listview->showPager()?>
</div>
