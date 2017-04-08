<?php
use fay\helpers\HtmlHelper;
use blog\models\tables\Bills;
?>
<div class="col-2-3">
	<div class="col-right">
		<form method="get" class="validform" id="search-form">
			<div class="mb5">
				<?php echo F::form()->select('user_id', array(''=>'--成员--') + HtmlHelper::getSelectOptions($users, 'id', 'realname'))?>
				|
				<?php echo F::form()->select('type', array(
					''=>'--收支--',
					Bills::TYPE_IN=>'收入',
					Bills::TYPE_OUT=>'支出',
				));?>
				<a href="javascript:;" class="btn-3" id="search-form-submit">查询</a>
			</div>
		</form>
		<table border="0" cellpadding="0" cellspacing="0" class="list-table">
			<thead>
				<tr>
					<th>成员</th>
					<th>进出</th>
					<th>分类</th>
					<th>金额</th>
					<th>余额</th>
					<th>记账时间</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>成员</th>
					<th>进出</th>
					<th>分类</th>
					<th>金额</th>
					<th>余额</th>
					<th>记账时间</th>
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
	<div class="col-left">
		<form id="form" action="<?php echo $this->url('admin/bill/create')?>" method="post" class="validform">
			<div class="form-field">
				<label class="title">成员</label>
				<?php echo F::form()->select('user_id', HtmlHelper::getSelectOptions($users, 'id', 'realname'))?>
			</div>
			<div class="form-field">
				<label class="title">支出/收入</label>
				<?php echo F::form()->inputRadio('type', Bills::TYPE_OUT, array(
					'label'=>'支出'
				), true)?>
				<?php echo F::form()->inputRadio('type', Bills::TYPE_IN, array(
					'label'=>'收入'
				))?>
			</div>
			<div class="form-field">
				<label class="title">分类</label>
				<?php echo F::form()->select('cat_id', HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
					'id'=>'cat_id',
				))?>
			</div>
			<div class="form-field">
				<label class="title">金额</label>
				<?php echo F::form()->inputText('amount', array('ignore'=>false))?>
			</div>
			<div class="form-field" id="prop-values-container">
				<label class="title">描述</label>
				<?php echo F::form()->textarea('description', array(
					'class'=>'full-width h90',
				))?>
			</div>
			<div class="form-field">
				<a href="javascript:;" class="btn-1" id="form-submit">提交</a>
			</div>
		</form>
	</div>
</div>
<script>
$(function(){
	$("input[name='type']").change(function(){
		$.ajax({
			type: "GET",
			url: system.url("admin/bill/get-cats"),
			data: {
				"type":$(this).val()
			},
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#cat_id").html('');
					$.each(resp.data, function(i, data){
						$("#cat_id").append('<option value="'+i+'">'+data+'</option>');
					});
				}else{
					alert(resp.message);
				}
			}
		});
	});
});
</script>