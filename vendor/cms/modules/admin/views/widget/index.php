<?php
use fay\helpers\Html;
?>
<div class="col-1">
	<table border="0" cellpadding="0" cellspacing="0" class="list-table posts">
		<thead>
			<tr>
				<th>名称</th>
				<th>引用名</th>
				<th>描述</th>
				<th>操作</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>名称</th>
				<th>引用名</th>
				<th>描述</th>
				<th>操作</th>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach($widgets as $w){?>
			<tr>
				<td><strong><?php echo $w->title?></strong></td>
				<td><?php echo $w->name?></td>
				<td><?php echo $w->description?></td>
				<td><?php if(method_exists($w, 'index')){?>
					<a href="#create-instance-dialog" title="<?php echo $w->title?>" class="create-instance-link" data-name="<?php echo $w->name?>">创建实例</a>
				<?php }?></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</div>
<div class="hide">
	<div id="create-instance-dialog" class="common-dialog">
		<div class="common-dialog-content">
			<h4>创建小工具实例</h4>
			<form id="create-instance-form" action="<?php echo $this->url('admin/widget/create-instance')?>" method="post" class="validform">
				<input type="hidden" name="widget_name" id="widget-name" />
				<div class="form-field">
					<label class="title">别名</label>
					<?php echo Html::inputText('alias', '', array(
						'data-rule'=>'string',
						'data-label'=>'别名',
						'data-params'=>'{max:255,format:"alias"}',
						'data-ajax'=>$this->url('admin/widget/is-alias-not-exist'),
						'class'=>'w400',
					))?>
					<p class="description">别名用于调用该widget实例，必须唯一，若为空，则系统会自动生成一个</p>
				</div>
				<div class="form-field">
					<label class="title">描述</label>
					<?php echo Html::textarea('description', '', array(
						'class'=>'w400',
					))?>
				</div>
				<div class="form-field">
					<a href="javascript:;" class="btn-1" id="create-instance-form-submit">创建</a>
					<a href="javascript:;" class="btn-2 fancybox-close">取消</a>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function(){
	system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
		system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".create-instance-link").fancybox({
				'padding':0,
				'centerOnScroll':true,
				'onStart':function(o){
					$("#widget-name").val($(o).attr('data-name'));
				},
				'type' : 'inline',
				'onClosed':function(o){
					$($(o).attr('href')).find('input').each(function(){
						$(this).poshytip('hide');
					});
				}
			});
		});
	});
});
</script>