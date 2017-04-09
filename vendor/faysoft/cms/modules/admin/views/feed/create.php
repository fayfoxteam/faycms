<?php
use cms\models\tables\FeedsTable;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30 cf"><?php echo F::form()->textarea('content', array(
                'class'=>'h200 form-control autosize',
            ));?></div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box" id="box-operation">
                <div class="box-title">
                    <a class="tools toggle" title="点击以切换"></a>
                    <h3>操作</h3>
                </div>
                <div class="box-content">
                    <div>
                        <?php echo F::form()->submitLink('提交', array(
                            'class'=>'btn',
                        ))?>
                    </div>
                    <div class="misc-pub-section mt6">
                        <strong>状态：</strong>
                        <?php
                            echo F::form()->select('status', array(
                                FeedsTable::STATUS_DRAFT=>'草稿',
                                FeedsTable::STATUS_PENDING=>'待审核',
                                FeedsTable::STATUS_APPROVED=>'通过审核',
                                FeedsTable::STATUS_UNAPPROVED=>'未通过审核',
                            ), array(
                                'class'=>'form-control mw100 mt5 ib',
                            ), FeedsTable::STATUS_APPROVED);
                        ?>
                    </div>
                    <div class="misc-pub-section">
                        <strong>是否置顶？</strong>
                        <?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
                        <?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'), true)?>
                    </div>
                </div>
            </div>
            <?php
                if(isset($_box_sort_settings['side'])){
                    foreach($_box_sort_settings['side'] as $box){
                        $k = array_search($box, $boxes_cp);
                        if($k !== false){
                            if(isset(F::app()->boxes[$k]['view'])){
                                $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                            }else{
                                $this->renderPartial('_box_'.$box, $this->getViewData());
                            }
                            unset($boxes_cp[$k]);
                        }
                    }
                }
            ?>
        </div>
        <div class="postbox-container-2 dragsort" id="normal"><?php 
            if(isset($_box_sort_settings['normal'])){
                foreach($_box_sort_settings['normal'] as $box){
                    $k = array_search($box, $boxes_cp);
                    if($k !== false){
                        if(isset(F::app()->boxes[$k]['view'])){
                            $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                        }else{
                            $this->renderPartial('_box_'.$box, $this->getViewData());
                        }
                        unset($boxes_cp[$k]);
                    }
                }
            }
            
            //最后多出来的都放最后面
            foreach($boxes_cp as $k=>$box){
                if(isset(F::app()->boxes[$k]['view'])){
                    $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                }else{
                    $this->renderPartial('_box_'.$box, $this->getViewData());
                }
            }
        ?></div>
    </div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/feed.js')?>"></script>
<script>
$(function(){
    common.dragsortKey = 'admin_feed_box_sort';
    common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat':'feed'});
    common.filebrowserFlashUploadUrl = system.url('cms/admin/file/upload', {'cat':'feed'});
    feed.boxes = <?php echo json_encode($enabled_boxes)?>;
    feed.init();
});
</script>