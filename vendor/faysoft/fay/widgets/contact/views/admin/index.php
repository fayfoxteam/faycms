<?php
use fay\helpers\Html;
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
		<div class="form-group">
			<label class="col-2 title">勾选表单元素</label>
			<div class="col-10 pt7">
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'name', array(
						'label'=>'姓名'
					), true);
				?></span>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'email', array(
						'label'=>'邮箱'
					), true);
				?></span>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'content', array(
						'label'=>'内容'
					), true);
				?></span>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'mobile', array(
						'label'=>'电话'
					));
				?></span>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'title', array(
						'label'=>'标题'
					));
				?></span>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', 'country', array(
						'label'=>'国家'
					));
				?></span>
			</div>
		</div>
		<div class="dragsort-list" id="widget-contact-elements">
			<div class="dragsort-item cf">
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<div class="col-6">
						<strong class="mb10">姓名</strong>
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
<div class="box">
	<div class="box-title">
		<h4>渲染模版</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<?php echo Html::textarea('template', isset($config['template']) ? $config['template'] : '', array(
				'class'=>'form-control h90 autosize',
				'id'=>'code-editor',
			))?>
			<p class="fc-grey mt5">
				若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
				即类似<code>frontend/widget/template</code><br />
				则会调用当前application下符合该相对路径的view文件。<br />
				否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
			</p>
		</div>
	</div>
</div>