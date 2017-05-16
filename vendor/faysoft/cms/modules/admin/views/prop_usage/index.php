<?php
use cms\models\tables\PropsTable;
use fay\helpers\HtmlHelper;

/**
 * @var $relation_props array
 * @var $props array
 * @var $usage_model \cms\services\prop\PropUsageInterface
 * @var $usage_type int
 */
?>
<?php echo F::form()->open()?>
<?php echo F::form()->inputHidden('cat_id')?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="box" id="box-files" data-name="files">
                <div class="box-title">
                    <h4>属性列表</h4>
                </div>
                <div class="box-content">
                    <?php echo HtmlHelper::link('添加属性', 'javascript:', array(
                        'class'=>'btn',
                        'id'=>'select-prop-link',
                        'data-src'=>'select-prop-dialog',
                    ))?>
                    <div class="dragsort-list">
                        <?php foreach($props as $prop){?>
                            <div class="dragsort-item">
                                <a class="dragsort-rm" href="javascript:"></a>
                                <div class="dragsort-item-container">
                                    <h3 class="ib"><?php echo HtmlHelper::encode($prop['title'])?></h3>
                                    <?php if($prop['alias']){?>
                                    <em class="fc-grey">[ <?php echo $prop['alias']?> ]</em>
                                    <?php }?>
                                    <div class="mt6 mb10 fc-grey">
                                        <span class="mr10" title="表单元素">
                                            <i class="fa fa-cube mr5"></i><?php echo PropsTable::$element_map[$prop['element']]?>
                                        </span>
                                        <span class="mr10" title="是否必选">
                                            <i class="fa <?php echo $prop['required'] ? 'fa-check-square-o' : 'fa-square-o'?> mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="title">排序值：</label>
                                            <?php echo HtmlHelper::inputText(
                                                "sort[{$prop['id']}]",
                                                $prop['sort'],
                                                array(
                                                    'class'=>'form-control ib mw200',
                                                )
                                            )?>
                                        </div>
                                        <div class="col-6">
                                            <label class="title">是否共享：</label>
                                            <?php
                                                echo HtmlHelper::inputRadio(
                                                    "is_share[{$prop['id']}]",
                                                    1,
                                                    !!$prop['is_share'],
                                                    array(
                                                        'label'=>'是',
                                                    )
                                                );
                                                echo HtmlHelper::inputRadio(
                                                    "is_share[{$prop['id']}]",
                                                    0,
                                                    !$prop['is_share'],
                                                    array(
                                                        'label'=>'否',
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php }?>
                        <?php foreach($relation_props as $prop){?>
                            <div class="dragsort-item bl-yellow">
                                <div class="dragsort-item-container">
                                    <h3 class="ib"><?php
                                        echo HtmlHelper::encode($prop['title'])
                                    ?></h3>
                                    <?php if($prop['alias']){?>
                                        <em class="fc-grey">[ <?php echo $prop['alias']?> ]</em>
                                    <?php }?>
                                    <span class="normal">（来自：<?php
                                        echo HtmlHelper::link(
                                            $usage_model->getUsageItemTitle($prop['usage_id']),
                                            array('cms/admin/prop-usage/index', array(
                                                'usage_id'=>$prop['usage_id'],
                                                'usage_type'=>$usage_type,
                                            ))
                                        )
                                    ?>的共享）</span>
                                    <div class="mt6 mb10 fc-grey">
                                        <span class="mr10" title="表单元素">
                                            <i class="fa fa-cube mr5"></i><?php echo \cms\services\prop\ItemPropService::$elementMap[$prop['element']]::getName()?>
                                        </span>
                                        <span class="mr10" title="是否必选">
                                            <i class="fa <?php echo $prop['required'] ? 'fa-check-square-o' : 'fa-square-o'?> mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>排序值：</label>
                                            <?php echo $prop['sort']?>
                                        </div>
                                        <div class="col-6">
                                            <label class="title">是否共享：</label>
                                            是
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php }?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box" id="box-operation">
                <div class="box-title">
                    <h3>操作</h3>
                </div>
                <div class="box-content">
                    <div>
                        <?php echo F::form()->submitLink('提交', array(
                            'class'=>'btn',
                        ))?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo F::form()->close()?>
<div class="hide">
    <div id="select-prop-dialog" class="dialog">
        <div class="dialog-content">
            <h4>属性列表</h4>
            <table class="inbox-table">
                <thead>
                <tr>
                    <th class="w70">ID</th>
                    <th>属性名称</th>
                    <th>表单元素</th>
                    <th>操作</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="select-prop-list-pager" class="pager"></div>
        </div>
    </div>
</div>
<script>
var propUsage = {
    /**
     * 初始化选择属性弹窗
     */
    'dialog': function(){
        common.loadFancybox(function(){
            $('#select-prop-link').fancybox({
                'touch': false,
                'onComplete': function(){
                    propUsage.loadData();
                }
            });
        });
    },
    /**
     * 加载数据
     */
    'loadData': function(page){
        page = page || 1;
        
        
    },
    'init': function(){
        this.dialog();
    }
};
$(function(){
    propUsage.init();
});
</script>