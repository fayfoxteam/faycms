<?php
use fay\helpers\Html;
use fay\models\tables\Props;
?>
<?php if($props){?>
<?php foreach($props as $p){?>
	<div class="form-field">
		<label class="title bold">
			<?php echo Html::encode($p['title']);?>
			<?php if($p['required']){?>
				<em class="fc-red">(必选)</em>
			<?php }?>
		</label>
		<?php 
		switch($p['element']){
			case Props::ELEMENT_TEXT:
				echo Html::inputText("props[{$p['id']}]", isset($data[$p['id']]) ? $data[$p['id']]['value'] : '', array(
					'class'=>'form-control mw500',
					'data-rule'=>'string',
					'data-params'=>'{max:255}',
					'data-required'=>$p['required'] ? 'required' : false,
					'data-label'=>$p['title'],
				));
			break;
			case Props::ELEMENT_RADIO:
				foreach($p['values'] as $k=>$v){
					if(isset($data[$p['id']]) && !empty($data[$p['id']]['value']) && $data[$p['id']]['value']['id'] == $k){
						$checked = true;
					}else{
						$checked = false;
					}
					echo Html::inputRadio("props[{$p['id']}]", $k, $checked, array(
						'datat-rule'=>'int',
						'data-required'=>$p['required'] ? 'required' : false,
						'data-label'=>$p['title'],
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
				if(!$p['required']){
					echo Html::inputRadio("props[{$p['id']}]", '', false, array(
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
				echo Html::select("props[{$p['id']}]", array(''=>'--未选择--')+$p['values'], isset($data[$p['id']]) ? $data[$p['id']]['value'] : array(), array(
					'datat-rule'=>'int',
					'data-required'=>$p['required'] ? 'required' : false,
					'data-label'=>$p['title'],
					'class'=>'form-control wa',
				));
			break;
			case Props::ELEMENT_CHECKBOX:
				$values = array();
				if(isset($data[$p['id']])){
					foreach($data[$p['id']]['value'] as $pv){
						$values[] = $pv['id'];
					}
				}
				foreach($p['values'] as $k=>$v){
					if(in_array($k, $values)){
						$checked = true;
					}else{
						$checked = false;
					}
					echo Html::inputCheckbox("props[{$p['id']}][]", $k, $checked, array(
						'datat-rule'=>'int',
						'data-required'=>$p['required'] ? 'required' : false,
						'data-label'=>$p['title'],
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
				echo Html::textarea("props[{$p['id']}]", isset($data[$p['id']]) ? $data[$p['id']]['value'] : '', array(
					'class'=>'form-control h90',
					'data-required'=>$p['required'] ? 'required' : false,
					'data-label'=>$p['title'],
				));
			break;
		}
		?>
	</div>
<?php }?>
<?php }?>