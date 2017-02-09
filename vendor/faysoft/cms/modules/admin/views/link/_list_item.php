<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;

/**
 * @var $data array
 */
?>
<tr valign="top" id="link-<?php echo $data['id']?>">
	<td>
		<strong>
			<a class="row-title" title="<?php echo $data['description']?>" href="<?php echo $data['url']?>" target="_blank"><?php echo $data['title']?></a>
		</strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/link/edit', array('id'=>$data['id']))?>">编辑</a>
			<a href="<?php echo $data['url']?>" target="_blank">访问</a>
			<a href="<?php echo $this->url('admin/link/remove', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">永久删除</a>
		</div>
	</td>
	<td><?php echo HtmlHelper::encode($data['url'])?></td>
	<td>
		<?php if($data['visible']){
			echo '可见';
		}else{
			echo '不可见';
		}?>
	</td>
	<td><?php echo HtmlHelper::link($data['cat_title'], array('admin/link/index', array(
		'cat_id'=>$data['cat_id'],
	)))?></td>
	<td><?php echo HtmlHelper::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'form-control w50 edit-sort',
	))?></td>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['last_modified_time'])?>">
			<?php echo DateHelper::niceShort($data['last_modified_time'])?>
		</abbr>
	</td>
</tr>