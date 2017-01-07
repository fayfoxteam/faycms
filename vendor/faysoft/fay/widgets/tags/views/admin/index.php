<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\RolesTable;
use fay\services\user\UserRoleService;

/**
 * @var $widget \fay\widgets\tags\controllers\IndexController
 */
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
			<label class="title bold">排序规则</label>
			<?php
			echo F::form('widget')->inputRadio('order', 'sort', array(
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
				'after'=>'排序值+添加时间倒序（手工排序）',
			), true);
			echo F::form('widget')->inputRadio('order', 'posts', array(
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
				'after'=>'文章数排序',
			));
			echo F::form('widget')->inputRadio('order', 'create_time', array(
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
				'after'=>'id倒序（添加时间倒序）',
			));
			?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">链接格式</label>
				<?php
					echo HtmlHelper::inputRadio('uri', 'tag/{$title}', !isset($widget->config['uri']) || $widget->config['uri'] == 'tag/{$title}', array(
						'label'=>'tag/{$title}',
					));
					echo HtmlHelper::inputRadio('uri', 'tag/{$id}', isset($widget->config['uri']) && $widget->config['uri'] == 'tag/{$id}', array(
						'label'=>'tag/{$id}',
					));
					echo HtmlHelper::inputRadio('uri', '', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
						'tag/{$id}', 'tag/{$title}',
					)), array(
						'label'=>'其它',
					));
					echo HtmlHelper::inputText('other_uri', isset($widget->config['uri']) && !in_array($widget->config['uri'], array(
						'tag/{$id}', 'tag/{$title}',
					)) ? $widget->config['uri'] : '', array(
						'class'=>'form-control mw150 ib',
					));
				?>
				<p class="fc-grey">
					<code>{$id}</code>代表“标签ID”。
					<code>{$title}</code>代表“标签名称”。
					不要包含base_url部分。<br>
					<span class="fc-orange">此配置项是否生效取决于模版代码</span>
				</p>
			</div>
			<div class="form-field">
				<label class="title bold">渲染模版</label>
				<?php echo F::form('widget')->textarea('template', array(
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