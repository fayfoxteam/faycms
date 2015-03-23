<?php
use fay\helpers\Html;
use fay\models\tables\Files;

$cols = F::form('setting')->getData('cols');
?>
<div class="col-1">
	<?php echo F::form('search')->open(null, 'get')?>
		<div class="mb5">
		文件名：<?php echo F::form('search')->inputText('keywords', array(
			'class'=>'w200',
		));?>
		|
		<?php echo F::form('search')->select('type', array(
			''=>'--用于--',
			Files::TYPE_POST => '文章',
			Files::TYPE_PAGE => '静态页',
			Files::TYPE_GOODS => '商品',
			Files::TYPE_CAT => '分类插图',
			Files::TYPE_WIDGET => '小工具',
			Files::TYPE_EXAM => '考试系统',
		))?>
		|
		<?php echo F::form('search')->select('qiniu', array(
			''=>'--七牛--',
			'1'=>'已上传至七牛',
			'0'=>'未上传至七牛',
		))?>
		</div>
		<div>
		上传时间：
		<?php echo F::form('search')->inputText('start_time', array(
			'data-rule'=>'datetime',
			'data-label'=>'时间',
			'class'=>'datetimepicker',
		));?>
		-
		<?php echo F::form('search')->inputText('end_time', array(
			'data-rule'=>'datetime',
			'data-label'=>'时间',
			'class'=>'datetimepicker',
		));?>
			<a href="javascript:;" class="btn-3" id="search-form-submit">查询</a>
		</div>
	<?php echo F::form('search')->close()?>
	<form method="post" action="<?php echo $this->url('admin/file/batch')?>" id="batch-form">
		<div class="fl mt5"><?php
			echo Html::select('batch_action', array(
				''=>'批量操作',
				'upload-to-qiniu'=>F::app()->checkPermission('admin/qiniu/put') ? '上传至七牛' : false,
				'remove-from-qiniu'=>F::app()->checkPermission('admin/qiniu/delete') ? '从七牛移除' : false,
				'remove'=>F::app()->checkPermission('admin/file/remove') ? '物理删除' : false,
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn-3 ml5',
			));
		?></div>
		<?php $listview->showPager();?>
		<table class="list-table">
			<thead>
				<tr>
					<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
					<th width="62"></th>
					<th>文件</th>
					<?php if(in_array('qiniu', $cols)){?>
					<th class="w100">七牛</th>
					<?php }?>
					<?php if(in_array('file_type', $cols)){?>
					<th>文件类型</th>
					<?php }?>
					<?php if(in_array('file_path', $cols)){?>
					<th>存储路径</th>
					<?php }?>
					<?php if(in_array('file_size', $cols)){?>
					<th>大小</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>用户</th>
					<?php }?>
					<?php if(in_array('type', $cols)){?>
					<th>用于</th>
					<?php }?>
					<?php if(in_array('downloads', $cols)){?>
					<th>下载次数</th>
					<?php }?>
					<?php if(in_array('upload_time', $cols)){?>
					<th>上传时间</th>
					<?php }?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><input type="checkbox" class="batch-ids-all" /></th>
					<th></th>
					<th>文件</th>
					<?php if(in_array('qiniu', $cols)){?>
					<th>七牛</th>
					<?php }?>
					<?php if(in_array('file_type', $cols)){?>
					<th>文件类型</th>
					<?php }?>
					<?php if(in_array('file_path', $cols)){?>
					<th>存储路径</th>
					<?php }?>
					<?php if(in_array('file_size', $cols)){?>
					<th>大小</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>用户</th>
					<?php }?>
					<?php if(in_array('type', $cols)){?>
					<th>用于</th>
					<?php }?>
					<?php if(in_array('downloads', $cols)){?>
					<th>下载次数</th>
					<?php }?>
					<?php if(in_array('upload_time', $cols)){?>
					<th>上传时间</th>
					<?php }?>
				</tr>
			</tfoot>
			<tbody>
				<?php $listview->showData(array(
					'cols'=>$cols,
					'display_name'=>F::form('setting')->getData('display_name'),
					'display_time'=>F::form('setting')->getData('display_time'),
				));?>
			</tbody>
		</table>
		<div class="fl mt5"><?php
			echo Html::select('batch_action_2', array(
				''=>'批量操作',
				'upload-to-qiniu'=>F::app()->checkPermission('admin/qiniu/put') ? '上传至七牛' : false,
				'remove-from-qiniu'=>F::app()->checkPermission('admin/qiniu/delete') ? '从七牛移除' : false,
				'remove'=>F::app()->checkPermission('admin/file/remove') ? '物理删除' : false,
			));
			echo Html::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn-3 ml5',
			));
		?></div>
	</form>
	<?php $listview->showPager();?>
	<div class="clear"></div>
</div>
<script>
var file = {
	'remove':function(file_id){
		$.ajax({
			type: 'GET',
			url: system.url('admin/file/remove'),
			data: {
				'id':file_id
			},
			dataType: 'json',
			success: function(data){
				if(data.status){
					$('#file-'+file_id+' td').addClass('bg-red').fadeOut('slow');
				}else{
					alert(data.message);
				}
			}
		});
	},
	'qiniu':{
		'put':function(file_id){
			var $container = $('#file-'+file_id);
			$container.find('.qiniu-status').hide();
			$container.find('.loading').show();
			$.ajax({
				type: 'GET',
				url: system.url('admin/qiniu/put'),
				data: {
					'id':file_id
				},
				dataType: 'json',
				success: function(data){
					if(data.status){
						$container.attr('data-qiniu', '1');
						$container.find('.qiniu-uploaded').show();
						$container.find('.loading').hide();
						$container.find('.show-qiniu-file').attr('href', data.url);
					}else{
						alert(data.message);
					}
				}
			});
		},
		'remove':function(file_id){
			var $container = $('#file-'+file_id);
			$container.find('.qiniu-status').hide();
			$container.find('.loading').show();
			$.ajax({
				type: 'GET',
				url: system.url('admin/qiniu/delete'),
				data: {
					'id':file_id
				},
				dataType: 'json',
				success: function(data){
					if(data.status){
						$container.attr('data-qiniu', '0');
						$container.find('.qiniu-not-upload').show();
						$container.find('.loading').hide();
						$container.find('.show-qiniu-file').attr('href', '');
					}else{
						alert(data.message);
					}
				}
			});
		}
	},
	'events':function(){
		$(document).on('click', '.delete-file', function(){
			if(confirm('确定要物理删除该文件吗，删除后将无法恢复')){
				file.remove($(this).attr('data-id'));
			}
			return false;
		}).on('click', '.qiniu-put', function(){
			file.qiniu.put($(this).attr('data-id'));
			return false;
		}).on('click', '.qiniu-delete', function(){
			file.qiniu.remove($(this).attr('data-id'));
			return false;
		});
		$('#batch-form').on('submit', function(){
			var action = $('[name="batch_action"]').val();
			if(!action){
				action = $('[name="batch_action_2"]').val();
			}
			if(action == 'upload-to-qiniu'){
				$('.batch-ids:checked').each(function(){
					id = $(this).val();
					if($('#file-'+id).attr('data-qiniu') == '0'){
						file.qiniu.put(id);
					}
				});
				return false;
			}else if(action == 'remove-from-qiniu'){
				$('.batch-ids:checked').each(function(){
					id = $(this).val();
					if($('#file-'+id).attr('data-qiniu') == '1'){
						file.qiniu.remove(id);
					}
				});
				return false;
			}
			return true;
		});
	},
	'init':function(){
		this.events();
	}
};
$(function(){
	file.init();
});
</script>