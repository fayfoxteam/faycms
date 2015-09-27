<?php
use fay\helpers\Html;
use fay\models\tables\Goods;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				<?php echo F::form('search')->select('field', array(
					'title'=>'商品名称',
					'sn'=>'货号',
				), array(
					'class'=>'form-control',
				))?>
				<?php echo F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				));?>
				|
				<?php echo F::form('search')->select('cat_id', array(
					''=>'--分类--',
				) + Html::getSelectOptions($cats, 'id', 'title'), array(
					'class'=>'form-control',
				))?>
				|
				<?php echo F::form('search')->select('status', array(
					''=>'--状态--',
					Goods::STATUS_INSTOCK=>'在库',
					Goods::STATUS_ONSALE=>'销售中',
				), array(
					'class'=>'form-control',
				))?>
			</div>
			<div class="mb5">
				<?php echo F::form('search')->select('time_field', array(
					'create_time'=>'创建时间',
					'last_modified_time'=>'最后修改时间',
					'publish_time'=>'发布时间',
				), array(
					'class'=>'form-control',
				))?>
				<?php echo F::form('search')->inputText('start_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'时间',
					'class'=>'form-control datetimepicker',
				));?>
				-
				<?php echo F::form('search')->inputText('end_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'时间',
					'class'=>'form-control datetimepicker',
				));?>
				<?php echo F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
		<table class="list-table goods-list">
			<thead>
				<tr>
					<th width="62"></th>
					<th>商品名称</th>
					<th>货号</th>
					<th>分类</th>
					<th>价格</th>
					<th class="w35">新品</th>
					<th class="w35">热销</th>
					<th class="w70">状态</th>
					<th class="w70">排序值</th>
					<th>创建时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th></th>
					<th>商品名称</th>
					<th>货号</th>
					<th>分类</th>
					<th>价格</th>
					<th>新品</th>
					<th>热销</th>
					<th>状态</th>
					<th>排序值</th>
					<th>创建时间</th>
				</tr>
			</tfoot>
			<tbody>
				<?php $listview->showData();?>
			</tbody>
		</table>
		<?php $listview->showPager();?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
	$(".goods-list").delegate(".is-new-link", "click", function(){
		var o = this;
		$(this).hide().after('<img src="'+system.assets('images/throbber.gif')+'" />');
		$.ajax({
			type: "GET",
			url: system.url("admin/goods/set-is-new"),
			data: {
				"id":$(this).attr("data-id"),
				"is_new":$(this).hasClass("tick-circle") ? 0 : 1
			},
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$(o).removeClass("tick-circle")
						.removeClass("cross-circle")
						.addClass(resp.is_new == 1 ? "tick-circle" : "cross-circle")
						.show()
						.next("img").remove();
				}else{
					alert(resp.message);
				}
			}
		});
	});

	$(".goods-list").delegate(".is-hot-link", "click", function(){
		var o = this;
		$(this).hide().after('<img src="'+system.assets('images/throbber.gif')+'" />');
		$.ajax({
			type: "GET",
			url: system.url("admin/goods/set-is-hot"),
			data: {
				"id":$(this).attr("data-id"),
				"is_hot":$(this).hasClass("tick-circle") ? 0 : 1
			},
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$(o).removeClass("tick-circle")
						.removeClass("cross-circle")
						.addClass(resp.is_hot == 1 ? "tick-circle" : "cross-circle")
						.show()
						.next("img").remove();
				}else{
					alert(resp.message);
				}
			}
		});
	});

	$(".edit-sort").feditsort({
		'url':system.url("admin/goods/set-sort")
	});
});
</script>