<?php
use cms\helpers\ListTableHelper;
use fay\helpers\Html;
use fay\models\tables\Logs;
?>
<div class="col-1">
	<form method="get" class="validform" id="search-form">
		<div class="mb5">
			Code：<?php echo F::form('search')->inputText('code', array(
				'class'=>'w200',
			));?>
			|
			<?php echo F::form('search')->select('type', array(
				''=>'--类型--',
				Logs::TYPE_NORMAL=>'正常',
				Logs::TYPE_ERROR=>'错误',
				Logs::TYPE_WARMING=>'警告',
			))?>
			<a href="javascript:;" class="btn-3" id="search-form-submit">查询</a>
		</div>
	</form>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table">
		<thead>
			<tr>
				<th>Code</th>
				<th>类型</th>
				<th>Data</th>
				<th>用户</th>
				<th class="wp15"><?php echo ListTableHelper::getSortLink('create_time', '生成时间')?></th>
				<th class="wp15">IP</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Code</th>
				<th>类型</th>
				<th>Data</th>
				<th>用户</th>
				<th><?php echo ListTableHelper::getSortLink('create_time', '生成时间')?></th>
				<th>IP</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		$listview->showData();
	?>
		</tbody>
	</table>
	<?php $listview->showPager();?>
</div>
<div class="hide">
	<div id="log-detail-dialog" class="common-dialog">
		<div class="common-dialog-content">
			<h4>日志</h4>
			<table class="form-table">
				<tr>
					<th class="adaption">Code</th>
					<td><?php echo Html::inputText('', '', array(
						'class'=>'w550',
						'id'=>'ld-code',
					))?></td>
				</tr>
				<tr>
					<th valign="top" class="adaption">Data</th>
					<td><?php echo Html::textarea('', '', array(
						'class'=>'w550 h90',
						'rows'=>5,
						'id'=>'ld-data',
					))?></td>
				</tr>
				<tr>
					<th class="adaption">Create Time</th>
					<td>
						<?php echo Html::inputText('', '', array(
							'class'=>'w550',
							'id'=>'ld-create_time',
						))?>
					</td>
				</tr>
				<tr>
					<th class="adaption">User</th>
					<td>
						<?php echo Html::inputText('', '', array(
							'class'=>'w550',
							'id'=>'ld-username',
						))?>
					</td>
				</tr>
				<tr>
					<th class="adaption">User Agent</th>
					<td>
						<?php echo Html::textarea('', '', array(
							'class'=>'w550',
							'id'=>'ld-user_agent',
							'rows'=>2,
						))?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script>
$(function(){
	system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
		system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$(".quick-view").fancybox({
				'padding':0,
				'titleShow':false,
				'centerOnScroll':true,
				'onComplete':function(o){
					$("#log-detail-dialog").block({
						'zindex':1200
					});
					$.ajax({
						type: "GET",
						url: system.url("admin/log/get"),
						data: {"id":$(o).attr('data-id')},
						dataType: "json",
						cache: false,
						success: function(resp){
							$("#log-detail-dialog").unblock();
							if(resp.status){
								$("#ld-code").val(resp.log.code);
								$("#ld-data").val(resp.log.data);
								$("#ld-user_agent").val(resp.log.user_agent);
								$("#ld-create_time").val(system.date(resp.log.create_time));
								if(resp.log.user_id == 0){
									$("#ld-username").val('系统');
								}else{
									$("#ld-username").val(resp.log.username);
								}
							}else{
								alert(resp.message);
							}
						}
					});
				},
				'onClosed':function(){
					$("#log-detail-dialog").unblock();
				}
			});
		});
	});
});
</script>