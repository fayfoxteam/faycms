<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title">显示下列项目</label>
		<?php
		echo F::form('setting')->inputCheckbox('cols[]', 'main_category', array(
			'label'=>'主分类',
		));
		if(in_array('category', $enabled_boxes)){
			//若附加分类的box被移除，则不显示该列
			echo F::form('setting')->inputCheckbox('cols[]', 'category', array(
				'label'=>'附加分类',
			));
		}
		if(in_array('tags', $enabled_boxes)){
			//若标签的box被移除，则不显示该列
			echo F::form('setting')->inputCheckbox('cols[]', 'tags', array(
				'label'=>'标签',
			));
		}
		echo F::form('setting')->inputCheckbox('cols[]', 'status', array(
			'label'=>'状态',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'user', array(
			'label'=>'作者',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'views', array(
			'label'=>'阅读数',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'comments', array(
			'label'=>'评论数',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'publish_time', array(
			'label'=>'发布时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_view_time', array(
			'label'=>'最后访问时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'last_modified_time', array(
			'label'=>'最后修改时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'create_time', array(
			'label'=>'创建时间',
		));
		echo F::form('setting')->inputCheckbox('cols[]', 'sort', array(
			'label'=>'排序',
		));
		?>
	</div>
	<div class="form-field">
		<label class="title">显示作者</label>
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
		<label class="title">显示时间</label>
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
		<label class="title">分页大小</label>
		<?php echo F::form('setting')->inputNumber('page_size', array(
			'class'=>'form-control w50',
			'min'=>1,
		))?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn btn-sm',
		))?>
	</div>
<?php echo F::form('setting')->close()?>