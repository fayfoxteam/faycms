<?php
use fay\helpers\HtmlHelper;
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
                        <?php if(!empty($props)){?>
                            <?php foreach($props as $prop){?>
                                <div class="dragsort-item">
                                    <a class="dragsort-rm" href="javascript:"></a>
                                    <a class="dragsort-item-selector"></a>
                                    <div class="dragsort-item-container">
                                        
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?php }?>
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