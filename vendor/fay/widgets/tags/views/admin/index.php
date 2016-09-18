<?php
use fay\helpers\Html;
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
			), '标签云')?>
		</div>
		<div class="form-field">
			<label class="title bold">数量</label>
			<?php echo F::form('widget')->inputText('number', array(
				'class'=>'form-control mw400',
			), '10')?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(!Role::service()->is(Roles::ITEM_SUPER_ADMIN))echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">链接格式</label>
				<?php
					echo Html::inputRadio('uri', 'tag/{$id}', !isset($config['uri']) || $config['uri'] == 'tag/{$id}', array(
						'label'=>'tag/{$id}',
					));
					echo Html::inputRadio('uri', 'tag/{$name}', isset($config['uri']) && $config['uri'] == 'tag/{$name}', array(
						'label'=>'tag/{$name}',
					));
					echo Html::inputRadio('uri', '', isset($config['uri']) &&!in_array($config['uri'], array(
						'tag/{$id}', 'tag/{$name}',
					)), array(
						'label'=>'其它',
					));
					echo Html::inputText('other_uri', isset($config['uri']) &&!in_array($config['uri'], array(
						'tag/{$id}', 'tag/{$name}',
					)) ? $config['uri'] : '', array(
						'class'=>'form-control mw150 ib',
					));
				?>
				<p class="fc-grey">
					<code>{$id}</code>代表“标签ID”。
					<code>{$name}</code>代表“标签名称”。
					不要包含base_url部分。<br>
					<span class="fc-orange">此配置项是否生效取决于模版代码</span>
				</p>
			</div>
			<div class="form-field">
				<label class="title bold">渲染模版</label>
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
</div>
<script>
$(function(){
	$('.toggle-advance').on('click', function(){
		$(".advance").toggle();
	});
});
</script>