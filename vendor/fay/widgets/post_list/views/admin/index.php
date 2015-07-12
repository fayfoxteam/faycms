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
			<label class="title bold">分页大小</label>
			<?php echo F::form('widget')->inputText('page_size', array(
				'class'=>'form-control mw150',
			), 10)?>
		</div>
		<div class="form-field">
			<label class="title bold">排序规则</label>
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
			?>
		</div>
		<div class="form-field">
			<a href="javascript:;" class="toggle-advance" style="text-decoration:underline;">高级设置</a>
			<span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
		</div>
		<div class="advance <?php if(F::app()->session->get('role') != Users::ROLE_SUPERADMIN)echo 'hide';?>">
			<div class="form-field">
				<label class="title bold">页码字段</label>
				<?php echo F::form('widget')->inputText('page_key', array(
					'class'=>'form-control mw150',
				), 'page')?>
			</div>
			<div class="form-field">
				<label class="title bold">分类字段</label>
				<?php echo F::form('widget')->inputText('cat_key', array(
					'class'=>'form-control mw150',
				), 'cat_id')?>
				<p class="fc-grey">若传入分类字段，会搜索此分类下的文章</p>
			</div>
			<div class="form-field">
				<label class="title bold">发布时间格式</label>
				<?php echo F::form('widget')->inputText('date_format', array(
					'class'=>'form-control mw150',
				), 'pretty')?>
				<p class="fc-grey">若为空，则不显示时间；若为pretty，则会显示“1天前”这样的时间格式；<br>
					其他格式视为PHP date函数的第一个参数</p>
			</div>
			<div class="form-field">
				<label class="title bold">链接格式</label>
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
				<label class="title bold">附加字段</label>
				<?php
					echo F::form('widget')->inputCheckbox('fields[]', 'cat', array(
						'label'=>'分类详情',
					), true);
					echo F::form('widget')->inputCheckbox('fields[]', 'user', array(
						'label'=>'作者信息',
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
			<div class="form-field">
				<label class="title bold">无内容时显示的替换文本</label>
				<?php echo F::form('widget')->textarea('empty_text', array(
					'class'=>'form-control h90 autosize',
				), '无相关记录！')?>
				<p class="fc-grey">可以包含html</p>
			</div>
			<div class="form-field">
				<label class="title bold">分页条模版</label>
				<p><?php
					echo Html::inputRadio('pager', 'system', !isset($data['pager']) || $data['pager'] == 'system', array(
						'label'=>'调用全局分页条',
					));
					echo Html::inputRadio('pager', 'custom', isset($data['pager']) && $data['pager'] == 'custom', array(
						'label'=>'小工具内自定义',
					));
				?></p>
				<div id="pager-template-container" class="<?php if(!isset($data['pager']) || $data['pager'] == 'system')echo 'hide';?>">
					<?php echo F::form('widget')->textarea('pager_template', array(
						'class'=>'form-control h90 autosize',
					))?>
					<p class="fc-grey mt5">
						若模版内容符合正则<code>/^[\w_-]+\/[\w_-]+\/[\w_-]+$/</code>，
						即类似<code>frontend/widget/pager</code><br />
						则会调用当前application下符合该相对路径的view文件。<br />
						否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	$('.toggle-advance').on('click', function(){
		$(".advance").toggle();
	});

	$('input[name="pager"]').on('click', function(){
		if($(this).val() == 'custom'){
			$('#pager-template-container').show();
		}else{
			$('#pager-template-container').hide();
		}
	});
});
</script>