<?php
use fay\helpers\Date;
use fay\helpers\Html;
use fay\services\File;
use fay\services\user\Role;
use fay\models\tables\Roles;
?>
<tr valign="top" id="user-<?php echo $data['id']?>">
	<?php if(in_array('avatar', $cols)){?>
	<td><?php echo Html::link(Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
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
				echo Html::encode($data['username']);
			else
				echo '&nbsp;';?>
		</strong>
		<div class="row-actions">
			<?php
				echo Html::link('查看', array('admin/operator/item', array(
					'id'=>$data['id'],
				)), array(), true);
				if(!Role::service()->is(Roles::ITEM_SUPER_ADMIN, $data['id']) || Role::service()->is(Roles::ITEM_SUPER_ADMIN)){
					echo Html::link('编辑', array('admin/operator/edit', array(
						'id'=>$data['id'],
					)), array(), true);
				}
			?>
		</div>
	</td>
	
	<?php if(in_array('roles', $cols)){?>
	<td><?php
		$user_roles = Role::service()->get($data['id']);
		foreach($user_roles as $k => $role){
			if($k){
				echo ', ';
			}
			echo Html::link($role['title'], array('admin/operator/index', array(
				'role'=>$role['id']
			)));
		}
	?></td>
	<?php }?>
	
	<?php if(in_array('mobile', $cols)){?>
	<td><?php echo Html::encode($data['mobile'])?></td>
	<?php }?>
	
	<?php if(in_array('email', $cols)){?>
	<td>
		<a href="mailto:<?php echo Html::encode($data['email'])?>"><?php echo Html::encode($data['email'])?></a>
	</td>
	<?php }?>
	
	<?php if(in_array('nickname', $cols)){?>
	<td><?php echo Html::encode($data['nickname'])?></td>
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
		<abbr class="time" title="<?php echo Date::format($data['reg_time'])?>">
			<?php echo Date::niceShort($data['reg_time'])?>
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
		<abbr class="time" title="<?php echo Date::format($data['last_login_time'])?>">
			<?php echo Date::niceShort($data['last_login_time'])?>
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
		<abbr class="time" title="<?php echo Date::format($data['last_time_online'])?>">
			<?php echo Date::niceShort($data['last_time_online'])?>
		</abbr>
	</td>
	<?php }?>
	
	<?php if(in_array('trackid', $cols)){?>
	<td><?php echo $data['trackid']?></td>
	<?php }?>
</tr>