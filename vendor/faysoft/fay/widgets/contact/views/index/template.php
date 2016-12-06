<?php
/**
 * @var string $alias
 * @var $config
 */
?>
<form class="contact-form" id="widget-<?php echo \fay\helpers\Html::encode($alias)?>-form" action="<?php echo $this->url('contact/send')?>" method="post">
	<?php foreach($config['elements'] as $element){?>
	<fieldset>
	<?php
		if(!empty($config['label'][$element])){
			echo \fay\helpers\Html::tag('label', array(
				'for'=>'widget-' . \fay\helpers\Html::encode($alias) . '-field-' . $element,
			), $config['label'][$element]);
		}
		if($element == 'content'){
			//若是内容字段，输出文本域
			echo \fay\helpers\Html::textarea($element, '', array(
				'id'=>'widget-' . \fay\helpers\Html::encode($alias) . '-field-' . $element,
				'placeholder'=>empty($config['placeholder'][$element]) ? false : $config['placeholder'][$element]
			));
		}else{
			//其他字段输出输入框
			echo \fay\helpers\Html::inputText($element, '', array(
				'id'=>'widget-' . \fay\helpers\Html::encode($alias) . '-field-' . $element,
				'placeholder'=>empty($config['placeholder'][$element]) ? false : $config['placeholder'][$element]
			));
		}
	?>
	</fieldset>
	<?php }?>
	<fieldset>
		<?php echo \fay\helpers\Html::link($config['submit_text'], 'javascript:;', array(
			'class'=>'btn',
			'id'=>'widget-' . \fay\helpers\Html::encode($alias) . '-form-submit'
		))?>
	</fieldset>
</form>