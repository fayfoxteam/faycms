<?php
use fay\helpers\Html;
use fay\models\tables\Roles;
?>
<div class="box" id="box-abstract" data-name="abstract">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title bold">标题</label>
			<?php echo Html::inputText('title', isset($data['title']) ? $data['title'] : '', array(
				'class'=>'form-control mw400',
			))?>
			<p class="fc-grey">若为空，则显示顶级分类的标题</p>
		</div>
		<div class="form-field">
			<label class="title bold">顶级分类</label>
			<?php echo Html::select('top', Html::getSelectOptions($cats), isset($data['top']) ? $data['top'] : 0, array(
				'class'=>'form-control mw400',
			))?>
			<p class="fc-grey">仅显示所选分类的子分类（不包含所选分类本身）</p>
		</div>
		<div class="form-field">
			<label class="title bold">是否体现层级关系</label>
			<?php echo Html::inputRadio('hierarchical', 1, !empty($data['hierarchical']), array(
				'label'=>'是',
			))?>
			<?php echo Html::inputRadio('hierarchical', 0, empty($data['hierarchical']),  array(
				'label'=>'否',
			), true)?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(!in_array(Roles::ITEM_SUPER_ADMIN, F::session()->get('roles')))echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">链接格式</label>
				<?php
					echo Html::inputRadio('uri', 'cat/{$id}', !isset($data['uri']) || $data['uri'] == 'cat/{$id}', array(
						'label'=>'cat/{$id}',
					));
					echo Html::inputRadio('uri', 'cat/{$alias}', isset($data['uri']) && $data['uri'] == 'cat/{$alias}', array(
						'label'=>'cat/{$alias}',
					));
					echo Html::inputRadio('uri', 'cat-{$id}', isset($data['uri']) && $data['uri'] == 'cat-{$id}', array(
						'label'=>'cat-{$id}',
					));
					echo Html::inputRadio('uri', 'cat-{$alias}', isset($data['uri']) && $data['uri'] == 'cat-{$alias}', array(
						'label'=>'cat-{$alias}',
					));
					echo Html::inputRadio('uri', '', isset($data['uri']) &&!in_array($data['uri'], array(
						'cat/{$id}', 'cat/{$alias}', 'cat-{$id}', 'cat-{$alias}',
					)), array(
						'label'=>'其它',
					));
					echo Html::inputText('other_uri', isset($data['uri']) &&!in_array($data['uri'], array(
						'cat/{$id}', 'cat/{$alias}', 'cat-{$id}', 'cat-{$alias}',
					)) ? $data['uri'] : '', array(
						'class'=>'form-control mw150 ib',
					));
				?>
				<p class="fc-grey">
					<code>{$id}</code>代表“分类ID”。
					<code>{$alias}</code>代表“分类别名”。
					不要包含base_url部分。<br>
					<span class="fc-orange">此配置项是否生效取决于模版代码</span>
				</p>
			</div>
			<div class="form-field">
				<label class="title bold">渲染模版</label>
				<?php echo Html::textarea('template', isset($data['template']) ? $data['template'] : '', array(
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