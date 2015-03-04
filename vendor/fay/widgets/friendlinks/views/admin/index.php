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
			<label class="title">标题</label>
			<?php echo Html::inputText('title', isset($data['title']) ? $data['title'] : '')?>
			<p class="color-grey">若为空，默认为“友情链接”</p>
		</div>
		<div class="form-field">
			<label class="title">显示链接数</label>
			<?php echo Html::inputText('number', isset($data['number']) ? $data['number'] : '5')?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
		</div>
		<div class="advance <?php if(F::app()->session->get('role') != Users::ROLE_SUPERADMIN)echo 'hide';?>">
			<div class="form-field">
				<label class="title">渲染模版<span class="color-red">（若非开发人员，请不要修改此配置）</span></label>
				<?php echo Html::textarea('template', isset($data['template']) ? $data['template'] : '', array(
					'class'=>'wp90 h200',
				))?>
				<p class="color-grey">
					若模版内容符合正则<span class="color-orange">/^[\w_-]+\/[\w_-]+\/[\w_-]+$/</span>，
					即类似<span class="color-orange">frontend<span class="color-green">/</span>widget<span class="color-green">/</span>template</span><br />
					则会调用当前application下符合该相对路径的view文件。<br />
					否则视为php代码eval执行。若留空，会调用默认模版。
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