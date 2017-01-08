<div class="notification-container">
	<?php foreach($notification as $status => $n){?>
	<div class="notification notification-<?php echo $status?>">
		<?php foreach($n as $i){?>
			<p><?php echo $i?></p>
		<?php }?>
	</div>
	<?php }?>
</div>