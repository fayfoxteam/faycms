<?php
use cms\models\tables\PropsTable;
use fay\helpers\HtmlHelper;
?>
<?php echo F::form()->inputHidden('refer')?>
<div class="form-field">
    <label class="title bold">属性名称</label>
    <?php echo F::form()->inputText('title', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">属性别名</label>
    <?php echo F::form()->inputText('alias', array(
        'class'=>'form-control mw200',
    ))?>
    <p class="description">特殊属性可能需要通过别名调用，可留空</p>
</div>
<div class="form-field">
    <label class="title bold">是否为必选属性</label>
    <?php echo F::form()->inputCheckbox('required', 1, array(
        'label'=>'必选',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">排序值</label>
    <?php echo F::form()->inputText('sort', array(
        'class'=>'form-control mw150',
    ), 100)?>
</div>
<div class="form-field">
    <label class="title bold">用途</label>
    <?php echo F::form()->select('type', PropsTable::$type_map, array(
        'class'=>'form-control mw150',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">表单元素</label>
    <?php
    echo F::form()->inputRadio('element', PropsTable::ELEMENT_TEXT, array(
        'label'=>'输入框',
    ), true),
    F::form()->inputRadio('element', PropsTable::ELEMENT_NUMBER, array(
        'label'=>'数字输入框',
    )),
    F::form()->inputRadio('element', PropsTable::ELEMENT_RADIO, array(
        'label'=>'单选框',
    )),
    F::form()->inputRadio('element', PropsTable::ELEMENT_SELECT, array(
        'label'=>'下拉框',
    )),
    F::form()->inputRadio('element', PropsTable::ELEMENT_CHECKBOX, array(
        'label'=>'多选框',
    )),
    F::form()->inputRadio('element', PropsTable::ELEMENT_TEXTAREA, array(
        'label'=>'文本域',
    )),
    F::form()->inputRadio('element', PropsTable::ELEMENT_IMAGE, array(
        'label'=>'图片',
    ));
    ?>
</div>
<div class="form-field <?php if(empty($prop['element']) || !in_array($prop['element'], array(
        PropsTable::ELEMENT_RADIO,
        PropsTable::ELEMENT_SELECT,
        PropsTable::ELEMENT_CHECKBOX,
    ))) echo 'hide';?>" id="prop-values-container">
    <label class="title bold">属性值</label>
    <?php echo F::form()->inputText('', array(
        'id'=>'prop-title',
        'class'=>'form-control w200 ib',
    ))?>
    <a href="javascript:" class="btn btn-sm btn-grey" id="add-prop-value-link">添加</a>
    <span class="fc-grey">（添加后可拖拽排序）</span>
    <div class="dragsort-list" id="prop-list">
        <?php if(isset($prop['values']) && is_array($prop['values'])){?>
            <?php foreach($prop['values'] as $pv){?>
                <div class="dragsort-item">
                    <?php echo HtmlHelper::inputHidden('ids[]', $pv['id'])?>
                    <a class="dragsort-rm" href="javascript:"></a>
                    <a class="dragsort-item-selector"></a>
                    <div class="dragsort-item-container">
                        <?php echo F::form()->inputText("prop_values[]", array(
                            'data-rule'=>'string',
                            'data-params'=>'{max:255}',
                            'data-label'=>'属性值',
                            'data-required'=>'required',
                            'class'=>'form-control',
                        ), $pv['title'])?>
                    </div>
                </div>
            <?php }?>
        <?php }?>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
    $('#add-prop-value-link').on('click', function(){
        var $propTitle = $('#prop-title');
        var $propList = $('#prop-list');
        if($propTitle.val() == ''){
            common.alert('属性值不能为空！');
            return false;
        }
        $propList.append(['<div class="dragsort-item hide">',
            '<input type="hidden" name="ids[]" value="" />',
            '<a class="dragsort-rm" href="javascript:"></a>',
            '<a class="dragsort-item-selector"></a>',
            '<div class="dragsort-item-container">',
                '<input type="text" name="prop_values[]" value="'+system.encode($propTitle.val())+'" data-label="属性值" data-rule="string" data-params="{max:255}" class="form-control" />',
            '</div>',
        '</div>'].join(''));
        $propList.find('.dragsort-item:last').fadeIn();
        $propTitle.val('');
    });

    $('input[name="element"]').change(function(){
        if($(this).val() == <?php echo PropsTable::ELEMENT_RADIO?> ||
            $(this).val() == <?php echo PropsTable::ELEMENT_SELECT?> ||
            $(this).val() == <?php echo PropsTable::ELEMENT_CHECKBOX?>){
            $('#prop-values-container').show();
        }else{
            $('#prop-values-container').hide();
        }
    });

    $('.edit-sort').feditsort({
        'url':system.url('cms/admin/post-prop/sort')
    });
});
</script>