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
                    <div id="upload-file-container" class="mt5">
                        <?php echo HtmlHelper::link('添加属性', 'javascript:', array(
                            'class'=>'btn',
                            'id'=>'upload-file-link',
                        ))?>
                    </div>
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
                                            <i class="fa fa-check-square-o mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
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
                                            <i class="fa fa-cube mr5"></i><?php echo PropsTable::$element_map[$prop['element']]?>
                                        </span>
                                        <span class="mr10" title="是否必选">
                                            <i class="fa fa-check-square-o mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
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