<?php
use fay\models\tables\RolesTable;
use fay\helpers\HtmlHelper;
use fay\services\user\UserRoleService;
?>
<div class="box">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title bold">默认显示文章</label>
			<?php
				echo F::form('widget')->inputHidden('default_post_id', array(
					'id'=>'fixed-id',
				));
				echo F::form('widget')->inputText('fixed_title', array(
					'class'=>'form-control mw500',
					'id'=>'fixed-title',
				));
			?>
			<p class="fc-grey">当没有传入ID字段时，默认显示此文章</p>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">ID字段</label>
				<?php echo F::form('widget')->inputText('id_key', array(
					'class'=>'form-control mw150',
				), 'id')?>
				<p class="fc-grey">URL中的id字段。（此字段为URL重写后的字段，即通过<code>F::input()-&gt;get($key)</code>可以获取到）</p>
			</div>
			<div class="form-field">
				<label class="title bold">所属分类</label>
				<?php echo F::form('widget')->select('under_cat_id', HtmlHelper::getSelectOptions($cats), array(
					'class'=>'form-control mw400',
				))?>
				<p class="fc-grey">仅搜索此分类及其子分类下的文章，当不同分类对应不同式样时，此选项可以避免文章在错误的界面显示。</p>
			</div>
			<div class="form-field">
				<label class="title bold">更新访问信息</label>
				<?php
					echo F::form('widget')->inputRadio('inc_views', '1', array(
						'label'=>'更新',
					));
					echo F::form('widget')->inputRadio('inc_views', '0', array(
						'label'=>'不更新',
					));
				?>
				<p class="fc-grey">请求widget的时候同时更新阅读数和最后访问时间。</p>
			</div>
			<div class="form-field">
				<label class="title bold">文章缩略图尺寸</label>
				<?php
				echo F::form('widget')->inputText('post_thumbnail_width', array(
					'placeholder'=>'宽度',
					'class'=>'form-control w100 ib',
				)),
				' x ',
				F::form('widget')->inputText('post_thumbnail_height', array(
					'placeholder'=>'高度',
					'class'=>'form-control w100 ib',
				));
				?>
				<p class="fc-grey">若留空，则返回默认尺寸缩略图。</p>
			</div>
			<div class="form-field">
				<label class="title bold">附加字段</label>
				<?php
					echo F::form('widget')->inputCheckbox('fields[]', 'category', array(
						'label'=>'主分类',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'categories', array(
						'label'=>'附加分类',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'user', array(
						'label'=>'作者信息',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'nav', array(
						'label'=>'导航（上一篇|下一篇）',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'tags', array(
						'label'=>'标签',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'files', array(
						'label'=>'附件',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'props', array(
						'label'=>'附加属性',
					));
					echo F::form('widget')->inputCheckbox('fields[]', 'meta', array(
						'label'=>'计数（评论数/阅读数/点赞数）',
					));
				?>
				<p class="fc-grey">仅勾选模版中用到的字段，可以加快程序效率。</p>
			</div>
			<div class="form-field thumbnail-size-container <?php if(empty($widget->config['fields']) || !in_array('files', $config['fields']))echo 'hide';?>">
				<label class="title bold">附件缩略图尺寸</label>
				<?php
				echo F::form('widget')->inputText('file_thumbnail_width', array(
					'placeholder'=>'宽度',
					'class'=>'form-control w100 ib',
				)),
				' x ',
				F::form('widget')->inputText('file_thumbnail_height', array(
					'placeholder'=>'高度',
					'class'=>'form-control w100 ib',
				));
				?>
				<p class="fc-grey">若留空，则默认为100x100。</p>
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
		$('.advance').toggle();
	});
	
	$('input[name="fields[]"][value="files"]').on('click', function(){
		if($(this).is(':checked')){
			$('.thumbnail-size-container').show();
		}else{
			$('.thumbnail-size-container').hide();
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