<?php
use fay\helpers\HtmlHelper;
use fay\models\tables\PropsTable;
use fay\helpers\ArrayHelper;
use fay\services\file\FileService;
?>
<?php if(!empty($prop_set)){?>
<?php foreach($prop_set as $prop){?>
	<div class="form-field">
		<label class="title bold">
			<?php echo HtmlHelper::encode($prop['title']);?>
			<?php if($prop['required']){?>
				<em class="fc-red">(必选)</em>
			<?php }?>
		</label>
		<?php 
		switch($prop['element']){
			case PropsTable::ELEMENT_TEXT:
				echo HtmlHelper::inputText("props[{$prop['id']}]", isset($prop['value']) ? $prop['value'] : '', array(
					'class'=>'form-control mw500',
					'data-rule'=>'string',
					'data-params'=>'{max:255}',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
				));
			break;
			case PropsTable::ELEMENT_RADIO:
				foreach($prop['options'] as $k=>$v){
					if(!empty($prop['value']) && $prop['value'] == $v['id']){
						$checked = true;
					}else{
						$checked = false;
					}
					echo HtmlHelper::inputRadio("props[{$prop['id']}]", $v['id'], $checked, array(
						'datat-rule'=>'int',
						'data-required'=>$prop['required'] ? 'required' : false,
						'data-label'=>$prop['title'],
						'wrapper'=>array(
							'tag'=>'label',
							'wrapper'=>array(
								'tag'=>'p',
								'class'=>'ib w240',
							)
						),
						'after'=>$v['title'],
					));
				}
				if(!$prop['required']){
					//非比选，多一个清空选项
					echo HtmlHelper::inputRadio("props[{$prop['id']}]", '', false, array(
						'wrapper'=>array(
							'tag'=>'label',
							'wrapper'=>array(
								'tag'=>'p',
								'class'=>'ib w240',
							)
						),
						'after'=>'--清空此选项--',
					));
				}
			break;
			case PropsTable::ELEMENT_SELECT:
				echo HtmlHelper::select("props[{$prop['id']}]", array(''=>'--未选择--')+ArrayHelper::column($prop['options'], 'title', 'id'), isset($prop['value']) ? $prop['value'] : array(), array(
					'data-rule'=>'int',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
					'class'=>'form-control wa',
				));
			break;
			case PropsTable::ELEMENT_CHECKBOX:
				$checked_values = empty($prop['value']) ? array() : explode(',', $prop['value']);
				foreach($prop['options'] as $k=>$v){
					if(in_array($v['id'], $checked_values)){
						$checked = true;
					}else{
						$checked = false;
					}
					echo HtmlHelper::inputCheckbox("props[{$prop['id']}][]", $v['id'], $checked, array(
						'datat-rule'=>'int',
						'data-required'=>$prop['required'] ? 'required' : false,
						'data-label'=>$prop['title'],
						'wrapper'=>array(
							'tag'=>'label',
							'wrapper'=>array(
								'tag'=>'p',
								'class'=>'ib w240',
							)
						),
						'after'=>$v['title'],
					));
				}
			break;
			case PropsTable::ELEMENT_TEXTAREA:
				echo HtmlHelper::textarea("props[{$prop['id']}]", isset($prop['value']) ? $prop['value'] : '', array(
					'class'=>'form-control h90',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
				));
			break;
			case PropsTable::ELEMENT_NUMBER:
				echo HtmlHelper::inputText("props[{$prop['id']}]", isset($prop['value']) ? $prop['value'] : '', array(
					'class'=>'form-control mw500',
					'data-rule'=>'int',
					'data-params'=>'{max:4294967295}',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
				));
				break;
			case PropsTable::ELEMENT_IMAGE:
				echo HtmlHelper::link('上传图片', 'javascript:;', array(
					'id'=>"upload-prop-{$prop['id']}",
					'class'=>'btn',
					'wrapper'=>array(
						'tag'=>'div',
						'id'=>"prop-{$prop['id']}-container",
						'class'=>'mb10',
					)
				));
				echo "<div id=\"prop-{$prop['id']}-preview-container\">";
				echo F::form()->inputHidden("props[{$prop['id']}]");
				if(!empty($prop['value'])){
					echo HtmlHelper::link(HtmlHelper::img($prop['value'], FileService::PIC_RESIZE, array(
						'dw'=>257,
					)), FileService::getUrl($prop['value']), array(
						'encode'=>false,
						'class'=>'fancybox-image block',
						'title'=>false,
					));
					echo HtmlHelper::link('移除图片', 'javascript:;', array(
						'class'=>'remove-image-link'
					));
				}
				echo '</div>';
				echo "<script>
					system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
						uploader.image({
							'cat': 'post',
							'browse_button': 'upload-prop-{$prop['id']}',
							'container': 'prop-{$prop['id']}-container',
							'preview_container': 'prop-{$prop['id']}-preview-container',
							'input_name': 'props[{$prop['id']}]',
							'remove_link_text': '移除图片'
						});
					});
				</script>";
				break;
		}
		?>
	</div>
<?php }?>
<?php }?>