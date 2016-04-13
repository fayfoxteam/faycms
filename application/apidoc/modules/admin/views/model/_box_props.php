<?php
use fay\helpers\Html;
?>
<div class="box" id="box-props" data-name="props">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>属性</h4>
	</div>
	<div class="box-content">
		<div class="cf mt5">
			<?php echo Html::link('新增属性', '#add-prop-dialog', array(
				'class'=>'btn',
				'id'=>'add-prop-link',
			))?>
		</div>
		<div class="dragsort-list" id="model-list">
			<div class="dragsort-item">
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25"><strong>post</strong></span>
					<span class="ib wp15">Post []</span>
					<span class="ib">zsxcv</span>
				</div>
			</div>
			<div class="dragsort-item">
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25"><strong>post</strong></span>
					<span class="ib wp15">Post []</span>
					<span class="ib">zsxcv</span>
				</div>
			</div>
			<div class="dragsort-item">
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<span class="ib wp25"><strong>post</strong></span>
					<span class="ib wp15">Post []</span>
					<span class="ib">zsxcv</span>
				</div>
			</div>
		<?php if(isset($props)){?>
			<?php foreach($props as $d){?>
			<div class="dragsort-item cf">
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<ul>
						<li>post</li>
						<li>Post []</li>
						<li>asdf</li>
						<li>zsxcv</li>
					</ul>
				</div>
			</div>
			<?php }?>
		<?php }?>
		</div>
	</div>
</div>