<?php
use fay\helpers\Html;

/**
 * @var $config
 */

$elements = array(
	'name' => '称呼',
	'email' => '邮箱',
	'content' => '内容',
	'mobile' => '电话',
	'title' => '标题',
	'country' => '国家',
);

?>
<div class="box">
	<div class="box-title">
		<h4>配置参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label class="title bold">标题</label>
			<?php echo F::form('widget')->inputText('title', array(
				'class'=>'form-control mw400',
			))?>
			<p class="fc-grey">用途视渲染模版而定</p>
		</div>
		<div class="form-field">
			<label class="title bold">提交按钮文案</label>
			<?php echo F::form('widget')->inputText('submit_text', array(
				'class'=>'form-control mw400',
			), '发送')?>
		</div>
		<div class="form-field">
			<label class="title bold">提交按钮CSS类</label>
			<?php echo F::form('widget')->inputText('submit_btn_class', array(
				'class'=>'form-control mw400',
			), 'btn')?>
		</div>
		<div class="form-field">
			<label class="title bold">提交成功文案</label>
			<?php echo F::form('widget')->inputText('submit_success', array(
				'class'=>'form-control mw400',
			), '发送成功')?>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<h4>表单元素</h4>
	</div>
	<div class="box-content">
		<div class="form-group">
			<label class="col-2 title">表单元素</label>
			<div class="col-10 pt7" id="widget-contact-element-options">
			<?php foreach($elements as $element => $label){?>
				<span class="w135 ib"><?php
					echo F::form('widget')->inputCheckbox('elements[]', $element, array(
						'label'=>$label,
						'class'=>'element-options'
					), in_array($element, $config['elements']));
				?></span>
			<?php }?>
			</div>
		</div>
		<div class="dragsort-list" id="widget-contact-element-list">
			<?php foreach($config['elements'] as $element){?>
				<div class="dragsort-item cf" id="element-<?php echo $element?>">
					<a class="dragsort-item-selector"></a>
					<div class="dragsort-item-container">
						<div class="col-6">
							<div class="ib mb10">
								<strong><?php echo $elements[$element]?></strong>
							</div>
							<div class="mb5"><?php
								echo Html::inputText(
									"label[{$element}]",
									isset($config['label'][$element]) ? $config['label'][$element] : '',
									array(
										'placeholder'=>'Label',
										'class'=>'form-control',
									)
								);
							?></div>
							<?php echo Html::inputText(
								"require_message[{$element}]",
								isset($config['require_message'][$element]) ? $config['require_message'][$element] : '',
								array(
									'placeholder'=>'必填报错语（留空则不做必填验证）',
									'class'=>'form-control',
								)
							)?>
						</div>
						<div class="col-6">
							<div class="mb5"><?php echo Html::textarea(
								"placeholder[{$element}]",
								isset($config['placeholder'][$element]) ? $config['placeholder'][$element] : '',
								array(
									'placeholder'=>'Placeholder',
									'class'=>'form-control h60 autosize',
								)
							)?></div>
							<?php
							if($element == 'email'){
								echo Html::inputText(
									"format_message[{$element}]",
									isset($config['format_message'][$element]) ?
										$config['format_message'][$element] : (isset($config['label'][$element]) ?
											$config['label'][$element] : isset($config['placeholder'][$element]) ?
												$config['placeholder'][$element] : $element) . '格式错误',
									array(
										'placeholder'=>'格式报错语',
										'class'=>'form-control',
									)
								);
							}?>
						</div>
					</div>
				</div>
			<?php }?>
			<?php foreach($elements as $element => $label){?>
			<?php if(in_array($element, $config['elements'])){
				continue;
			}?>
			<div class="dragsort-item cf hide" id="element-<?php echo $element?>">
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<div class="col-6">
						<div class="ib mb10">
							<strong><?php echo $label?></strong>
						</div>
						<div class="mb5"><?php echo Html::inputText(
							"label[{$element}]",
							isset($config['label'][$element]) ? $config['label'][$element] : '',
							array(
								'placeholder'=>'Label',
								'class'=>'form-control',
							)
						)?></div>
						<?php echo Html::inputText(
							"require_message[{$element}]",
							isset($config['require_message'][$element]) ? $config['require_message'][$element] : '',
							array(
								'placeholder'=>'必填报错语（留空则不做必填验证）',
								'class'=>'form-control',
							)
						)?>
					</div>
					<div class="col-6">
						<div class="mb5"><?php echo Html::textarea(
							"placeholder[{$element}]",
							isset($config['placeholder'][$element]) ? $config['placeholder'][$element] : '',
							array(
								'placeholder'=>'Placeholder',
								'class'=>'form-control h60 autosize',
							)
						)?></div>
						<?php
						if($element == 'email'){
							echo Html::inputText(
								"format_message[{$element}]",
								(isset($config['format_message'][$element]) ?
									$config['format_message'][$element] : isset($config['label'][$element]) ?
										$config['label'][$element] : isset($config['placeholder'][$element]) ?
											$config['placeholder'][$element] : $element) . '格式错误',
								array(
									'placeholder'=>'格式报错语',
									'class'=>'form-control',
								)
							);
						}?>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<h4>渲染模版</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<?php echo Html::textarea('template', isset($config['template']) ? $config['template'] : '', array(
				'class'=>'form-control h90 autosize',
				'id'=>'code-editor',
			))?>
			<p class="fc-grey mt5">
				若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
				即类似<code>frontend/widget/template</code><br />
				则会调用当前application下符合该相对路径的view文件。<br />
				否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
			</p>
		</div>
	</div>
</div>
<script>
var widgetContact = {
	'events': function(){
		$('#widget-contact-element-options').on('change', '.element-options', function(){
			if($(this).is(':checked')){
				$('#element-' + $(this).val()).show();
			}else{
				$('#element-' + $(this).val()).hide();
			}
		})
	},
	'init': function(){
		this.events();
	}
};
widgetContact.init();
</script>