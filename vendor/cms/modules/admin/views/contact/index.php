<?php
use fay\helpers\Html;

$cols = F::form('setting')->getData('cols');
?>
<div class="row">
	<div class="col-12">
		<ul class="contact-list">
			<?php $listview->showData(array(
				'setting'=>$settings,
			));?>
		</ul>
		<?php $listview->showPager();?>
	</div>
</div>