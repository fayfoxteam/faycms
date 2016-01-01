<?php
use fay\helpers\Html;
use fay\models\tables\Messages;
use fay\models\Post;
use fay\helpers\Date;
?>
<tr valign="top" id="message-<?php echo $data['id']?>">
	<td>
		<?php if($data['user_id']){
			echo Html::encode($data['username']);
			if($data['nickname']){
				echo '<br />',
				"<em class='fc-grey' title='用户名'>({$data['nickname']})</em>";
			}
		}else{
			echo '匿名';
		}?>
	</td>
	<td>
		<?php echo Html::encode($data['content'])?>
		<div class="row-actions">
			<?php if(!$data['deleted']){
				if($data['status'] == Messages::STATUS_PENDING){
					echo Html::link('批准', array('admin/message/approve', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-green',
					));
					echo Html::link('驳回', array('admin/message/unapprove', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-orange',
					));
				}else if($data['status'] == Messages::STATUS_APPROVED){
					echo Html::link('驳回', array('admin/message/unapprove', array(
							'id'=>$data['id'],
					)), array(
							'class'=>'fc-orange',
					));
				}else if($data['status'] == Messages::STATUS_UNAPPROVED){
					echo Html::link('批准', array('admin/message/approve', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-green',
					));
				}
			}
			
			if($data['deleted']){
				echo Html::link('还原', array('admin/message/undelete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-green',
				));
			}else{
				echo Html::link('回收站', array('admin/message/delete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red',
				));
			}
			echo Html::link('永久删除', array('admin/message/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'remove-link fc-red',
			))?>
		</div>	
	</td>
	<td>
		<?php echo Html::link($data['post_title'], array('admin/post/edit', array(
			'id'=>$data['post_id'],
		)), array(
			'target'=>'_blank',
		))?>
	</td>
	<td><?php if($data['status'] == Messages::STATUS_APPROVED){
		echo '<span class="fc-green">通过</span>';
	}else if($data['status'] == Messages::STATUS_UNAPPROVED){
		echo '<span class="fc-red">驳回</span>';
	}else if($data['status'] == Messages::STATUS_PENDING){
		echo '<span class="fc-orange">待审</span>';
	}?></td>
	<td>
		<abbr title="<?php echo Date::format($data['create_time'])?>">
			<?php echo Date::niceShort($data['create_time'])?>
		</abbr>
	</td>
</tr>