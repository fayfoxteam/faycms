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
			<?php echo HtmlHelper::link($data['name'], array('admin/payment/edit', array('id'=>$data['id'])))?>
		</strong>
		<div class="row-actions">
			<a href="<?php echo $this->url('admin/payment/edit', array('id'=>$data['id']))?>">编辑</a>
			<a href="<?php echo $this->url('admin/payment/delete', array('id'=>$data['id']))?>" class="fc-red">删除</a>
		</div>
	</td>
	<td><?php
		echo \fay\models\tables\PaymentsTable::$codes[$data['code']], '<em class="fc-grey"> [ ', $data['code'], ' ]</em>'
	?></td>
	<td>
		<?php if($data['enabled']){
			echo '<span class="fc-green">是</span>';
		}else{
			echo '<span class="fc-orange">否</span>';
		}?>
	</td>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
			<?php echo DateHelper::niceShort($data['create_time'])?>
		</abbr>
	</td>
	<td class="col-date">
		<abbr class="time" title="<?php echo DateHelper::format($data['last_modified_time'])?>">
			<?php echo DateHelper::niceShort($data['last_modified_time'])?>
		</abbr>
	</td>
</tr>