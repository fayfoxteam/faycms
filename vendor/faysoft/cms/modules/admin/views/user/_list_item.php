<?php
use fay\models\tables\UsersTable;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fay\services\FileService;
use fay\services\user\UserRoleService;
?>
<tr valign="top" id="user-<?php echo $data['id']?>">
	<?php if(in_array('avatar', $cols)){?>
	<td><?php echo HtmlHelper::link(HtmlHelper::img($data['avatar'], FileService::PIC_THUMBNAIL, array(
		'width'=>40,
		'height'=>40,
		'class'=>'circle',
		'spare'=>'avatar',
	)), array('admin/user/item', array(
		'id'=>$data['id'],
	)), array(
		'title'=>false,
		'encode'=>false,
	))?></td>
	<?php }?>
	
	<td>
		<strong>
			<?php if($data['username'])
				echo HtmlHelper::encode($data['username']);
			else
				echo '&nbsp;';?>
		</strong>
		<div class="row-actions">
			<?php
				echo HtmlHelper::link('查看', array('admin/user/item', array(
					'id'=>$data['id'],
				)), array(), true);
				echo HtmlHelper::link('编辑', array('admin/user/edit', array(
					'id'=>$data['id'],
				)), array(), true);
			?>
		</div>
	</td>
	
	<?php if(in_array('roles', $cols)){?>
	<td><?php
		$user_roles = UserRoleService::service()->get($data['id']);
		foreach($user_roles as $k => $role){
			if($k){
				echo ', ';
			}
			echo HtmlHelper::link($role['title'], array('admin/user/index', array(
				'role'=>$role['id']
			)));
		}
	?></td>
	<?php }?>
	
	<?php if(in_array('mobile', $cols)){?>
	<td><?php echo HtmlHelper::encode($data['mobile'])?></td>
	<?php }?>
	
	<?php if(in_array('email', $cols)){?>
	<td>
		<a href="mailto:<?php echo HtmlHelper::encode($data['email'])?>"><?php echo HtmlHelper::encode($data['email'])?></a>
	</td>
	<?php }?>
	
	<?php if(in_array('nickname', $cols)){?>
	<td><?php echo HtmlHelper::encode($data['nickname'])?></td>
	<?php }?>
	
	<?php if(in_array('status', $cols)){?>
	<td>
		<?php if($data['status'] == UsersTable::STATUS_PENDING){?>
			<span class="fc-orange">未审核</span>
		<?php }else if($data['status'] == UsersTable::STATUS_VERIFIED){?>
			<span class="fc-green">通过审核</span>
		<?php }else if($data['status'] == UsersTable::STATUS_VERIFY_FAILED){?>
			<span class="fc-red">未通过审核</span>
		<?php }else if($data['status'] == UsersTable::STATUS_UNCOMPLETED){?>
			<span class="fc-orange">信息不完整</span>
		<?php }else if($data['status'] == UsersTable::STATUS_NOT_VERIFIED){?>
			<span class="fc-orange">未验证邮箱</span>
		<?php }?>
	</td>
	<?php }?>
	
	<?php if(in_array('block', $cols)){?>
	<td><?php if($data['block']){
		echo '<span class="fc-red">阻塞</span>';
	}else{
		echo '<span class="fc-green">正常</span>';
	}?></td>
	<?php }?>
	
	<?php if(in_array('reg_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo DateHelper::format($data['reg_time'])?>">
			<?php echo DateHelper::niceShort($data['reg_time'])?>
		</abbr>
	</td>
	<?php }?>
	
	<?php if(in_array('reg_ip', $cols)){?>
	<td>
		<abbr class="ip" title="<?php echo long2ip($data['reg_ip'])?>">
			<?php echo $iplocation->getCountryAndArea(long2ip($data['reg_ip']))?>
		</abbr>
	</td>
	<?php }?>
	
	<?php if(in_array('last_login_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo DateHelper::format($data['last_login_time'])?>">
			<?php echo DateHelper::niceShort($data['last_login_time'])?>
		</abbr>
	</td>
	<?php }?>
	
	<?php if(in_array('last_login_ip', $cols)){?>
	<td>
		<abbr class="ip" title="<?php echo long2ip($data['last_login_ip'])?>">
			<?php echo $iplocation->getCountryAndArea(long2ip($data['last_login_ip']))?>
		</abbr>
	</td>
	<?php }?>
	
	<?php if(in_array('last_time_online', $cols)){?>
	<td>
		<span class="time abbr" title="<?php echo DateHelper::format($data['last_time_online'])?>">
			<?php echo DateHelper::niceShort($data['last_time_online'])?>
		</span>
	</td>
	<?php }?>
	
	<?php if(in_array('trackid', $cols)){?>
	<td><?php echo $data['trackid']?></td>
	<?php }?>
</tr>