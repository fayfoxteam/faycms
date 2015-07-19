<?php
use fay\helpers\Html;
use w\models\tables\sci;
?>
<div class="col-2-3">
	<div class="col-right">
		<form method="get" class="form-inline validform" id="search-form">
			<div class="mb5">
                期刊名称：<?php echo F::form('search')->inputText('name', array(
                    'class'=>'form-control w200',
                ));?>
                期刊简称：<?php echo F::form('search')->inputText('short_name', array(
                    'class'=>'form-control w200',
                ));?>
                研究方向：<?php echo F::form('search')->inputText('research_dir', array(
                    'class'=>'form-control w200',
                ));?>
				<a href="javascript:;" class="btn btn-sm" id="search-form-submit">查询</a>
			</div>
		</form>
		<table border="0" cellpadding="0" cellspacing="0" class="list-table">
			<thead>
				<tr>
                    <th>ID</th>
					<th>名称</th>
					<th>简称</th>
					<th>ISSN</th>
					<th>2014-2015最新影响因子</th>
					<th>研究方向</th>
				</tr>
			</thead>
			<tfoot>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>简称</th>
                <th>ISSN</th>
                <th>2014-2015最新影响因子</th>
                <th>研究方向</th>
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
</div>