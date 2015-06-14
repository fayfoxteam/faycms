<?php
use cms\helpers\ListTableHelper;
use fay\helpers\Html;
use fay\models\tables\Logs;
?>
<div class="row">
	<div class="col-7">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				Code：<?php echo F::form('search')->inputText('code', array(
					'class'=>'form-control',
				));?>
				|
				<?php echo F::form('search')->select('type', array(
					''=>'--类型--',
					Logs::TYPE_NORMAL=>'正常',
					Logs::TYPE_ERROR=>'错误',
					Logs::TYPE_WARMING=>'警告',
				), array(
					'class'=>'form-control',
				))?>
				<a href="javascript:;" class="btn btn-sm" id="search-form-submit">查询</a>
			</div>
		<?php echo F::form('search')->close()?>
	</div>
	<div class="col-5">
		<?php $listview->showPager()?>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<table class="list-table">
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
				<?php $listview->showData()?>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<?php $listview->showPager()?>
	</div>
</div>
<div class="hide">
	<div id="log-detail-dialog" class="dialog">
		<div class="dialog-content w600">
			<h4>日志</h4>
			<table class="form-table">
				<tr>
					<th class="adaption">Code</th>
					<td><?php echo Html::inputText('', '', array(
						'class'=>'form-control',
						'id'=>'ld-code',
					))?></td>
				</tr>
				<tr>
					<th valign="top" class="adaption">Data</th>
					<td><?php echo Html::textarea('', '', array(
						'class'=>'form-control h90 autosize',
						'rows'=>5,
						'id'=>'ld-data',
					))?></td>
				</tr>
				<tr>
					<th class="adaption">Create Time</th>
					<td>
						<?php echo Html::inputText('', '', array(
							'class'=>'form-control',
							'id'=>'ld-create_time',
						))?>
					</td>
				</tr>
				<tr>
					<th class="adaption">User</th>
					<td>
						<?php echo Html::inputText('', '', array(
							'class'=>'form-control',
							'id'=>'ld-username',
						))?>
					</td>
				</tr>
				<tr>
					<th class="adaption">User Agent</th>
					<td>
						<?php echo Html::textarea('', '', array(
							'class'=>'form-control autosize',
							'id'=>'ld-user_agent',
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
								$("#ld-data").val(resp.log.data).trigger('autosize.resize');
								$("#ld-user_agent").val(resp.log.user_agent).trigger('autosize.resize');
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