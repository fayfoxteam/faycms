<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>IP统计</h4>
	</div>
	<div class="box-content">
		<table class="inbox-table">
			<thead>
				<tr>
					<th>IP</th>
					<th>来源城市</th>
					<th>访问次数</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach($ips as $ip){
			?>
				<tr class="order-desc">
					<td><?php echo HtmlHelper::link(long2ip($ip['ip_int']), array('cms/admin/analyst/views', array(
						'ip'=>long2ip($ip['ip_int'])
					)));?></td>
					<td><?php echo $iplocation->getCountryAndArea(long2ip($ip['ip_int']))?></td>
					<td><?php echo $ip['count']?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>