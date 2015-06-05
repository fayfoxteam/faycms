<?php
use fay\helpers\Date;
?>
<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>当前用户信息</h4>
	</div>
	<div class="box-content">
		<table class="form-table">
		<?php if(\F::app()->session->get('id')){?>
			<tr>
				<th>用户身份</th>
				<td><?php echo \F::app()->session->get('role_title')?></td>
			</tr>
			<tr>
				<th>上次登陆时间</th>
				<td><?php echo Date::format(\F::app()->session->get('last_login_time'))?></td>
			</tr>
			<tr>
				<th>上次登陆IP</th>
				<td>
					<?php echo \F::app()->session->get('last_login_ip')?>
					<em>( <?php echo $iplocation->getCountryAndArea(\F::app()->session->get('last_login_ip'))?> )</em>
				</td>
			</tr>
		<?php }?>
			<tr>
				<th>浏览器内核</th>
				<td><span id="user-info-browser"></span></td>
			</tr>
			<tr>
				<th>浏览器套壳</th>
				<td><span id="user-info-browser-shell"></span></td>
			</tr>
			<tr>
				<th>当前登陆IP</th>
				<td>
					<?php echo F::app()->ip?>
					<em>( <?php echo $iplocation->getCountryAndArea(F::app()->ip)?> )</em>
				</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/analyst.min.js"></script>
<script>
$(function(){
	var browser = _fa.getBrowser();
	$('#user-info-browser').text(browser[0] + '/' + browser[1]);
	if(browser[2]){
		$('#user-info-browser-shell').text(browser[2] + '/' + browser[3]);
	}
});
</script>