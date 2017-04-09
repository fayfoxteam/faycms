<?php
use fay\helpers\HtmlHelper;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
?>
<?php echo F::form('setting')->open(array('admin/system/setting'))?>
    <?php echo F::form('setting')->inputHidden('_key')?>
    <div class="form-field">
        <label class="title bold">显示下列项目</label>
        <?php 
        foreach(F::app()->boxes as $box){
            echo HtmlHelper::inputCheckbox('boxes[]', $box['name'],
                isset($enabled_boxes) && in_array($box['name'], $enabled_boxes) ? true : false, array(
                    'label'=>$box['title'],
            ));
        }
        ?>
    </div>
    <div class="form-field">
        <?php echo F::form('setting')->submitLink('提交', array(
            'class'=>'btn',
        ))?>
    </div>
<?php echo F::form('setting')->close()?>