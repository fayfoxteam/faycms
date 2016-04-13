<?php
use fay\helpers\Html;
?>
<div class="bd input">
	<div class="clearfix exam-question-item">
		<span><?php echo $index+1?>、</span>
		<span><?php echo $question['question']?></span>
		<span>（共<?php echo $question['score']?> 分）</span>
	</div>
	<div class="">
		<?php echo Html::textarea("answers[{$question['id']}]", '', array(
			'style'=>'width:90%;height:100px;',
		))?>
	</div>
</div>