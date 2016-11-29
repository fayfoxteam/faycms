<?php
use fay\models\tables\Roles;
use fay\services\user\Role;
?>
<div class="box">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title bold">标题</label>
			<?php echo F::form('widget')->inputText('title', array(
				'class'=>'form-control mw400',
			))?>
			<p class="fc-grey">若为空，则显示顶级分类的标题</p>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<h4>表单元素</h4>
	</div>
	<div class="box-content">
		<div class="dragsort-list" id="widget-contact-elements">
			<div class="dragsort-item cf">
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<div class="col-6">
						<strong>姓名</strong>
						<input name="label[name]" type="text" class="form-control" placeholder="Label" />
					</div>
					<div class="col-6">
						<textarea name="placeholder[name]" class="form-control autosize" placeholder="Placeholder"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>