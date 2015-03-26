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
					<label class="title">
						角色名称
						<em class="required">*</em>
					</label>
					<?php echo F::form()->inputText('title', array(
						'class'=>'form-control mw500',
					))?>
				</div>
				<div class="form-field">
					<label class="title">描述</label>
					<?php echo F::form()->textarea('description', array(
						'class'=>'form-control h90 mw500 autosize',
					))?>
				</div>
				<div class="form-field">
					<?php echo F::form()->submitLink('更新角色', array(
						'class'=>'btn',
					))?>
					<a href="javascript:;" id="form-reset" class="btn-2">重置</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="col-content">
		<?php $i = 0;
			foreach($actions as $cat_title => $action){
				$i++;
				if($i > $col_left_count)continue;
		?>
			<div class="box">
				<div class="box-title">
					<h4><input type="checkbox" class="select-all" title="全选" /><?php echo $cat_title?></h4>
				</div>
				<div class="box-content">
				<?php foreach($action as $a){?>
					<span class="w200 ib" title="<?php echo $a['router']?>">
						<?php echo F::form()->inputCheckbox('actions[]', $a['id'], array(
							'label'=>$a['title'],
							'parent'=>$a['parent'],
						))?>
					</span>
				<?php }?>
					<div class="clear"></div>
				</div>
			</div>
		<?php }?>
		</div>
	</div>
	<div class="col-6">
		<div class="col-content">
		<?php $i = 0;
			foreach($actions as $cat_title => $action){
				$i++;
				if($i <= $col_left_count)continue;
		?>
			<div class="box">
				<div class="box-title">
					<h4><input type="checkbox" class="select-all" title="全选" /><?php echo $cat_title?></h4>
				</div>
				<div class="box-content">
				<?php foreach($action as $a){?>
					<span class="w200 ib" title="<?php echo $a['router']?>">
						<?php echo F::form()->inputCheckbox('actions[]', $a['id'], array(
							'label'=>$a['title'],
							'parent'=>$a['parent'],
						))?>
					</span>
				<?php }?>
					<div class="clear"></div>
				</div>
			</div>
		<?php }?>
		</div>
	</div>
</div>
<?php echo F::form()->close()?>
<script>
$(function(){
	//初始化父级必选项
	$("input[type='checkbox'][name='actions[]'][parent!=0]:checked").each(function(){
		var $parent = $("input[type='checkbox'][name='actions[]'][value='"+$(this).attr("parent")+"']");
		$parent.attr("checked", "checked").attr("disabled", "disabled");
		if(!$parent.next("input[type='hidden']").length){
			$parent.after('<input type="hidden" name="actions[]" value="'+$(this).attr("parent")+'" />');
		}
	});
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
			$parent.attr("checked", "checked").attr("disabled", "disabled").change();;
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
});
</script>