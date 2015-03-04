<?php
use fay\helpers\Html;
use fay\models\tables\Props;
?>
<div class="col-content">
<?php 
	if(!empty($role['props'])){
		foreach($role['props'] as $p){?>
		<div class="form-field">
			<label class="title">
				<?php echo Html::encode($p['title']);?>
				<?php if($p['required']){?>
					<em class="color-red">(必选)</em>
				<?php }?>
			</label>
			<?php 
			switch($p['element']){
				case Props::ELEMENT_TEXT:
					echo Html::inputText("props[{$p['id']}]", '', array(
						'class'=>'w300',
						'data-rule'=>'string',
						'data-params'=>'{max:255}',
						'data-required'=>$p['required'] ? 'required' : false,
						'data-label'=>Html::encode($p['title']),
					));
				break;
				case Props::ELEMENT_RADIO:
					foreach($p['values'] as $k=>$v){
						echo Html::inputRadio("props[{$p['id']}]", $k, false, array(
							'label'=>$v,
							'data-rule'=>'string',
							'data-required'=>$p['required'] ? 'required' : false,
							'data-label'=>Html::encode($p['title']),
						));
					}
				break;
				case Props::ELEMENT_SELECT:
					echo Html::select("props[{$p['id']}]", $p['values'], array(), array(
						'data-rule'=>'int',
						'data-required'=>$p['required'] ? 'required' : false,
						'data-label'=>Html::encode($p['title']),
					));
				break;
				case Props::ELEMENT_CHECKBOX:
					foreach($p['values'] as $k=>$v){
						echo Html::inputCheckbox("props[{$p['id']}][]", $k, false, array(
							'label'=>$v,
							'data-rule'=>'string',
							'data-required'=>$p['required'] ? 'required' : false,
							'data-label'=>Html::encode($p['title']),
						));
					}
				break;
				case Props::ELEMENT_TEXTAREA:
					echo Html::textarea("props[{$p['id']}]", '', array(
						'class'=>'w300',
						'data-rule'=>'string',
						'data-required'=>$p['required'] ? 'required' : false,
						'data-label'=>Html::encode($p['title']),
					));
				break;
			}
			?>
		</div>
	<?php }?>
<?php }?>
</div>