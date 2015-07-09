<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">显示下列项目</label>
		<?php 
		echo F::form('setting')->inputCheckbox('cols[]', 'file_type', array(
			'label'=>'文件类型',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'file_path', array(
			'label'=>'存储路径',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'file_size', array(
			'label'=>'文件大小',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'user', array(
			'label'=>'用户',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'type', array(
			'label'=>'用于',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'downloads', array(
			'label'=>'下载次数',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'upload_time', array(
			'label'=>'上传时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'qiniu', array(
			'label'=>'七牛',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title bold">显示用户</label>
		<?php
		echo F::form('setting')->inputRadio('display_name', 'username', array(
			'label'=>'用户名',
		), true);
		echo F::form('setting')->inputRadio('display_name', 'nickname', array(
			'label'=>'昵称',
		));
		echo F::form('setting')->inputRadio('display_name', 'realname', array(
			'label'=>'真名',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title bold">显示时间</label>
		<?php
		echo F::form('setting')->inputRadio('display_time', 'short', array(
			'label'=>'简化时间',
		), true);
		echo F::form('setting')->inputRadio('display_time', 'full', array(
			'label'=>'完整时间',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title bold">分页大小</label>
		<?php echo F::form('setting')->inputNumber('page_size', array(
			'class'=>'form-control w50',
			'min'=>1,
			'max'=>999,
		))?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn btn-sm',
		))?>
	</div>
<?php echo F::form('setting')->close()?>