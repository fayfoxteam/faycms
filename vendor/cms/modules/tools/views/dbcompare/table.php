<?php
use fay\helpers\Html;
?>
<div class="col-2-1">
	<div class="col-left">
		<h3 class="mb5"><?php echo $db_config['left']['host'], '/', $db_config['left']['dbname']?></h3>
		<table class="list-table">
			<thead><tr>
				<th>Field</th>
				<th>Type</th>
				<th>Null</th>
				<th>Key</th>
				<th>Default</th>
			</tr></thead>
			<tbody>
			<?php
			$i = 0;
			$j = 0;
			foreach($all_fields as $k => $f){
				//同时获取right数据库对应的表（无视字段顺序）
				$right_field = array();
				foreach($right_fields_simple as $key => $n){
					if($f == $n){
						$right_field = $right_fields[$key];
						break;
					}
				}
				
				$border_left = 'pl11';
				if(isset($right_fields_simple[$j]) && $f == $right_fields_simple[$j]){
					$j++;
				}else if(!in_array($f, $right_fields_simple)){
					//新增
					$border_left = 'bl-yellow';
				}else if(in_array($f, array_slice($right_fields_simple, 0, $j))){
					//在right数组的位置比较靠后，说明是位置换了
					$border_left = 'bl-blue';
				}?>
				<tr><?php if(isset($left_fields_simple[$i]) && $f == $left_fields_simple[$i]){?>
					<td class="<?php echo $border_left;?>">
						<strong><?php echo $f?></strong>
					</td>
					<td><span class="<?php if(isset($right_field['Type']) && $right_field['Type'] != $left_fields[$i]['Type']){
						echo 'bg-red';
					}?>"><?php echo $left_fields[$i]['Type']?></span></td>
					<td><span class="<?php if(isset($right_field['Null']) && $right_field['Null'] != $left_fields[$i]['Null']){
						echo 'bg-red';
					}?>"><?php echo $left_fields[$i]['Null']?></span></td>
					<td><span class="<?php if(isset($right_field['Key']) && $right_field['Key'] != $left_fields[$i]['Key']){
						echo 'bg-red';
					}?>"><?php echo $left_fields[$i]['Key']?></span></td>
					<td><span class="<?php if(isset($right_field['Default']) && $right_field['Default'] != $left_fields[$i]['Default']){
						echo 'bg-red';
					}?>"><?php if($left_fields[$i]['Default'] === ''){
						echo 'Empty String';
					}else if($left_fields[$i]['Default'] === null){
						echo 'NULL';
					}else{
						echo $left_fields[$i]['Default'];
					}?></span></td>
					<?php $i++;?>
				<?php }else if(in_array($f, $left_fields_simple)){//位置变了，字段还是在的
					echo '<td class="pl11" colspan="5">&nbsp</td>';
				}else{//字段不存在
					echo '<td class="pl11" colspan="5">&nbsp</td>';
				}?></tr>
			<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-right">
		<h3 class="mb5"><?php echo $db_config['right']['host'], '/', $db_config['right']['dbname']?></h3>
		<table class="list-table">
			<thead><tr>
				<th>Field</th>
				<th>Type</th>
				<th>Null</th>
				<th>Key</th>
				<th>Default</th>
			</tr></thead>
			<tbody>
			<?php
			$i = 0;
			$j = 0;
			foreach($all_fields as $f){
				//同时获取左侧数据库对应的表（无视字段顺序）
				$left_field = array();
				foreach($left_fields_simple as $key => $n){
					if($f == $n){
						$left_field = $left_fields[$key];
						break;
					}
				}
				
				$border_left = 'pl11';
				if(isset($left_fields_simple[$j]) && $f == $left_fields_simple[$j]){
					$j++;
				}else if(!in_array($f, $left_fields_simple)){
					//新增
					$border_left = 'bl-yellow';
				}else if(in_array($f, array_slice($left_fields_simple, $j))){
					//在left数组的位置比较靠后，说明是位置换了
					$border_left = 'bl-blue';
				}?>
				<tr><?php if(isset($right_fields_simple[$i]) && $f == $right_fields_simple[$i]){?>
					<td class="<?php echo $border_left;?>">
						<strong><?php echo $f?></strong>
					</td>
					<td><span class="<?php if(isset($left_field['Type']) && $left_field['Type'] != $right_fields[$i]['Type']){
						echo 'bg-red';
					}?>"><?php echo $right_fields[$i]['Type']?></span></td>
					<td><span class="<?php if(isset($left_field['Null']) && $left_field['Null'] != $right_fields[$i]['Null']){
						echo 'bg-red';
					}?>"><?php echo $right_fields[$i]['Null']?></span></td>
					<td><span class="<?php if(isset($left_field['Key']) && $left_field['Key'] != $right_fields[$i]['Key']){
						echo 'bg-red';
					}?>"><?php echo $right_fields[$i]['Key']?></span></td>
					<td><span class="<?php if(isset($left_field['Default']) && $left_field['Default'] != $right_fields[$i]['Default']){
						echo 'bg-red';
					}?>"><?php if($right_fields[$i]['Default'] === ''){
						echo 'Empty String';
					}else if($right_fields[$i]['Default'] === null){
						echo 'NULL';
					}else{
						echo $right_fields[$i]['Default'];
					}?></span></td>
					<?php $i++;?>
				<?php }else if(in_array($f, $right_fields_simple)){//位置变了，字段还是在的
					echo '<td class="pl11" colspan="5">&nbsp</td>';
				}else{//字段不存在
					echo '<td class="pl11" colspan="5">&nbsp</td>';
				}?></tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<script>
$(function(){
	$(document).on('mouseenter', '.list-table tr', function(){
		var index = $(this).index();
		$('.list-table').each(function(){
			$(this).find('tbody tr:eq('+index+')').addClass('hover').siblings().removeClass('hover');
		});
	}).on('mouseleave', '.list-table tr', function (){
		var index = $(this).index();
		$('.list-table tbody tr:eq('+index+')').each(function(){
			$(this).removeClass('hover');
		});
	});
});
</script>