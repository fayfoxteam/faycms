<?php
use fay\helpers\Html;
use fay\models\tables\Users;
?>
<div class="box" id="box-abstract" data-name="abstract">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title">分页大小</label>
			<?php echo F::form('widget')->inputText('page_size', array(
				'class'=>'form-control mw150',
			), 10)?>
		</div>
		<div class="form-field">
			<label class="title">页码字段</label>
			<?php echo F::form('widget')->inputText('page_key', array(
				'class'=>'form-control mw150',
			), 'page')?>
		</div>
		<div class="form-field">
			<label class="title">分类字段</label>
			<?php echo F::form('widget')->inputText('cat_key', array(
				'class'=>'form-control mw150',
			), 'cat_id')?>
			<p class="fc-grey">若传入分类字段，会搜索此分类下的文章</p>
		</div>
		<div class="form-field">
			<label class="title">排序规则</label>
			<?php
				echo F::form('widget')->inputRadio('order', 'hand', array(
					'wrapper'=>array(
						'tag'=>'label',
						'wrapper'=>'p',
					),
					'after'=>'置顶+排序值+发布时间倒序（手工排序）',
				), true);
				echo F::form('widget')->inputRadio('order', 'publish_time', array(
					'wrapper'=>array(
						'tag'=>'label',
						'wrapper'=>'p',
					),
					'after'=>'仅发布时间倒序（最新发布）',
				));
				echo F::form('widget')->inputRadio('order', 'views', array(
					'wrapper'=>array(
						'tag'=>'label',
						'wrapper'=>'p',
					),
					'after'=>'阅读数倒序+发布时间倒序（热门文章）',
				));
				echo F::form('widget')->inputRadio('order', 'rand', array(
					'wrapper'=>array(
						'tag'=>'label',
						'wrapper'=>'p',
					),
					'after'=>'随机排序（效率较低）',
				));
			?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
		</div>
		<div class="advance <?php if(F::app()->session->get('role') != Users::ROLE_SUPERADMIN)echo 'hide';?>">
			<div class="form-field">
				<label class="title">发布时间格式</label>
				<?php echo F::form('widget')->inputText('date_format', array(
					'class'=>'form-control mw150',
				))?>
				<p class="fc-grey">若为空，则不显示时间</p>
			</div>
			<div class="form-field">
				<label class="title">链接格式<span class="fc-red">（若非开发人员，请不要修改此配置）</span></label>
				<?php
					echo Html::inputRadio('uri', 'post/{$id}', !isset($data['uri']) || $data['uri'] == 'post/{$id}', array(
						'label'=>'post/{$id}',
					));
					echo Html::inputRadio('uri', 'post-{$id}', isset($data['uri']) && $data['uri'] == 'post-{$id}', array(
						'label'=>'post-{$id}',
					));
					echo Html::inputRadio('uri', '', isset($data['uri']) && !in_array($data['uri'], array(
						'post/{$id}', 'post-{$id}',
					)), array(
						'label'=>'其它',
					));
					echo Html::inputText('other_uri', isset($data['uri']) && !in_array($data['uri'], array(
						'post/{$id}', 'post-{$id}',
					)) ? $data['uri'] : '', array(
						'class'=>'form-control mw150 ib',
					));
				?>
				<p class="fc-grey">
					<code>{$id}</code>代表“文章ID”。
					不要包含base_url部分
				</p>
			</div>
			<div class="form-field">
				<label class="title">渲染模版<span class="fc-red">（若非开发人员，请不要修改此配置）</span></label>
				<?php echo F::form('widget')->textarea('template', array(
					'class'=>'form-control h90 autosize',
				))?>
				<p class="fc-grey mt5">
					若模版内容符合正则<code>/^[\w_-]+\/[\w_-]+\/[\w_-]+$/</code>，
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