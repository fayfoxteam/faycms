<?php
use fay\helpers\HtmlHelper;
use cms\models\tables\PropsTable;
use fay\helpers\ArrayHelper;

/**
 * @var $this \fay\core\View
 */
?>
<?php if(!empty($prop_set)){?>
<?php foreach($prop_set as $prop){?>
    <div class="form-field">
        <label class="title bold prop-title">
            <span><?php echo HtmlHelper::encode(isset($prop['title_alias']) ? $prop['title_alias'] : $prop['title']);?></span>
            <?php if($prop['required']){?>
                <em class="fc-red">(必选)</em>
            <?php }?>
        </label>
        <?php echo HtmlHelper::inputText("labels[{$prop['id']}]", isset($prop['title_alias']) ? $prop['title_alias'] : $prop['title'], array(
            'class'=>'form-control w240 mb5 hide prop-title-editor',
            'data-title'=>HtmlHelper::encode($prop['title']),
        ))?>
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
                $this->renderPartial('file/_upload_image', array(
                    'label'=>'图片',
                    'field'=>"props[{$prop['id']}]",
                    'field_value'=>empty($prop['value']) ? 0 : $prop['value'],
                ));
                break;
        }
        ?>
    </div>
<?php }?>
<?php }?>