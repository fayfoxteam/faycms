<div class="box" id="box-ip" data-name="ip">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>IP</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('show_ip_int', array('class'=>'form-control'))?>
		<p class="fc-grey">在前台显示的IP，可以不是真实IP</p>
		<?php if(F::form()->getData('ip_int') !== null){?>
		<p class="misc-pub-section mt6 pl0">
			<span>真实IP：</span>
			<?php echo F::form()->getData('ip_int', null, false)?>
		</p>
		<?php }?>
	</div>
</div>