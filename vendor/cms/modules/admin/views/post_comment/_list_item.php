<?php
use fay\helpers\Html;
use fay\helpers\Date;
use fay\models\tables\PostComments;
use cms\helpers\PostCommentHelper;
use fay\helpers\String;
?>
<tr valign="top" id="message-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
	));?></td>
	<?php if(in_array('id', $cols)){?>
		<td>评论ID</td>
	<?php }?>
	<td>
		<?php echo Html::encode($data['content'])?>
		<div class="row-actions">
			<?php if(!$data['deleted']){
				if($data['status'] == PostComments::STATUS_PENDING){
					echo Html::link('批准', array('admin/post-comment/approve', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-green',
					));
					echo Html::link('驳回', array('admin/post-comment/disapprove', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-orange',
					));
				}else if($data['status'] == PostComments::STATUS_APPROVED){
					echo Html::link('驳回', array('admin/post-comment/disapprove', array(
							'id'=>$data['id'],
					)), array(
							'class'=>'fc-orange',
					));
				}else if($data['status'] == PostComments::STATUS_UNAPPROVED){
					echo Html::link('批准', array('admin/post-comment/approve', array(
						'id'=>$data['id'],
					)), array(
						'class'=>'fc-green',
					));
				}
			}
			
			if($data['deleted']){
				echo Html::link('还原', array('admin/post-comment/undelete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-green',
				));
				echo Html::link('永久删除', array('admin/post-comment/remove', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'remove-link fc-red',
				));
			}else{
				echo Html::link('回收站', array('admin/post-comment/delete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'fc-red',
				));
			}?>
		</div>	
	</td>
	<?php if(in_array('user', $cols)){?>
	<td>
		<?php echo empty($data[$settings['display_name']]) ? '匿名' : Html::encode($data[$settings['display_name']]);?>
	</td>
	<?php }?>
	<?php if(in_array('post', $cols)){?>
	<td>
		<?php echo Html::link(String::niceShort($data['post_title'], 40), array('admin/post/edit', array(
			'id'=>$data['post_id'],
		)), array(
			'target'=>'_blank',
			'title'=>Html::encode($data['post_title']),
		))?>
	</td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td><?php echo PostCommentHelper::getStatus($data['status'], $data['deleted']);?></td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td>
		<abbr title="<?php echo Date::format($data['create_time'])?>">
			<?php echo Date::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<?php }?>
</tr>