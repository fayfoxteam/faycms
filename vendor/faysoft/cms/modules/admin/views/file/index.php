<?php
use fay\helpers\HtmlHelper;

$cols = F::form('setting')->getData('cols');
?>
<div class="row mb5">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
			文件名：<?php echo F::form('search')->inputText('keywords', array(
				'class'=>'form-control w200',
			));?>
			|
			<?php echo F::form('search')->select('cat_id', array(
				''=>'--分类--',
			) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
				'class'=>'form-control',
			))?>
			|
			<?php echo F::form('search')->select('qiniu', array(
				''=>'--七牛--',
				'1'=>'已上传至七牛',
				'0'=>'未上传至七牛',
			), array(
				'class'=>'form-control',
			))?>
			</div>
			<div>
			上传时间：
			<?php echo F::form('search')->inputText('start_time', array(
				'class'=>'datetimepicker form-control',
			));?>
			-
			<?php echo F::form('search')->inputText('end_time', array(
				'class'=>'datetimepicker form-control',
			));?>
				<a href="javascript:;" class="btn btn-sm" id="search-form-submit">查询</a>
			</div>
		<?php echo F::form('search')->close()?>
	</div>
</div>
<form method="post" action="<?php echo $this->url('admin/file/batch')?>" id="batch-form" class="form-inline">
	<div class="row">
		<div class="col-5"><?php
			echo HtmlHelper::select('', array(
				''=>'批量操作',
				'upload-to-qiniu'=>(F::app()->checkPermission('admin/qiniu/put') && in_array('qiniu', $cols)) ? '上传至七牛' : false,
				'remove-from-qiniu'=>(F::app()->checkPermission('admin/qiniu/delete') && in_array('qiniu', $cols)) ? '从七牛移除' : false,
				'remove'=>F::app()->checkPermission('admin/file/remove') ? '物理删除' : false,
				'exchange'=>F::app()->checkPermission('admin/file/exchange') ? '移动到分类' : false,
			), '', array(
				'class'=>'form-control',
				'id'=>'batch-action',
			));

			echo F::form('search')->select('', array(
				''=>'--分类--',
			) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
				'class'=>'form-control fn-hide',
				'id' => 'cat-id-1',
			));

			echo HtmlHelper::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit',
				'class'=>'btn btn-sm ml5',
			));
		?></div>
		<div class="col-7"><?php $listview->showPager();?></div>
	</div>
	<div class="row">
		<div class="col-12">
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
						<?php if(in_array('cat', $cols)){?>
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
						<?php if(in_array('cat', $cols)){?>
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
		</div>
	</div>
	<div class="row">
		<div class="col-5"><?php
			echo HtmlHelper::select('', array(
				''=>'批量操作',
				'upload-to-qiniu'=>F::app()->checkPermission('admin/qiniu/put') ? '上传至七牛' : false,
				'remove-from-qiniu'=>F::app()->checkPermission('admin/qiniu/delete') ? '从七牛移除' : false,
				'remove'=>F::app()->checkPermission('admin/file/remove') ? '物理删除' : false,
				'exchange'=>F::app()->checkPermission('admin/file/exchange') ? '移动到分类' : false,
			), '', array(
				'class'=>'form-control',
				'id'=>'batch-action-2',
			));

			echo F::form('search')->select('', array(
				''=>'--分类--',
			) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
				'class'=>'form-control fn-hide',
				'id' => 'cat-id-2',
			));
			echo HtmlHelper::link('提交', 'javascript:;', array(
				'id'=>'batch-form-submit-2',
				'class'=>'btn btn-sm ml5',
			));
		?></div>
		<div class="col-7"><?php $listview->showPager();?></div>
	</div>
</form>
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
					common.alert(data.message);
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
				success: function(resp){
					if(resp.status){
						$container.attr('data-qiniu', '1');
						$container.find('.qiniu-uploaded').show();
						$container.find('.loading').hide();
						$container.find('.show-qiniu-file').attr('href', resp.data.url);
					}else{
						common.alert(resp.message);
						$container.find('.qiniu-not-upload').show();
						$container.find('.loading').hide();
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
				success: function(resp){
					if(resp.status){
						$container.attr('data-qiniu', '0');
						$container.find('.qiniu-not-upload').show();
						$container.find('.loading').hide();
						$container.find('.show-qiniu-file').attr('href', '');
					}else{
						common.alert(resp.message);
						$container.find('.qiniu-uploaded').show();
						$container.find('.loading').hide();
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
			
			if(action == 'upload-to-qiniu'){
				$('body').unblock('immediately');
				$('.batch-ids:checked').each(function(){
					id = $(this).val();
					if($('#file-'+id).attr('data-qiniu') == '0'){
						file.qiniu.put(id);
					}
				});
				return false;
			}else if(action == 'remove-from-qiniu'){
				$('body').unblock('immediately');
				$('.batch-ids:checked').each(function(){
					id = $(this).val();
					if($('#file-'+id).attr('data-qiniu') == '1'){
						file.qiniu.remove(id);
					}
				});
				return false;
			}else if(action == 'exchange'){
				if($('#batch-form [name="_submit"]').val() == 'batch-form-submit'){
					$('#batch-form').append('<input type="hidden" name="cat_id" value="'+$('#cat-id-1').val()+'">')
				}else{
					$('#batch-form').append('<input type="hidden" name="cat_id" value="'+$('#cat-id-2').val()+'">')
				}
			}
			return true;
		}).on('change', '#batch-action', function(){
			if($(this) .val() == 'exchange'){
				$('#cat-id-1').removeClass('fn-hide');
			}else{
				$('#cat-id-1').addClass('fn-hide');
			}
		}).on('change', '#batch-action-2', function(){
			if($(this) .val() == 'exchange'){
				$('#cat-id-2').removeClass('fn-hide');
			}else{
				$('#cat-id-2').addClass('fn-hide');
			}
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