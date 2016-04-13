<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<li>
	<?php echo Html::link($data['paper_title'], array('user/exam/item', array(
		'id'=>$data['id'],
	)))?>
	<p>
		<span>开考时间：<?php echo Date::format($data['start_time'])?></span>
		|
		<span>耗时：<?php echo Date::diff($data['start_time'], $data['end_time'])?></span>
		|
		<span>得分/总分：<?php echo $data['score'], ' / ', $data['total_score']?></span>
	</p>
</li>