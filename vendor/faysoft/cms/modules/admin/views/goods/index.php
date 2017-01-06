<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\Goods;

$cols = F::form('setting')->getData('cols', array());
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
				) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
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
					<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
					<?php
					if(in_array('id', $cols)){
						echo HtmlHelper::tag('th', array(), '商品ID');
					}
					if(in_array('thumbnail', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w50',
						), '商品图');
					}
					echo HtmlHelper::tag('th', array(), '商品名称');
					if(in_array('sn', $cols)){
						echo HtmlHelper::tag('th', array(), '货号');
					}
					if(in_array('category', $cols)){
						echo HtmlHelper::tag('th', array(), '分类');
					}
					if(in_array('user', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w115',
						), '用户');
					}
					if(in_array('price', $cols)){
						echo HtmlHelper::tag('th', array(), '价格');
					}
					if(in_array('is_new', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w35',
						), '新品');
					}
					if(in_array('is_hot', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w35',
						), '热销');
					}
					if(in_array('views', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w70',
						), '浏览量');
					}
					if(in_array('sales', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w70',
						), '销量');
					}
					if(in_array('comments', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w70',
						), '评论数');
					}
					if(in_array('status', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w70',
						), '状态');
					}
					if(in_array('sort', $cols)){
						echo HtmlHelper::tag('th', array(
							'class'=>'w115',
						), '排序值');
					}
					if(in_array('publish_time', $cols)){
						echo HtmlHelper::tag('th', array(), '发布时间');
					}
					if(in_array('last_modified_time', $cols)){
						echo HtmlHelper::tag('th', array(), '最后修改时间');
					}
					if(in_array('create_time', $cols)){
						echo HtmlHelper::tag('th', array(), '创建时间');
					}
					?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="w20"><input type="checkbox" class="batch-ids-all" /></th>
					<?php
					if(in_array('id', $cols)){
						echo HtmlHelper::tag('th', array(), '商品ID');
					}
					if(in_array('thumbnail', $cols)){
						echo HtmlHelper::tag('th', array(), '商品图');
					}
					echo HtmlHelper::tag('th', array(), '商品名称');
					if(in_array('sn', $cols)){
						echo HtmlHelper::tag('th', array(), '货号');
					}
					if(in_array('category', $cols)){
						echo HtmlHelper::tag('th', array(), '分类');
					}
					if(in_array('user', $cols)){
						echo HtmlHelper::tag('th', array(), '用户');
					}
					if(in_array('price', $cols)){
						echo HtmlHelper::tag('th', array(), '价格');
					}
					if(in_array('is_new', $cols)){
						echo HtmlHelper::tag('th', array(), '新品');
					}
					if(in_array('is_hot', $cols)){
						echo HtmlHelper::tag('th', array(), '热销');
					}
					if(in_array('views', $cols)){
						echo HtmlHelper::tag('th', array(), '浏览量');
					}
					if(in_array('sales', $cols)){
						echo HtmlHelper::tag('th', array(), '销量');
					}
					if(in_array('comments', $cols)){
						echo HtmlHelper::tag('th', array(), '评论数');
					}
					if(in_array('status', $cols)){
						echo HtmlHelper::tag('th', array(), '状态');
					}
					if(in_array('sort', $cols)){
						echo HtmlHelper::tag('th', array(), '排序值');
					}
					if(in_array('publish_time', $cols)){
						echo HtmlHelper::tag('th', array(), '发布时间');
					}
					if(in_array('last_modified_time', $cols)){
						echo HtmlHelper::tag('th', array(), '最后修改时间');
					}
					if(in_array('create_time', $cols)){
						echo HtmlHelper::tag('th', array(), '创建时间');
					}
					?>
				</tr>
			</tfoot>
			<tbody><?php
				$listview->showData(array(
					'cols'=>$cols,
				));
			?></tbody>
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
						.addClass(resp.data.is_new == 1 ? "tick-circle" : "cross-circle")
						.show()
						.next("img").remove();
				}else{
					common.alert(resp.message);
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
						.addClass(resp.data.is_hot == 1 ? "tick-circle" : "cross-circle")
						.show()
						.next("img").remove();
				}else{
					common.alert(resp.message);
				}
			}
		});
	});

	$(".edit-sort").feditsort({
		'url':system.url("admin/goods/set-sort")
	});
});
</script>