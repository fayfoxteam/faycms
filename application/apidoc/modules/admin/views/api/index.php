<?php
use cms\helpers\ListTableHelper;
use apidoc\models\tables\Apis;
use fay\helpers\HtmlHelper;

$cols = F::form('setting')->getData('cols', array());
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5"><?php
				echo F::form('search')->select('keywords_field', array(
					'router'=>'路由',
					'title'=>'标题',
					'user_id'=>'用户ID',
				), array(
					'class'=>'form-control',
				)),
				'&nbsp;',
				F::form('search')->inputText('keywords', array(
					'class'=>'form-control w200',
				)),
				'&nbsp;',
				F::form('search')->select('cat_id', array(
					''=>'--分类--',
				) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
					'class'=>'form-control',
				));
			?></div>
			<div><?php
				echo F::form('search')->select('time_field', array(
					'create_time'=>'创建时间',
					'last_modified_time'=>'最后修改时间',
				), array(
					'class'=>'form-control',
				)),
				'&nbsp;',
				F::form('search')->inputText('start_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'开始时间',
					'class'=>'form-control datetimepicker',
				)),
				' - ',
				F::form('search')->inputText('end_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'结束时间',
					'class'=>'form-control datetimepicker',
				)),
				F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
	</div>
</div>
<div class="row">
	<div class="col-5">
		<ul class="subsubsub fl">
			<li class="<?php if(F::app()->input->get('status') == null)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/api/index')?>">全部</a>
				<span class="fc-grey">(<span id="api-count-0"><?php
					echo array_sum($status_counts);
				?></span>)</span>
			</li>
			<?php $status = Apis::getStatus();?>
			<?php foreach($status as $k => $s){?>
				<li <?php if(F::app()->input->get('status') == $k)echo 'class="sel"';?>>
					|
					<a href="<?php echo $this->url('admin/api/index', array('status'=>$k))?>"><?php echo $s?></a>
					<span class="fc-grey">(<span id="api-count-<?php echo $k?>"><?php
						echo isset($status_counts[$k]) ? $status_counts[$k] : 0;
					?></span>)</span>
				</li>
			<?php }?>
		</ul>
	</div>
	<div class="col-7"><?php $listview->showPager()?></div>
</div>
<div class="row">
	<div class="col-12">
		<table class="list-table">
			<thead>
				<tr>
					<th>标题</th>
					<?php if(in_array('router', $cols)){?>
					<th>路由</th>
					<?php }?>
					<?php if(in_array('status', $cols)){?>
					<th class="w90">状态</th>
					<?php }?>
					<?php if(in_array('category', $cols)){?>
					<th>分类</th>
					<?php }?>
					<?php if(in_array('http_method', $cols)){?>
					<th>请求方式</th>
					<?php }?>
					<?php if(in_array('need_login', $cols)){?>
					<th>是否需要登录</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>作者</th>
					<?php }?>
					<?php if(in_array('since', $cols)){?>
					<th>自从</th>
					<?php }?>
					<?php if(in_array('last_modified_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(in_array('create_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>标题</th>
					<?php if(in_array('router', $cols)){?>
					<th>路由</th>
					<?php }?>
					<?php if(in_array('status', $cols)){?>
					<th>状态</th>
					<?php }?>
					<?php if(in_array('category', $cols)){?>
					<th>分类</th>
					<?php }?>
					<?php if(in_array('http_method', $cols)){?>
					<th>请求方式</th>
					<?php }?>
					<?php if(in_array('need_login', $cols)){?>
					<th>是否需要登录</th>
					<?php }?>
					<?php if(in_array('user', $cols)){?>
					<th>作者</th>
					<?php }?>
					<?php if(in_array('since', $cols)){?>
					<th>自从</th>
					<?php }?>
					<?php if(in_array('last_modified_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('last_modified_time', '最后修改时间')?></th>
					<?php }?>
					<?php if(in_array('create_time', $cols)){?>
					<th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
					<?php }?>
				</tr>
			</tfoot>
			<tbody><?php $listview->showData(array(
				'cols'=>$cols,
				'http_methods'=>Apis::getHttpMethods(),
			));?></tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12"><?php $listview->showPager()?></div>
</div>