<?php
use fay\helpers\HtmlHelper;

?>
<div class="box" id="box-sku" data-name="sku">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>SKU</h4>
    </div>
    <div class="box-content"><?php
        if($props){
            foreach($props as $p){
                if(!$p['is_sale_prop'])continue;?>
                <div class="sku-group form-field" data-name="<?php echo $p['title']?>" data-pid="<?php echo $p['id']?>">
                    <label class="sku-label title bold"><?php echo $p['title']?>：</label>
                    <div class="sku-box">
                        <?php foreach($p['prop_values'] as $pv){
                            $alias = isset($goods['props'][$p['id']]['values'][$pv['id']]) ? $goods['props'][$p['id']]['values'][$pv['id']] : $pv['title'];
                            $checked = isset($goods['props'][$p['id']]['values'][$pv['id']]);?>
                            <p class="ib w240 sku-item">
                                <?php echo HtmlHelper::inputCheckbox("cp_sale[{$p['id']}][]", $pv['id'], $checked, array(
                                    'id'=>"cp-sale-{$p['id']}-{$pv['id']}",
                                    'data-rule'=>'string',
                                    'data-params'=>'{max:255}',
                                    'data-label'=>$p['title'].'属性',
                                    'data-required'=>$p['required'] ? 'required' : false,
                                ))?>
                                <label for="<?php echo "cp-sale-{$p['id']}-{$pv['id']}"?>"><?php echo $pv['title']?></label>
                                <?php echo HtmlHelper::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $alias, array(
                                    'class'=>'form-control mw200 ib fn-hide cp-alias',
                                ))?>
                            </p>
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        <?php }else{?>
            该分类无销售属性
        <?php }?>
        <div id="sku-table-container"></div>
    </div>
</div>