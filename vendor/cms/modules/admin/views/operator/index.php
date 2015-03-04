<?php
use fay\helpers\Html;
use cms\helpers\ListTableHelper;

$cols = F::form('setting')->getData('cols');
?>
<div class="col-1">
	<form method="get" class="validform" id="operator-index-form">
		<div class="mb5">
			<?php echo F::form()->select('field', array(
				'id'=>'用户ID',
				'username'=>'用户名',
				'realname'=>'姓名',
				'cellphone'=>'手机',
				'email'=>'邮箱',
			))?>
			<?php echo F::form()->inputText('keywords', array('class'=>'w200'));?>
			|
			<?php echo F::form()->select('role', array(''=>'--角色--') + Html::getSelectOptions($roles, 'id', 'title'), array());?>
		</div>
		<div class="mb5">
			<a href="javascript:;" class="btn-3" id="operator-index-form-submit">查询</a>
		</div>
	</form>
	<table border="0" cellpadding="0" cellspacing="0" class="list-table operators">
		<thead>
			<tr>
				<?php if(in_array('avatar', $cols)){
					echo '<th class="w50">头像</th>';
				}?>
				<th>登录名</th>
				<?php if(in_array('role', $cols)){
					echo '<th>角色</th>';
				}
				if(in_array('cellphone', $cols)){
					echo '<th>手机</th>';
				}
				if(in_array('email', $cols)){
					echo '<th>邮箱</th>';
				}
				if(in_array('nickname', $cols)){
					echo '<th>昵称</th>';
				}
				if(in_array('realname', $cols)){
					echo '<th>真名</th>';
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
				<?php if(in_array('role', $cols)){
					echo '<th>角色</th>';
				}
				if(in_array('cellphone', $cols)){
					echo '<th>手机</th>';
				}
				if(in_array('email', $cols)){
					echo '<th>邮箱</th>';
				}
				if(in_array('nickname', $cols)){
					echo '<th>昵称</th>';
				}
				if(in_array('realname', $cols)){
					echo '<th>真名</th>';
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
	<?php $listview->showPage();?>
</div>