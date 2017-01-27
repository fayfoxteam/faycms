<?php
/**
 * @var $this \fay\core\View
 * @var $groups array
 */
$this->appendCss($this->appStatic('css/group.css'));
$this->appendCss($this->assets('css/font-awesome.min.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-42">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer subtitle">
				<span class="title">盟誓</span>
				<span>第三式</span>
			</div>
			<div class="layer left-bottom"><img src="<?php echo $this->appStatic('images/group/lb.png')?>"></div>
			<div class="layer" id="group-list">
				<ul><?php foreach($groups as $g){?>
					<li>
						<p class="name"><?php echo $g['name']?></p>
						<p class="meta">
							<span class="count"><i class="fa fa-users"></i><?php echo $g['count']?></span>
							<span class="time"><i class="fa fa-calendar"></i><?php echo \fay\helpers\DateHelper::niceShort($g['create_time'])?></span>
							<a href="<?php echo $this->url('group/step5', array(
								'group_id'=>$g['id'])
							)?>">查看兰谱</a>
						</p>
					</li>
				<?php }?></ul>
			</div>
		</div>
		<div class="swiper-slide" id="group-41">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第四式</span>
				<span class="title">兰谱</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
		</div>
	</div>
</div>