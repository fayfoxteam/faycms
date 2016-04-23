<?php
use fay\helpers\Html;
use fay\models\tables\Roles;
use fay\models\user\Role;
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
		<div class="form-field">
			<label class="title bold">分类</label>
			<?php echo F::form('widget')->select('top', Html::getSelectOptions($cats), array(
				'class'=>'form-control mw400',
			))?>
		</div>
		<div class="form-field">
			<label class="title bold">显示页面数</label>
			<?php echo F::form('widget')->inputText('number', array(
				'class'=>'form-control mw150',
			), 5)?>
		</div>
		<div class="form-field">
			<label class="title bold">无数据时是否显示小工具</label>
			<?php echo F::form('widget')->inputRadio('show_empty', 1, array(
				'label'=>'是',
			))?>
			<?php echo F::form('widget')->inputRadio('show_empty', 0, array(
				'label'=>'否',
			), true)?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
		</div>
		<div class="advance <?php if(!Role::model()->is(Roles::ITEM_SUPER_ADMIN))echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">链接格式<span class="fc-red">（若非开发人员，请不要修改此配置）</span></label>
				<?php
					echo Html::inputRadio('uri', 'page/{$id}', !isset($config['uri']) || $config['uri'] == 'page/{$id}', array(
						'label'=>'page/{$id}',
					));
					echo Html::inputRadio('uri', 'page/{$alias}', isset($config['uri']) && $config['uri'] == 'page/{$alias}', array(
						'label'=>'page/{$alias}',
					));
					echo Html::inputRadio('uri', 'page-{$id}', isset($config['uri']) && $config['uri'] == 'page-{$id}', array(
						'label'=>'page-{$id}',
					));
					echo Html::inputRadio('uri', 'page-{$alias}', isset($config['uri']) && $config['uri'] == 'page-{$alias}', array(
						'label'=>'page-{$alias}',
					));
					echo Html::inputRadio('uri', '', isset($config['uri']) && !in_array($config['uri'], array(
						'page/{$id}', 'page-{$id}',
					)), array(
						'label'=>'其它',
					));
					echo Html::inputText('other_uri', isset($config['uri']) && !in_array($config['uri'], array(
						'page/{$id}', 'page/{$alias}', 'page-{$id}', 'page-{$alias}',
					)) ? $config['uri'] : '', array(
						'class'=>'form-control mw150 ib',
					));
				?>
				<p class="fc-grey">
					<code>{$id}</code>代表“文章ID”。
					不要包含base_url部分
				</p>
			</div>
			<div class="form-field">
				<label class="title bold">渲染模版<span class="fc-red">（若非开发人员，请不要修改此配置）</span></label>
				<?php echo F::form('widget')->textarea('template', array(
					'class'=>'form-control h90 autosize',
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
</div>
<script>
$(function(){
	$('.toggle-advance').on('click', function(){
		$(".advance").toggle();
	});
});
</script>