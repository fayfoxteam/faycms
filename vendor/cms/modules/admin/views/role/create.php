<?php

$action_cat_count = count($actions);
$col_left_count = floor($action_cat_count / 2);
?>
<?php echo F::form()->open()?>
	<div class="col-1">
		<div class="box">
			<div class="box-title">
				<h4>概况</h4>
			</div>
			<div class="box-content">
				<div class="form-field">
					<label class="title">
						角色名称
						<em class="color-red">*</em>
					</label>
					<?php echo F::form()->inputText('title', array(
						'class'=>'w300',
					))?>
				</div>
				<div class="form-field">
					<label class="title">描述</label>
					<?php echo F::form()->textarea('description', array('class'=>'w550 h90'))?>
				</div>
				<div class="form-field">
					<?php echo F::form()->submitLink('添加角色', array(
						'class'=>'btn-1',
					))?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-2-1">
		<div class="col-left">
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
						<span style="width:200px;float:left;" title="<?php echo $a['router']?>">
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
		<div class="col-right">
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
						<span style="width:200px;float:left;" title="<?php echo $a['router']?>">
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
		<div class="clear"></div>
	</div>
<?php echo F::form()->close()?>
<script>
$(function(){
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
});
</script>