<div class="row">
	<div class="col-6">
		<h3>Form 1</h3>
		<form id="test-form" class="form-1-3" method="post">
			<h3>输入框(username)</h3>
			<?php echo F::form()->inputText('username')?>
			<h3>单选框(role)</h3>
			<?php
				echo F::form()->inputRadio('role', 1, array(
					'label'=>1,
				));
				echo F::form()->inputRadio('role', 2, array(
					'label'=>2,
				));
			?>
			<h3>复选框(status)</h3>
			<?php
				echo F::form()->inputCheckbox('status[]', 1, array(
					'label'=>1,
				));
				echo F::form()->inputCheckbox('status[]', 2, array(
					'label'=>2,
				));
			?>
			<h3>文本域(refer)</h3>
			<?php echo F::form()->textarea('refer')?>
			<h3>下拉框(block)</h3>
			<?php echo F::form()->select('block', array(
				'0'=>'否',
				'1'=>'是',
			))?>
			<h3>下拉多选框(cat_id)</h3>
			<?php echo F::form()->select('cat_id[]', array(
				'1'=>'1',
				'2'=>'2',
				'3'=>'3',
				'4'=>'4',
				'5'=>'5',
			), array(
				'multiple'=>true,
			))?>
			<h3>时间格式(datetime)</h3>
			<?php echo F::form()->inputText('datetime')?>
			
			<div><input type="submit" /></div>
		</form>
	</div>
</div>