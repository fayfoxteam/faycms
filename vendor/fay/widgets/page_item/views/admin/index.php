<?php
use fay\models\tables\Users;
?>
<div class="box" id="box-abstract" data-name="abstract">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title bold">默认显示页面</label>
			<?php
				echo F::form('widget')->inputHidden('default_page_id', array(
					'id'=>'page-id',
				));
				echo F::form('widget')->inputText('page_title', array(
					'class'=>'form-control mw500',
					'id'=>'page-title',
				));
			?>
			<p class="fc-grey">
				固定显示一篇文章，一般用在页面的某一块显示一些固定的描述。
			</p>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(F::app()->session->get('role') != Users::ROLE_SUPERADMIN)echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">ID字段</label>
				<?php echo F::form('widget')->inputText('id_key', array(
					'class'=>'form-control mw150',
				), 'page_id')?>
				<p class="fc-grey">URL中的id字段。（此字段为URL重写后的字段，即通过<code>F::input()-&gt;get($key)</code>可以获取到）</p>
			</div>
			<div class="form-field">
				<label class="title bold">别名字段</label>
				<?php echo F::form('widget')->inputText('alias_key', array(
					'class'=>'form-control mw150',
				), 'page_alias')?>
				<p class="fc-grey">
					若传入分类别名字段，会根据别名获取页面。<br>
					若同时传入ID和分类别名， 则以ID字段为准。
				</p>
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
	
	system.getScript(system.assets('faycms/js/fayfox.autocomplete.js'), function(){
		$('#page-title').autocomplete({
			'url' : system.url('admin/page/search'),
			'startSuggestLength':0,
			'onSelect':function(obj, data){
				$('#page-id').val(data.id);
			}
		});
	});
});
</script>