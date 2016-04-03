<?php
use fay\helpers\Html;
use apidoc\models\tables\Apis;
use fay\helpers\Date;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<div class="row-actions"><?php
			echo Html::link('编辑', array('admin/api/edit', array(
				'id'=>$data['id'],
			)), array(), true),
			Html::link('永久删除', array('admin/api/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'fc-red remove-link',
			), true);
		?></div>
	</td>
	<?php if(in_array('router', $cols)){?>
	<td><?php echo $data['router']?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td><?php echo $data['status']?></td>
	<?php }?>
	<?php if(in_array('category', $cols)){?>
	<td><?php echo $data['cat_title']?></td>
	<?php }?>
	<?php if(in_array('http_method', $cols)){?>
	<td><?php switch($data['http_method']){
		case Apis::HTTP_METHOD_GET:
			echo 'GET';
		break;
		case Apis::HTTP_METHOD_POST:
			echo 'POST';
		break;
		case Apis::HTTP_METHOD_BOTH:
			echo 'GET/POST';
		break;
	}?></td>
	<?php }?>
	<?php if(in_array('need_login', $cols)){?>
	<td><?php echo $data['need_login'] ? '是' : '否'?></td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php
		echo Html::link($data[F::form('setting')->getData('display_name', 'nickname')], array(
			'admin/api/index', array(
				'keywords_field'=>'user_id',
				'keywords'=>$data['user_id'],
			),
		));
	?></td>
	<?php }?>
	<?php if(in_array('version', $cols)){?>
	<td><?php echo $data['version']?></td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['create_time']);
			}else{
				echo Date::format($data['create_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
	<?php if(in_array('last_modified_time', $cols)){?>
	<td>
		<abbr class="time" title="<?php echo Date::format($data['last_modified_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['last_modified_time']);
			}else{
				echo Date::format($data['last_modified_time']);
			}?>
		</abbr>
	</td>
	<?php }?>
</tr>