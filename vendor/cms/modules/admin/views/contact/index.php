<?php
use fay\helpers\Html;

$cols = F::form('setting')->getData('cols');
?>
<div class="row">
	<div class="col-12">
		<form method="post" id="batch-form" action="<?php echo $this->url('admin/contact/batch')?>">
			<table class="list-table">
				<thead>
					<tr>
						<th class="w20 pl11"><input type="checkbox" class="check-all" /></th>
						<th>留言</th>
						<?php if(in_array('title', $cols)){?>
						<th>标题</th>
						<?php }?>
						<?php if(in_array('reply', $cols)){?>
						<th>回复</th>
						<?php }?>
						<?php if(in_array('name', $cols)){?>
						<th>姓名</th>
						<?php }?>
						<?php if(in_array('email', $cols)){?>
						<th>邮箱</th>
						<?php }?>
						<?php if(in_array('country', $cols)){?>
						<th>国家</th>
						<?php }?>
						<?php if(in_array('phone', $cols)){?>
						<th>电话</th>
						<?php }?>
						<?php if(in_array('create_time', $cols)){?>
						<th>留言时间</th>
						<?php }?>
						<?php if(in_array('area', $cols)){?>
						<th>地域</th>
						<?php }?>
						<?php if(in_array('ip', $cols)){?>
						<th>IP</th>
						<?php }?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="pl11"><input type="checkbox" class="check-all" /></th>
						<th>留言</th>
						<?php if(in_array('title', $cols)){?>
						<th>标题</th>
						<?php }?>
						<?php if(in_array('reply', $cols)){?>
						<th>回复</th>
						<?php }?>
						<?php if(in_array('name', $cols)){?>
						<th>姓名</th>
						<?php }?>
						<?php if(in_array('email', $cols)){?>
						<th>邮箱</th>
						<?php }?>
						<?php if(in_array('country', $cols)){?>
						<th>国家</th>
						<?php }?>
						<?php if(in_array('phone', $cols)){?>
						<th>电话</th>
						<?php }?>
						<?php if(in_array('create_time', $cols)){?>
						<th>留言时间</th>
						<?php }?>
						<?php if(in_array('area', $cols)){?>
						<th>地域</th>
						<?php }?>
						<?php if(in_array('ip', $cols)){?>
						<th>IP</th>
						<?php }?>
					</tr>
				</tfoot>
				<tbody>
					<?php $listview->showData(array(
						'cols'=>$cols,
					));?>
				</tbody>
			</table>
			<?php echo $listview->showPager()?>
			<div class="batch-container mt5">
				<?php echo Html::select('action', array(
					''=>'--批处理--',
					'read'=>'标记为已读',
					'unread'=>'标记为未读',
					'remove'=>'删除',
				), '', array(
					'class'=>'form-control ib mw150',
				))?>
				<a href="javascript:;" class="btn btn-sm" id="batch-form-submit">提交</a>
			</div>
		</form>
	</div>
</div>
<div class="hide">
	<div id="reply-dialog" class="dialog w650">
		<div class="dialog-content">
			<h4>回复给：<span id="reply-to"></span></h4>
			<form id="reply-form" action="<?php echo $this->url('admin/contact/reply')?>">
				<input type="hidden" name="id" value="" />
				<textarea name="reply" class="h200 wp100"></textarea>
				<a href="javascript:;" id="reply-form-submit" class="btn fr mt5 mr10">回复</a>
				<a href="javascript:;" class="btn btn-grey fr fancybox-close mt5 mr10">取消</a>
			</form>
			<br class="clear" />
		</div>
	</div>
</div>
<script>
$(function(){
	$(document).on('change', '.check-all', function(){
		$("[name='ids[]'],.check-all").attr("checked", !!$(this).attr("checked"));
	});
	
	system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
		system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
			$('.reply-link').fancybox({
				'padding':0,
				'titleShow':false,
				'centerOnScroll':true,
				'onComplete':function(o){
					if($(o).attr('data-name')){
						$('#reply-dialog #reply-to').text($(o).attr('data-name'));
					}else if($(o).attr('data-phone')){
						$('#reply-dialog #reply-to').text($(o).attr('data-phone'));
					}else if($(o).attr('data-email')){
						$('#reply-dialog #reply-to').text($(o).attr('data-email'));
					}else{
						$('#reply-dialog #reply-to').text('匿名');
					}

					$('#reply-dialog [name="reply"]').val($(o).attr('data-reply'));
					
					$('#reply-form [name="id"]').val($(o).attr('data-id'));
				}
			});
		});
	});
})
</script>