<?php
use fay\helpers\Html;
use cms\helpers\ListTableHelper;

$cols = F::form('setting')->getData('cols');
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				<?php echo F::form('search')->select('time_field', array(
					'reg_time' =>'注册时间',
					'last_login_time' =>'最后登陆时间',
					'last_time_online' =>'最后活跃时间',
				), array(
					'class'=>'form-control',
				))?>
				<?php echo F::form('search')->inputText('start_time', array(
					'data-rule'=>'datetime',
					'class'=>'datetimepicker form-control',
				));?>
				-
				<?php echo F::form('search')->inputText('end_time', array(
					'data-rule'=>'datetime',
					'class'=>'datetimepicker form-control',
				));?>
			</div>
			<div class="mb5">
				<?php echo F::form('search')->select('select-by', array(
					'username'=>'登陆名',
					'nickname'=>'昵称',
					'mobile'=>'手机号',
					'email'=>'邮箱',
					'id'=>'用户ID',
				), array(
					'class'=>'form-control',
				))?>
				<?php echo F::form('search')->inputText('keywords', array('class'=>'form-control w200'))?>
				|
				<?php echo F::form('search')->select('role', array(''=>'--角色--')+Html::getSelectOptions($roles), array(
					'class'=>'form-control',
				));?>
				<?php echo F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
		<?php $listview->showPager();?>
		<table class="list-table">
			<thead>
				<tr>
					<?php if(in_array('avatar', $cols)){
						echo '<th class="w50">头像</th>';
					}?>
					<th>登录名</th>
					<?php if(in_array('roles', $cols)){
						echo '<th>角色</th>';
					}
					if(in_array('mobile', $cols)){
						echo '<th>手机</th>';
					}
					if(in_array('email', $cols)){
						echo '<th>邮箱</th>';
					}
					if(in_array('nickname', $cols)){
						echo '<th>昵称</th>';
					}
					if(in_array('status', $cols)){
						echo '<th>状态</th>';
					}
					if(in_array('block', $cols)){
						echo '<th>阻塞</th>';
					}
					if(in_array('reg_time', $cols)){
						echo '<th>', ListTableHelper::getSortLink('reg_time', '注册时间'), '</th>';
					}
					if(in_array('reg_ip', $cols)){
						echo '<th>注册IP</th>';
					}
					if(in_array('last_login_time', $cols)){
						echo '<th>', ListTableHelper::getSortLink('last_login_time', '最后登陆时间'), '</th>';
					}
					if(in_array('last_login_ip', $cols)){
						echo '<th>最后登陆IP</th>';
					}
					if(in_array('last_time_online', $cols)){
						echo '<th>', ListTableHelper::getSortLink('last_time_online', '最后在线时间'), '</th>';
					}
					if(in_array('trackid', $cols)){
						echo '<th>Trackid</th>';
					}?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<?php if(in_array('avatar', $cols)){
						echo '<th>头像</th>';
					}?>
					<th>登录名</th>
					<?php if(in_array('roles', $cols)){
						echo '<th>角色</th>';
					}
					if(in_array('mobile', $cols)){
						echo '<th>手机</th>';
					}
					if(in_array('email', $cols)){
						echo '<th>邮箱</th>';
					}
					if(in_array('nickname', $cols)){
						echo '<th>昵称</th>';
					}
					if(in_array('block', $cols)){
						echo '<th>阻塞</th>';
					}
					if(in_array('reg_time', $cols)){
						echo '<th>', ListTableHelper::getSortLink('reg_time', '注册时间'), '</th>';
					}
					if(in_array('reg_ip', $cols)){
						echo '<th>注册IP</th>';
					}
					if(in_array('last_login_time', $cols)){
						echo '<th>', ListTableHelper::getSortLink('last_login_time', '最后登陆时间'), '</th>';
					}
					if(in_array('last_login_ip', $cols)){
						echo '<th>最后登陆IP</th>';
					}
					if(in_array('last_time_online', $cols)){
						echo '<th>', ListTableHelper::getSortLink('last_time_online', '最后在线时间'), '</th>';
					}
					if(in_array('trackid', $cols)){
						echo '<th>Trackid</th>';
					}?>
				</tr>
			</tfoot>
			<tbody>
		<?php
			$listview->showData(array(
				'cols'=>$cols,
			));
		?>
			</tbody>
		</table>
		<?php $listview->showPager();?>
	</div>
</div>