<?php
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<table class="list-table">
			<thead>
				<tr>
					<th>名称</th>
					<th>描述</th>
					<th>引用名</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>名称</th>
					<th>描述</th>
					<th>引用名</th>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach($widgets as $w){?>
				<tr>
					<td>
						<strong><?php echo $w->title?></strong>
						<div class="row-actions"><?php
						if(method_exists($w, 'index')){
							echo Html::link('创建实例', '#create-instance-dialog', array(
								'title'=>$w->title,
								'class'=>'create-instance-link',
								'data-name'=>$w->name
							));
						}
						?></div>
					</td>
					<td><?php echo $w->description?></td>
					<td><?php echo $w->name?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<div class="hide">
	<div id="create-instance-dialog" class="dialog">
		<div class="dialog-content">
			<h4>创建小工具实例</h4>
			<form id="create-instance-form" action="<?php echo $this->url('admin/widget/create-instance')?>" method="post" class="validform">
				<input type="hidden" name="widget_name" id="widget-name" />
				<div class="form-field">
					<label class="title">描述</label>
					<?php echo Html::inputText('description', '', array(
						'class'=>'form-control w400',
					))?>
				</div>
				<div class="form-field">
					<label class="title">所属域</label>
					<?php echo Html::select('widgetarea', array(''=>'--所属小工具域--') + $widgetareas, '', array(
						'class'=>'form-control',
					))?>
					<p class="description">别名用于调用该widget实例，必须唯一，若为空，则系统会自动生成一个</p>
				</div>
				<div class="form-field">
					<label class="title">别名</label>
					<?php echo Html::inputText('alias', '', array(
						'data-rule'=>'string',
						'data-label'=>'别名',
						'data-params'=>'{max:255,format:\'alias\'}',
						'data-ajax'=>$this->url('admin/widget/is-alias-not-exist'),
						'class'=>'form-control w400',
					))?>
					<p class="description">别名用于调用该widget实例，必须唯一，若为空，则系统会自动生成一个</p>
				</div>
				<div class="form-field">
					<a href="javascript:;" class="btn" id="create-instance-form-submit">创建</a>
					<a href="javascript:;" class="btn btn-grey fancybox-close">取消</a>
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