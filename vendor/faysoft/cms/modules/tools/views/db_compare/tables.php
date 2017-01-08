<?php
use fay\helpers\HtmlHelper;
?>
<div class="row">
	<div class="col-6">
		<table class="list-table" id="left-db-tables">
			<thead><tr>
				<th class="bold"><?php echo $db_config['left']['host'], '/', $db_config['left']['dbname']?></th>
			</tr></thead>
			<tbody>
			<?php foreach($all_tables as $t){?>
			<?php if(in_array($t, $left_tables)){?>
			<tr class="lt-<?php echo $t;?>">
				<td class="<?php echo in_array($t, $right_tables) ? (in_array($t, $diff_tables) ? 'bl-red' : 'pl11') : 'bl-yellow';?>">
					<span class="fr row-actions pt0"><?php
						if(in_array($t, $right_tables)){
							echo HtmlHelper::link('<i class="fa fa-exchange"></i>', array('tools/db-compare/table', array(
								'name'=>$t,
							)), array(
								'title'=>'Table Compare',
								'encode'=>false,
							));
							echo HtmlHelper::link('<i class="fa fa-arrow-right"></i>', '#data-transfer-dialog', array(
								'data-name'=>$t,
								'data-db'=>'left',
								'class'=>'data-transfer',
								'title'=>'Data Transfer',
								'encode'=>false,
							));
						}
						echo HtmlHelper::link('<i class="fa fa-eye"></i>', '#ddl-dialog', array(
							'data-name'=>$t,
							'data-db'=>'left',
							'class'=>'show-ddl',
							'title'=>'DLL',
							'encode'=>false,
						));
					?></span>
					<strong><?php echo $t?></strong>
				</td>
			</tr>
			<?php }else{?>
				<tr><td>&nbsp;</td></tr>
			<?php }?>
			<?php }?>
			</tbody>
		</table>
	</div>
	<div class="col-6">
		<table class="list-table">
			<thead><tr>
				<th class="bold"><?php echo $db_config['right']['host'], '/', $db_config['right']['dbname']?></th>
			</tr></thead>
			<tbody>
			<?php foreach($all_tables as $t){?>
			<?php if(in_array($t, $right_tables)){?>
				<tr class="rt-<?php echo $t;?>">
					<td class="<?php echo in_array($t, $left_tables) ? (in_array($t, $diff_tables) ? 'bl-red' : 'pl11') : 'bl-yellow';?>">
						<span class="fr row-actions pt0"><?php
							if(in_array($t, $left_tables)){
								echo HtmlHelper::link('<i class="fa fa-exchange"></i>', array('tools/db-compare/table', array(
									'name'=>$t,
								)), array(
									'title'=>'Table Compare',
									'encode'=>false,
								));
								echo HtmlHelper::link('<i class="fa fa-arrow-left"></i>', '#data-transfer-dialog', array(
									'data-name'=>$t,
									'data-db'=>'right',
									'class'=>'data-transfer',
									'title'=>'Data Transfer',
									'encode'=>false,
								));
							}
							echo HtmlHelper::link('<i class="fa fa-eye"></i>', '#ddl-dialog', array(
								'data-name'=>$t,
								'data-db'=>'right',
								'class'=>'show-ddl',
								'title'=>'DLL',
								'encode'=>false,
							));
						?></span>
						<strong><?php echo $t?></strong>
					</td>
				</tr>
			<?php }else{?>
				<tr><td>&nbsp;</td></tr>
			<?php }?>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>
<div class="hide">
	<div id="ddl-dialog" class="dialog">
		<div class="dialog-content w800">
			<h4></h4>
			<div class="mb5">
				<h3>Fields</h3>
				<table class="list-table">
					<thead>
						<tr>
							<th>Field</th>
							<th>Type</th>
							<th>Null</th>
							<th>Key</th>
							<th>Default</th>
							<th>Comment</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<h3>DDL</h3>
			<?php echo HtmlHelper::textarea('code', '', array(
				'style'=>'font-family:Consolas,Monaco,monospace',
				'id'=>'ddl-code',
				'class'=>'form-control autosize',
			))?>
		</div>
	</div>
</div>
<div class="hide">
	<div id="data-transfer-dialog" class="dialog">
		<div class="dialog-content w800">
			<h4><span id="db-table-name"></span> (<span class="db-table-from"></span> &gt; <span class="db-table-to"></span>)</h4>
			<form id="transfer-form">
				<input type="hidden" name="from" />
				<input type="hidden" name="name" />
				<div class="mb5">
					<h3>Common Fields</h3>
					<p id="db-table-common-fields"></p>
				</div>
				<div class="mb5" id="db-table-from-separate-fields-container">
					<h3><span class="db-table-from"></span> Separate Fields</h3>
					<p id="db-table-from-separate-fields"></p>
				</div>
				<div class="mb5" id="db-table-to-separate-fields-container">
					<h3><span class="db-table-to"></span> Separate Fields</h3>
					<p id="db-table-to-separate-fields"></p>
				</div>
				<div class="mb5" id="db-table-to-separate-fields-container">
					<h3>TRUNCATE</h3>
					<label><input type="radio" name="truncate" value="1" checked="checked" />YES</label>
					<label><input type="radio" name="truncate" value="0" />NO</label>
				</div>
				<div class="mb5">
					<a href="javascript:;" id="start-transfer" class="btn">Start Transfer</a>
					<span class="fc-red">This process may be longer, please do not close the browser or jump page.</span>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
var db_config = {
	'left':{
		'host':'<?php echo $db_config['left']['host']?>',
		'dbname':'<?php echo $db_config['left']['dbname']?>'
	},
	'right':{
		'host':'<?php echo $db_config['right']['host']?>',
		'dbname':'<?php echo $db_config['right']['dbname']?>'
	}
};

$(function(){
	$(document).on('mouseenter', '.list-table tbody tr', function(){
		var index = $(this).index();
		$('.list-table').each(function(){
			$(this).find('tbody tr:eq('+index+')').addClass('hover').siblings().removeClass('hover');
		});
	}).on('mouseleave', '.list-table tbody tr', function(){
		var index = $(this).index();
		$('.list-table').each(function(){
			$(this).find('tbody tr:eq('+index+')').removeClass('hover');
		});
	}).on('click', '#start-transfer', function(){
		//数据迁移
		$('#data-transfer-dialog').block({
			'zindex':1200
		});
		$.ajax({
			'type': 'GET',
			'url': system.url('tools/db-compare/transfer'),
			'data': $('#transfer-form').serialize(),
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('#data-transfer-dialog').unblock();
				if(resp.status){
					$.fancybox.close();
					alert(resp.message);
				}else{
					alert(resp.message);
				}
			}
		});
	});

	//数据传输
	system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
		system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".data-transfer").fancybox({
				'padding':0,
				'titleShow':false,
				'centerOnScroll':false,
				'hideOnOverlayClick':false,
				'onStart':function(o){
					if($(o).attr('data-db') == 'left'){
						$('#transfer-form [name="from"]').val('left');
						$('.db-table-from').text(db_config.left.host + '/' + db_config.left.dbname);
						$('.db-table-to').text(db_config.right.host + '/' + db_config.right.dbname);
					}else{
						$('#transfer-form [name="from"]').val('right');
						$('.db-table-from').text(db_config.right.host + '/' + db_config.right.dbname);
						$('.db-table-to').text(db_config.left.host + '/' + db_config.left.dbname);
					}
					$('#db-table-name').text($(o).attr('data-name'));
					$('#transfer-form [name="name"]').val($(o).attr('data-name'));
				},
				'onComplete':function(o){
					$('#data-transfer-dialog').block({
						'zindex':1200
					});
					
					$.ajax({
						'type': 'GET',
						'url': system.url('tools/db-compare/get-fields'),
						'data': {
							'name':$(o).attr('data-name')
						},
						'dataType': 'json',
						'cache': false,
						'success': function(resp){
							$('#data-transfer-dialog').unblock();
							if(resp.status){
								if($(o).attr('data-db') == 'left'){
									//点了左侧的传输
									if(resp.data.left.length){
										$('#db-table-from-separate-fields-container').show();
										$('#db-table-from-separate-fields').html(resp.data.left.join(', '));
									}else{
										$('#db-table-from-separate-fields-container').hide();
									}
									if(resp.data.right.length){
										$('#db-table-to-separate-fields-container').show();
										$('#db-table-to-separate-fields').html(resp.data.right.join(', '));
									}else{
										$('#db-table-to-separate-fields-container').hide();
									}
								}else{
									//点了右侧的传输
									if(resp.data.right.length){
										$('#db-table-from-separate-fields-container').show();
										$('#db-table-from-separate-fields').html(resp.data.right.join(', '));
									}else{
										$('#db-table-from-separate-fields-container').hide();
									}
									if(resp.data.left.length){
										$('#db-table-to-separate-fields-container').show();
										$('#db-table-to-separate-fields').html(resp.data.left.join(', '));
									}else{
										$('#db-table-to-separate-fields-container').hide();
									}
								}

								$('#db-table-common-fields').html('');
								$.each(resp.data.common, function(i, data){
									$('#db-table-common-fields').append(['<p><label>',
										'<input type="checkbox" name="fields[]" value="', data,'" checked="checked" />',
										data,
									'</label></p>'].join(''));
								});
								
								$.fancybox.center();
							}
						}
					});
				},
				'onClosed':function(o){
					$('#data-transfer-dialog').unblock();
				}
			});
		});
	});
	
	//dll弹窗
	system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
		system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".show-ddl").fancybox({
				'padding':0,
				'titleShow':false,
				'centerOnScroll':false,
				'onStart':function(o){
					$('#ddl-dialog h4').text($(o).attr('data-name'));
				},
				'onComplete':function(o){
					$('#ddl-dialog').block({
						'zindex':1200
					});
					$.ajax({
						'type': 'GET',
						'url': system.url('tools/db-compare/ddl'),
						'data': {
							'name':$(o).attr('data-name'),
							'db':$(o).attr('data-db')
						},
						'dataType': 'json',
						'cache': false,
						'success': function(resp){
							$('#ddl-dialog').unblock();
							if(resp.status){
								var $tbody = $('#ddl-dialog .list-table tbody');
								$tbody.html('');
								$.each(resp.data.fields, function(i, data){
									$tbody.append(['<tr>',
										'<td>', data.Field, '</td>',
										'<td>', data.Type, '</td>',
										'<td>', data.Null, '</td>',
										'<td>', data.Key, '</td>',
										'<td>', data.Default === null ? 'NULL' : (data.Default === '' ? 'Empty String' : data.Default), '</td>',
										'<td>', data.Comment, '</td>',
									'</tr>'].join(''));
								});
								$('#ddl-code').val(resp.data.ddl);
								autosize.update($('#ddl-code'));
								$tbody.find('tr:even').addClass('alternate');
								$.fancybox.center();
							}
						}
					});
				},
				'onClosed':function(o){
					$('#ddl-dialog').unblock();
				}
			});
		});
	});
});
</script>