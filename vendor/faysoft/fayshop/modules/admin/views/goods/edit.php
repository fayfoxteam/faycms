<?php
use fayshop\models\tables\GoodsTable;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30"><?php echo F::form()->inputText('title', array(
                'id'=>'title',
                'class'=>'form-control bigtxt',
                'placeholder'=>'在此键入标题',
            ))?></div>
            <div class="mb30 cf"><?php $this->renderPartial('_content')?></div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box">
                <div class="box-title" id="box-operation">
                    <h4>操作</h4>
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
                                GoodsTable::STATUS_ONSALE=>'销售中',
                                GoodsTable::STATUS_INSTOCK=>'放入仓库',
                            ), array(
                                'class'=>'form-control mw100 ib',
                            ), GoodsTable::STATUS_ONSALE);
                        ?>
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
<script type="text/javascript" src="<?php echo $this->assets('fayshop/js/goods.js')?>"></script>
<script>
common.dragsortKey = 'admin_goods_box_sort';
common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat':'goods'});
common.filebrowserFlashUploadUrl = system.url('cms/admin/file/upload', {'cat':'goods'});
goods.boxes = <?php echo json_encode($enabled_boxes)?>;
$(function(){
    goods.skus = <?php echo json_encode($goods['skus'])?>;
    goods.init();
    
});
</script>