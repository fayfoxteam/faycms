<?php
use fay\helpers\Date;
?>
<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>在线管理员</h4>
	</div>
	<div class="box-content">
		<table class="form-table">
			<tr>
				<th>在线管理员总数</th>
				<td><?php echo count($admins)?></td>
			</tr>
		</table>
		<ul class="online-admins">
		<?php foreach($admins as $a){?>
			<li>
				<span class="fl"><?php echo $a['username']?> - <?php echo $a['realname']?><em>(<?php echo $a['role_title']?>)</em></span>
				<span class="fr"><?php echo Date::niceShort($a['last_login_time'])?></span>
				<div class="clear"></div>
			</li>
		<?php }?>
		</ul>
	</div>
</div>