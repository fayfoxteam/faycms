<?php
use faywiki\models\tables\WikiDocsTable;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<?php echo F::form()->inputHidden('cat_id')?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30"><?php echo F::form()->inputText('title', array(
                'id'=>'title',
                'class'=>'form-control bigtxt',
                'placeholder'=>'在此键入标题',
            ))?></div>
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
                    <div class="misc-pub-section mt6">
                        <strong>状态：</strong>
                        <?php
                            echo F::form()->select('status', array(
                                WikiDocsTable::STATUS_DRAFT => '草稿',
                                WikiDocsTable::STATUS_PENDING => '待审核',
                                WikiDocsTable::STATUS_PUBLISHED => '已发布',
                            ), array(
                                'class'=>'form-control mw100 mt5 ib',
                            ), WikiDocsTable::STATUS_PUBLISHED);
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
                                echo $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                            }else{
                                echo $this->renderPartial('_box_'.$box, $this->getViewData());
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
                            echo $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                        }else{
                            echo $this->renderPartial('_box_'.$box, $this->getViewData());
                        }
                        unset($boxes_cp[$k]);
                    }
                }
            }
            
            //最后多出来的都放最后面
            foreach($boxes_cp as $k=>$box){
                if(isset(F::app()->boxes[$k]['view'])){
                    echo $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                }else{
                    echo $this->renderPartial('_box_'.$box, $this->getViewData());
                }
            }
        ?></div>
    </div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faywiki/js/admin/doc.js')?>"></script>
<script>
$(function(){
    common.dragsortKey = 'admin_wiki_doc_box_sort';
    common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat': 'wiki_doc'});
    doc.boxes = <?php echo json_encode($enabled_boxes)?>;
    doc.docId = <?php echo isset($doc['id']) ? $doc['id'] : 0 ?>;
    doc.init();
});
</script>