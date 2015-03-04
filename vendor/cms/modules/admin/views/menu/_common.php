<?php
use fay\helpers\Html;
use fay\models\tables\Menus;

F::form('create')->setModel(Menus::model());
F::form('edit')->setModel(Menus::model());
?>
<div class="hide">
	<div id="edit-cat-dialog" class="common-dialog">
		<div class="common-dialog-content w550">
			<h4>编辑菜单<em>（当前菜单：<span id="edit-cat-title" class="color-orange"></span>）</em></h4>
			<?php echo F::form('edit')->open(array('admin/menu/edit'))?>
				<?php echo Html::inputHidden('id')?>
				<table class="form-table">
					<tr>
						<th class="adaption">标题<em class="color-red">*</em></th>
						<td>
							<?php echo Html::inputText('title')?>
							<span class="color-grey">主显标题</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">二级标题</th>
						<td>
							<?php echo Html::inputText('sub_title')?>
							<span class="color-grey">该字段用途视主题而定</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">别名</th>
						<td>
							<?php echo Html::inputText('alias')?>
							<span class="color-grey">别名用于特殊调用，不可重复，可为空</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">链接地址</th>
						<td>
							<?php echo Html::inputText('link', '', array(
								'class'=>'full-width',
							))?>
							<p class="color-grey">若是本站地址，域名部分用<span class="color-red">{$base_url}</span>代替</p>
							<p class="color-grey">若是外站地址，不要忘了http://</p>
						</td>
					</tr>
					<tr>
						<th class="adaption">排序</th>
						<td>
							<?php echo Html::inputText('sort', '100', array(
								'class'=>'w100',
							))?>
							<span class="color-grey">0-255之间，数值越小，排序越靠前</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">打开方式</th>
						<td>
							<?php echo Html::select('target', array(
								''=>'默认',
								'_blank'=>'_blank — 新窗口或新标签',
								'_top'=>'_top — 不包含框架的当前窗口或标签',
								'_self'=>'_self — 同一窗口或标签',
							))?>
						</td>
					</tr>
					<tr>
						<th class="adaption">父节点</th>
						<td>
							<?php echo Html::select('parent', array(''=>'根节点')+Html::getSelectOptions($menus, 'id', 'title'))?>
						</td>
					</tr>
					<tr>
						<th class="adaption"></th>
						<td>
							<?php echo F::form('edit')->submitLink('编辑菜单', array(
								'class'=>'btn-1',
							))?>
							<a href="javascript:;" class="btn-2 fancybox-close">取消</a>
						</td>
					</tr>
				</table>
			<?php echo F::form('edit')->close()?>
		</div>
	</div>
</div>
<div class="hide">
	<div id="create-cat-dialog" class="common-dialog">
		<div class="common-dialog-content w550">
			<h4>添加子项<em>（父节点：<span id="create-cat-parent" class="color-orange"></span>）</em></h4>
			<?php echo F::form('create')->open(array('admin/menu/create'))?>
				<?php echo Html::inputHidden('parent')?>
				<table class="form-table">
					<tr>
						<th class="adaption">标题<em class="color-red">*</em></th>
						<td>
							<?php echo Html::inputText('title')?>
							<span class="color-grey">主显标题</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">二级标题</th>
						<td>
							<?php echo Html::inputText('sub_title')?>
							<span class="color-grey">该字段用途视主题而定</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">别名</th>
						<td>
							<?php echo Html::inputText('alias')?>
							<span class="color-grey">别名用于特殊调用，不可重复，可为空</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">链接地址</th>
						<td>
							<?php echo Html::inputText('link', '{$base_url}', array(
								'class'=>'full-width',
							))?>
							<p class="color-grey">若是本站地址，域名部分用<span class="color-red">{$base_url}</span>代替</p>
							<p class="color-grey">若是外站地址，不要忘了http://</p>
						</td>
					</tr>
					<tr>
						<th class="adaption">排序</th>
						<td>
							<?php echo Html::inputText('sort', '100', array(
								'class'=>'w100',
							))?>
							<span class="color-grey">0-255之间，数值越小，排序越靠前</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">打开方式</th>
						<td>
							<?php echo Html::select('target', array(
								''=>'默认',
								'_blank'=>'_blank — 新窗口或新标签',
								'_top'=>'_top — 不包含框架的当前窗口或标签',
								'_self'=>'_self — 同一窗口或标签',
							))?>
						</td>
					</tr>
					<tr>
						<th class="adaption"></th>
						<td>
							<?php echo F::form('create')->submitLink('添加新菜单', array(
								'class'=>'btn-1',
							))?>
							<a href="javascript:;" class="btn-2 fancybox-close">取消</a>
						</td>
					</tr>
				</table>
			<?php echo F::form('create')->close()?>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
var cat = {
	'events':function(){
		$(".cat-list-container").delegate('.cat-item-title.parent', 'click', function(){
			if($(this).hasClass("close")){
				$(this).removeClass("close")
					.parent().siblings("ul").slideDown();
			}else{
				$(this).addClass("close")
					.parent().siblings("ul").slideUp();
			}
		});

		$(".edit-sort").feditsort({
			'url':system.url("admin/menu/sort")
		});
	},
	'editCat':function(){
		system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".edit-cat-link").fancybox({
					'padding':0,
					'titleShow':false,
					'centerOnScroll':true,
					'onComplete':function(o){
						$("#edit-cat-form").find(".submit-loading").remove();
						$("#edit-cat-dialog").block({
							'zindex':1200
						});
						$.ajax({
							type: "GET",
							url: system.url("admin/menu/get"),
							data: {"id":$(o).attr('data-id')},
							dataType: "json",
							cache: false,
							success: function(resp){
								$("#edit-cat-dialog").unblock();
								if(resp.status){
									$("#edit-cat-title").text(resp.data.title);
									$("#edit-cat-dialog input[name='id']").val(resp.data.id);
									$("#edit-cat-dialog input[name='title']").val(resp.data.title);
									$("#edit-cat-dialog input[name='sub_title']").val(resp.data.sub_title);
									$("#edit-cat-dialog input[name='alias']").val(resp.data.alias);
									$("#edit-cat-dialog input[name='sort']").val(resp.data.sort);
									$("#edit-cat-dialog input[name='link']").val(resp.data.link);
									$("#edit-cat-dialog select[name='target']").val(resp.data.target);
									$("#edit-cat-dialog select[name='parent']").val(resp.data.parent);
									//父节点不能被挂载到其子节点上
									$("#edit-cat-dialog select[name='parent'] option").attr('disabled', false).each(function(){
										if(system.inArray($(this).attr("value"), resp.children) || $(this).attr("value") == resp.data.id){
											$(this).attr('disabled', 'disabled');
										}
									});
									
								}else{
									alert(resp.message);
								}
							}
						});
					},
					'onClosed':function(o){
						$($(o).attr('href')).find('input,select,textarea').each(function(){
							$(this).poshytip('hide');
						});
					}
				});
			});
		});
	},
	'createCat':function(){
		system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$(".create-cat-link").fancybox({
					'padding':0,
					'titleShow':false,
					'centerOnScroll':true,
					'onStart':function(o){
						$("#create-cat-parent").text($(o).attr("data-title"));
						$("#create-cat-dialog  input[name='parent']").val($(o).attr("data-id"));
					},
					'onClosed':function(o){
						$($(o).attr('href')).find('input,select,textarea').each(function(){
							$(this).poshytip('hide');
						});
					}
				});
			});
		});
	},
	'init':function(){
		this.events();
		this.editCat();
		this.createCat();
	}
};
$(function(){
	cat.init();
})
</script>