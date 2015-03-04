<?php
use fay\helpers\Html;
use fay\helpers\String;
use fay\helpers\Date;
?>
<tr>
	<?php if(in_array('area', $cols)){?>
	<td><span class="abbr" title="<?php echo long2ip($data['ip_int'])?>"><?php echo $iplocation->getCountry(long2ip($data['ip_int']))?></span></td>
	<?php }?>
	<?php if(in_array('ip', $cols)){?>
	<td><?php echo long2ip($data['ip_int'])?></td>
	<?php }?>
	<?php if(in_array('url', $cols)){?>
	<td><a href="<?php echo $data['url']?>" target="_blank">
		<span class="abbr" title="<?php echo urldecode(Html::encode($data['url']))?>">
			<?php echo String::niceShort(urldecode(Html::encode($data['url'])), 32)?>
		</span>
	</a></td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td><span class="abbr" title="<?php echo Date::format($data['create_time'])?>">
		<?php echo Date::niceShort($data['create_time'])?>
	</span></td>
	<?php }?>
	<?php if(in_array('site', $cols)){?>
	<td><span><?php echo Html::encode($data['site_title'])?></span></td>
	<?php }?>
	<?php if(in_array('trackid', $cols)){?>
	<td><span><?php echo Html::encode($data['trackid'])?></span></td>
	<?php }?>
	<?php if(in_array('refer', $cols)){?>
	<td><a href="<?php echo $data['refer']?>" target="_blank">
		<span class="abbr" title="<?php echo urldecode(Html::encode($data['refer']))?>">
		<?php echo String::niceShort(urldecode(Html::encode($data['refer'])), 32)?>
		</span>
	</a></td>
	<?php }?>
	<?php if(in_array('se', $cols)){?>
	<td><?php echo Html::encode($data['se'])?></td>
	<?php }?>
	<?php if(in_array('keywords', $cols)){?>
	<td><?php echo Html::encode($data['keywords'])?></td>
	<?php }?>
	<?php if(in_array('browser', $cols)){?>
	<td><span><?php echo Html::encode($data['browser'])?></span></td>
	<?php }?>
	<?php if(in_array('browser_version', $cols)){?>
	<td><span><?php echo Html::encode($data['browser_version'])?></span></td>
	<?php }?>
	<?php if(in_array('shell', $cols)){?>
	<td><span><?php echo Html::encode($data['shell'])?></span></td>
	<?php }?>
	<?php if(in_array('shell_version', $cols)){?>
	<td><span><?php echo Html::encode($data['shell_version'])?></span></td>
	<?php }?>
	<?php if(in_array('os', $cols)){?>
	<td><span><?php echo Html::encode($data['os'])?></span></td>
	<?php }?>
	<?php if(in_array('ua', $cols)){?>
	<td><?php echo Html::encode($data['user_agent'])?></td>
	<?php }?>
	<?php if(in_array('screen', $cols)){?>
	<td><span><?php echo $data['screen_x'], ' x ', $data['screen_y']?></span></td>
	<?php }?>
</tr>