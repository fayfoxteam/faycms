<?php
use fay\helpers\Html;
use fay\models\tables\Posts;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
?>
<?php echo F::form('setting')->open(array('admin/system/setting'))?>
	<?php echo F::form('setting')->inputHidden('_key')?>
	<div class="form-field">
		<label class="title bold">显示下列项目</label>
		<?php 
		foreach(F::app()->boxes as $box){
			echo Html::inputCheckbox('boxes[]', $box['name'],
				isset($enabled_boxes) && in_array($box['name'], $enabled_boxes) ? true : false, array(
					'label'=>$box['title'],
			));
		}
		?>
	</div>
	<div class="form-field">
		<label class="title bold">默认编辑器</label>
		<p class="fc-grey">注意：该设置仅在创建文章时有效，编辑文章时强制为文章创建时所使用的编辑器进行编辑</p>
		<?php
			echo F::form('setting')->inputRadio('editor', Posts::CONTENT_TYPE_VISUAL_EDITOR, array(
				'after'=>'可视化编辑器（CKeditor，普通用户请选择此项）',
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
			), true);
			echo F::form('setting')->inputRadio('editor', Posts::CONTENT_TYPE_MARKDOWN, array(
				'after'=>'PageDown编辑器（Markdown语法，适用于写文档等，非专业人员不建议使用）',
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
			));
			echo F::form('setting')->inputRadio('editor', Posts::CONTENT_TYPE_TEXTAREA, array(
				'after'=>'文本域（特殊情况下你可能需要用到它）',
				'wrapper'=>array(
					'tag'=>'label',
					'wrapper'=>'p',
				),
			));
		?>
	</div>
	<div class="form-field">
		<?php echo F::form('setting')->submitLink('提交', array(
			'class'=>'btn',
		))?>
	</div>
<?php echo F::form('setting')->close()?>