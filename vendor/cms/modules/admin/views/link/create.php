<?php
use fay\helpers\Html;
?>
<form id="form" action="" method="post" class="validform">
	<?php $this->renderPartial('_edit_panel')?>
	<div class="form-field">
		<a href="javascript:;" class="btn-1" id="form-submit">添加链接</a>
	</div>
</form>
<?php $this->renderPartial('_js')?>