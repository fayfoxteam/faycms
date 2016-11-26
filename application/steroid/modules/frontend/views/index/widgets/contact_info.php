<?php
/**
 * @var $data
 */
?>
<div class="contact-info">
<?php foreach($data as $d){?>
	<div class="contact-info-item">
		<i class="<?php echo $d['key']?>"></i>
		<div class="detail"><?php echo nl2br($d['value'])?></div>
	</div>
<?php }?>
</div>