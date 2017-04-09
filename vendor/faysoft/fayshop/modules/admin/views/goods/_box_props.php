<?php
use fayshop\models\tables\GoodsCatPropsTable;
use fay\helpers\HtmlHelper;
?>
<div class="box" id="box-props" data-name="props">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>商品属性</h4>
    </div>
    <div class="box-content"><?php
        if($props){
            foreach($props as $p){
                if($p['is_sale_prop'])continue;?>
                <div class="form-field">
                    <label class="title bold">
                        <?php echo HtmlHelper::encode($p['title'])?>
                        <?php if($p['required']){?>
                            <em class="fc-red">(必选)</em>
                        <?php }?>
                    </label>
                    <?php if($p['type'] == GoodsCatPropsTable::TYPE_CHECK){//多选?>
                    <div class="goods-prop-box">
                        <?php foreach($p['prop_values'] as $pv){?>
                            <p class="ib w240">
                            <?php 
                            $alias = isset($goods['props'][$p['id']]['values'][$pv['id']]) ? $goods['props'][$p['id']]['values'][$pv['id']] : $pv['title'];
                            $checked = isset($goods['props'][$p['id']]['values'][$pv['id']]);
                            echo HtmlHelper::inputCheckbox("cp[{$p['id']}][]", $pv['id'], $checked, array(
                                'id'=>"cp-{$p['id']}-{$pv['id']}",
                                'data-rule'=>'int',
                                'data-label'=>$p['title'].'属性',
                                'data-required'=>$p['required'] ? 'required' : false,
                            ));?>
                            <label for="<?php echo "cp-{$p['id']}-{$pv['id']}"?>"><?php echo $pv['title']?></label>
                            <?php 
                            echo HtmlHelper::inputText("cp_alias[{$p['id']}][{$pv['id']}]", $alias, array(
                                'class'=>'form-control mw200 ib fn-hide',
                            ));
                            ?>
                            </p>
                        <?php }?>
                    </div>
                    <?php 
                    }else if($p['type'] == GoodsCatPropsTable::TYPE_OPTIONAL){//单选
                        $selected = isset($goods['props'][$p['id']]) ? array_keys($goods['props'][$p['id']]['values']) : array();
                        echo HtmlHelper::select("cp[{$p['id']}]", HtmlHelper::getSelectOptions($p['prop_values']), $selected, array(
                            'class'=>'form-control wa',
                        ));
                    }else if($p['type'] == GoodsCatPropsTable::TYPE_INPUT){//手工录入
                        $value = isset($goods['props'][$p['id']]['values'][0]) ? $goods['props'][$p['id']]['values'][0] : '';
                        echo HtmlHelper::inputText("cp_alias[{$p['id']}][0]", $value, array(
                            'class'=>'form-control mw500',
                            'data-rule'=>'string',
                            'data-params'=>'{max:255}',
                            'data-label'=>$p['title'].'属性',
                            'data-required'=>$p['required'] ? 'required' : false,
                        ));
                        echo HtmlHelper::inputHidden("cp[{$p['id']}]", 0);
                    }?>
                </div>
            <?php }?>
        <?php }else{?>
            该商品无可选属性
        <?php }?>
    </div>
</div>