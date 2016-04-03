<?php
use cms\helpers\ListTableHelper;
use apidoc\models\tables\Apis;
use fay\helpers\Html;

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
				) + Html::getSelectOptions($cats, 'id', 'title'), array(
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
					'data-label'=>'时间',
					'class'=>'form-control datetimepicker',
				)),
				' - ',
				F::form('search')->inputText('end_time', array(
					'data-rule'=>'datetime',
					'data-label'=>'时间',
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
			<li class="developing <?php if(F::app()->input->get('status') == Apis::STATUS_DEVELOPING)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/api/index', array('status'=>Apis::STATUS_DEVELOPING))?>">开发中</a>
				<span class="fc-grey">(<span id="developing-api-count"><?php
					echo isset($status_counts[Apis::STATUS_DEVELOPING]) ? $status_counts[Apis::STATUS_DEVELOPING] : 0;
				?></span>)</span>
				|
			</li>
			<li class="beta <?php if(F::app()->input->get('status') == Apis::STATUS_BETA)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/api/index', array('status'=>Apis::STATUS_BETA))?>">测试中</a>
				<span class="fc-grey">(<span id="beta-api-count"><?php
					echo isset($status_counts[Apis::STATUS_BETA]) ? $status_counts[Apis::STATUS_BETA] : 0;
				?></span>)</span>
				|
			</li>
			<li class="stable <?php if(F::app()->input->get('status') == Apis::STATUS_STABLE)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/api/index', array('status'=>Apis::STATUS_STABLE))?>">已上线</a>
				<span class="fc-grey">(<span id="stable-api-count"><?php
					echo isset($status_counts[Apis::STATUS_STABLE]) ? $status_counts[Apis::STATUS_STABLE] : 0;
				?></span>)</span>
				|
			</li>
			<li class="deprecated <?php if(F::app()->input->get('status') == Apis::STATUS_DEPRECATED)echo 'sel';?>">
				<a href="<?php echo $this->url('admin/api/index', array('status'=>Apis::STATUS_DEPRECATED))?>">已弃用</a>
				<span class="fc-grey">(<span id="deprecated-api-count"><?php
					echo isset($status_counts[Apis::STATUS_DEPRECATED]) ? $status_counts[Apis::STATUS_DEVELOPING] : 0;
				?></span>)</span>
			</li>
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
					<?php if(in_array('version', $cols)){?>
					<th>起始版本</th>
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
					<?php if(in_array('version', $cols)){?>
					<th>起始版本</th>
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
			));?></tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-12"><?php $listview->showPager()?></div>
</div>