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
	<td><span class="abbr" title="<?php echo urldecode(Html::encode($data['refer']))?>">
		<?php echo String::niceShort(urldecode(Html::encode($data['refer'])), 22)?>
	</span></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
</tr>