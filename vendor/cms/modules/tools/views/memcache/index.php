<?php
use fay\helpers\Html;
?>
<div class="detail-panel">
<?php foreach($slabs as $slabId => $slabMeta){
	if(!is_int($slabId)) continue;
	if($slabMeta['used_chunks'] == 0) continue;
	$cdump = F::cache()->memcache()->getExtendedStats('cachedump', $slabId);
	$first_cdump = array_shift($cdump);
	?>
	<div class="bd">
		<h3 class="fl">SLAB: <?php echo $slabId?></h3>
		<span class="pl11">(
			Records: <?php echo $slabMeta['used_chunks']?> |
			get_hits: <?php echo $slabMeta['get_hits']?> |
			cmd_set: <?php echo $slabMeta['cmd_set']?> |
			delete_hits: <?php echo $slabMeta['delete_hits']?>
		)</span>
		<table class="list-table">
			<thead>
				<tr>
					<th class="wp38">键</th>
					<th>值</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($first_cdump as $key=>$v){?>
				<tr>
					<td>
						<span><?php echo $key?></span>
						<div class="row-actions">
							<?php echo Html::link('Delete', array('tools/memcache/delete', array(
								'key'=>$key,
							)), array(
								'class'=>'fc-red',
							))?>
						</div>
					</td>
					<td style="word-break:break-all;word-wrap:break-word;"><?php 
						$data = F::cache()->get($key);
						if(is_array($data)){
							echo json_encode($data);
						}else{
							echo $data;
						}
					?></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
<?php }?>
</div>