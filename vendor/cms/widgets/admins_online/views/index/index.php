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
		<ul class="online-admins">
		<?php foreach($admins as $a){?>
			<li>
				<span class="fl"><?php
					echo $a['username'];
					echo $a['nickname'] ? ' - ' . $a['nickname'] : '';
					echo ' (', long2ip($a['last_login_ip']), ')';
				?></span>
				<span class="fr"><?php echo Date::niceShort($a['last_login_time'])?></span>
				<div class="clear"></div>
			</li>
		<?php }?>
		</ul>
	</div>
</div>