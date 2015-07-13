<?php
use fay\models\tables\Users;
use fay\helpers\Html;
?>
<div class="box" id="box-abstract" data-name="abstract">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(F::app()->session->get('role') != Users::ROLE_SUPERADMIN)echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">显示方式</label>
				<?php
					echo F::form('widget')->inputRadio('type', 'by_input', array(
						'label'=>'根据传入ID显示',
					), true);
					echo F::form('widget')->inputRadio('type', 'fixed_post', array(
						'label'=>'固定显示一篇文章',
					));
				?>
			</div>
			<div class="<?php if(isset($config['type']) && $config['type'] == 'fixed_post')echo 'hide'?>" id="type-by-input-options">
				<div class="form-field">
					<label class="title bold">ID字段</label>
					<?php echo F::form('widget')->inputText('id_key', array(
						'class'=>'form-control mw150',
					), 'id')?>
					<p class="fc-grey">URL中的id字段。（此字段为URL重写后的字段，即通过<code>F::input()-&gt;request($key)</code>可以获取到）</p>
				</div>
				<div class="form-field">
					<label class="title bold">所属分类</label>
					<?php echo F::form('widget')->select('under_cat_id', Html::getSelectOptions($cats), array(
						'class'=>'form-control mw400',
					))?>
					<p class="fc-grey">仅搜索此分类及其子分类下的文章，当不同分类对应不同式样时，此选项很有用。</p>
				</div>
				<div class="form-field">
					<label class="title bold">递增阅读数</label>
					<?php
						echo F::form('widget')->inputRadio('inc_views', '1', array(
							'label'=>'递增',
						), true);
						echo F::form('widget')->inputRadio('inc_views', '0', array(
							'label'=>'不递增',
						));
					?>
					<p class="fc-grey">仅搜索此分类及其子分类下的文章，当不同分类对应不同式样时，此选项很有用。</p>
				</div>
			</div>
			<div class="<?php if(!isset($config['type']) || $config['type'] != 'fixed_post')echo 'hide'?>" id="type-fixed-post-options">
				<div class="form-field">
					<label class="title bold">指定文章标题</label>
					<?php
						echo F::form('widget')->inputHidden('fixed_id', array(
							'id'=>'fixed-id',
						));
						echo F::form('widget')->inputText('fixed_title', array(
							'class'=>'form-control mw500',
							'id'=>'fixed-title',
						));
					?>
					<p class="fc-grey">
						固定显示一篇文章，一般用在页面的某一块显示一些固定的描述。
					</p>
				</div>
			</div>
			<div class="form-field">
				<label class="title bold">附加字段</label>
				<?php
					echo F::form('widget')->inputCheckbox('fields[]', 'user', array(
						'label'=>'作者信息',
					), true);
					echo F::form('widget')->inputCheckbox('fields[]', 'nav', array(
						'label'=>'导航（上一篇|下一篇）',
					), true);
					echo F::form('widget')->inputCheckbox('fields[]', 'tags', array(
						'label'=>'标签',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'files', array(
						'label'=>'附件',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'props', array(
						'label'=>'附加属性',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'categories', array(
						'label'=>'附加分类',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'messages', array(
						'label'=>'评论信息',
					));
				?>
				<p class="fc-grey">仅勾选模版中用到的字段，可以加快程序效率。</p>
			</div>
			<div class="form-field">
				<label class="title bold">渲染模版</label>
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
		$('.advance').toggle();
	});
	
	$('input[name="type"]').on('click', function(){
		if($(this).val() == 'by_input'){
			$('#type-by-input-options').show();
			$('#type-fixed-post-options').hide();
		}else{
			$('#type-by-input-options').hide();
			$('#type-fixed-post-options').show();
		}
	});
	
	system.getScript(system.assets('faycms/js/fayfox.autocomplete.js'), function(){
		$('#fixed-title').autocomplete({
			'url' : system.url('admin/post/search'),
			'startSuggestLength':0,
			'onSelect':function(obj, data){
				$('#fixed-id').val(data.id);
			}
		});
	});
});
</script>