<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<tr valign="top" id="link-<?php echo $data['id']?>">
	<td>
		<strong>
			<a class="row-title" title="<?php echo $data['description']?>" href="<?php echo $data['url']?>" target="_blank"><?php echo $data['title']?></a>
		</strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/link/edit', array('id'=>$data['id']))?>">编辑</a>
			<a href="<?php echo $data['url']?>" target="_blank">访问</a>
			<a href="<?php echo $this->url('admin/link/remove', array('id'=>$data['id']) + F::input()->get())?>" class="color-red remove-link">永久删除</a>
		</div>
	</td>
	<td><?php echo Html::encode($data['url'])?></td>
	<td>
		<?php if($data['visiable']){
			echo '可见';
		}else{
			echo '不可见';
		}?>
	</td>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'edit-sort w30',
	))?></td>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['last_modified_time'])?>">
			<?php echo Date::niceShort($data['last_modified_time'])?>
		</span>
	</td>
</tr>