<?php
use fay\helpers\Html;
use fay\models\tables\Props;
?>
<?php if($prop_set){?>
<?php foreach($prop_set as $prop){?>
	<div class="form-field">
		<label class="title bold">
			<?php echo Html::encode($prop['title']);?>
			<?php if($prop['required']){?>
				<em class="fc-red">(必选)</em>
			<?php }?>
		</label>
		<?php 
		switch($prop['element']){
			case Props::ELEMENT_TEXT:
				echo Html::inputText("props[{$prop['id']}]", isset($prop['value']) ? $prop['value'] : '', array(
					'class'=>'form-control mw500',
					'data-rule'=>'string',
					'data-params'=>'{max:255}',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
				));
			break;
			case Props::ELEMENT_RADIO:
				foreach($prop['values'] as $k=>$v){
					if(!empty($prop['value']) && $prop['value']['id'] == $k){
						$checked = true;
					}else{
						$checked = false;
					}
					echo Html::inputRadio("props[{$prop['id']}]", $k, $checked, array(
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
						'after'=>$v,
					));
				}
				if(!$prop['required']){
					//非比选，多一个清空选项
					echo Html::inputRadio("props[{$prop['id']}]", '', false, array(
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
			case Props::ELEMENT_SELECT:
				echo Html::select("props[{$prop['id']}]", array(''=>'--未选择--')+$prop['values'], isset($prop['value']) ? $prop['value'] : array(), array(
					'datat-rule'=>'int',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
					'class'=>'form-control wa',
				));
			break;
			case Props::ELEMENT_CHECKBOX:
				$values = array();
				if(isset($prop['value'])){
					foreach($prop['value'] as $pv){
						$values[] = $pv['id'];
					}
				}
				foreach($prop['values'] as $k=>$v){
					if(in_array($k, $values)){
						$checked = true;
					}else{
						$checked = false;
					}
					echo Html::inputCheckbox("props[{$prop['id']}][]", $k, $checked, array(
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
						'after'=>$v,
					));
				}
			break;
			case Props::ELEMENT_TEXTAREA:
				echo Html::textarea("props[{$prop['id']}]", isset($data[$prop['id']]) ? $prop['value'] : '', array(
					'class'=>'form-control h90',
					'data-required'=>$prop['required'] ? 'required' : false,
					'data-label'=>$prop['title'],
				));
			break;
		}
		?>
	</div>
<?php }?>
<?php }?>