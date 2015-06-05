<?php
use fay\helpers\Html;
?>
<div class="bd multiple-answer">
	<div class="clearfix exam-question-item">
		<span><?php echo $index+1?>、</span>
		<span><?php echo $question['question']?></span>
		<span>（共<?php echo $question['score']?> 分）</span>
	</div>
	<ul class="exam-question-answers">
	<?php foreach($question['answers'] as $a){?>
		<li><?php echo Html::inputCheckbox("answers[{$question['id']}][]", $a['id'], false, array(
			'label'=>Html::encode($a['answer']),
		))?></li>
	<?php }?>
	</ul>
</div>