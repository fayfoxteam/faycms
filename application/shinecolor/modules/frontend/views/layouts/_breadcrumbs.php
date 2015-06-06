<?php
use fay\helpers\Html;
?>
<div class="w1000">
	<div id="breadcrumbs">
		当前位置：
		<?php foreach($breadcrumbs as $b){
			if(!empty($b['link'])){
				echo Html::link($b['label'], $b['link']), ' &gt; ';
			}else{
				echo $b['label'];
			}
		}?>
	</div>
</div>