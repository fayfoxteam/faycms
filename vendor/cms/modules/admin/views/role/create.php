<?php
$action_cat_count = count($actions);
$col_left_count = floor($action_cat_count / 2);
?>
<?php echo F::form()->open()?>
<div class="row">
	<div class="col-12">
		<div class="box">
			<div class="box-title">
				<h4>概况</h4>
			</div>
			<div class="box-content">
				<div class="form-field">
					<label class="title bold">
						角色名称
						<em class="required">*</em>
					</label>
					<?php echo F::form()->inputText('title', array(
						'class'=>'form-control mw500',
					))?>
				</div>
				<div class="form-field">
					<label class="title bold">描述</label>
					<?php echo F::form()->textarea('description', array(
						'class'=>'form-control h90 mw500 autosize',
					))?>
				</div>
				<div class="form-field">
					<?php echo F::form()->submitLink('添加角色', array(
						'class'=>'btn',
					))?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="tabbable">
			<ul class="nav-tabs">
				<li class="active"><a href="#action-panel">访问权限</a></li>
				<li><a href="#cats-panel">分类权限</a></li>
			</ul>
			<div class="tab-content">
				<div id="action-panel" class="tab-pane p5">
					<?php $this->renderPartial('_action_panel', array(
						'col_left_count'=>$col_left_count,
					))?>
				</div>
				<div id="cats-panel" class="tab-pane p5">
					<?php $this->renderPartial('_cat_panel', array(
						'cats'=>$cats,
					))?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo F::form()->close()?>
<script>
var role = {
	'events':function(){
		$(".select-all").change(function(){
			if($(this).attr("checked")){
				$(this).parent().parent().next(".box-content").find("input[type='checkbox']").attr("checked", "checked");
			}else{
				$(this).parent().parent().next(".box-content").find("input[type='checkbox']").attr("checked", false).attr("disabled", false);
				$(this).parent().parent().next(".box-content").find("input[type='hidden']").remove();
			}
		});
		//父节点必选
		$("input[name='actions[]']").change(function(){
			var $parent = $("input[type='checkbox'][name='actions[]'][value='"+$(this).attr("parent")+"']");
			if($(this).attr("checked")){
				$parent.attr("checked", "checked").attr("disabled", "disabled").change();
				if(!$parent.next("input[type='hidden']").length){
					$parent.after('<input type="hidden" name="actions[]" value="'+$(this).attr("parent")+'" />');
				}
			}else{
				if($("input[name='actions[]'][parent='"+$(this).attr("parent")+"']:checked").length == 0){
					$parent.removeAttr("disabled");
					$parent.next("input[type='hidden']").remove();
				}
			}
		});

		$(".tree-container").on('click', '.leaf-title.parent', function(){
			$li = $(this).parent().parent();
			if($li.hasClass("close")){
				$li.children('ul').slideDown(function(){
					$li.removeClass("close");
				});
			}else{
				$li.children('ul').slideUp(function(){
					$li.addClass("close");
				});
			}
		}).on('click', '.select-all-children', function(){
			$(this).parent().parent().parent().find('input[name="role_cats[]"]').attr('checked', 'checked');
			return false;
		});
	},
	'init':function(){
		//初始化父级必选项
		$("input[type='checkbox'][name='actions[]'][parent!=0]:checked").each(function(){
			var $parent = $("input[type='checkbox'][name='actions[]'][value='"+$(this).attr("parent")+"']");
			$parent.attr("checked", "checked").attr("disabled", "disabled");
			if(!$parent.next("input[type='hidden']").length){
				$parent.after('<input type="hidden" name="actions[]" value="'+$(this).attr("parent")+'" />');
			}
		});
		
		this.events();
	}
};
$(function(){
	role.init();
});
</script>