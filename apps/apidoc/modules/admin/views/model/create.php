<?php
$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset

/**
 * @var $this \fay\core\View
 */
?>
<?php echo F::form()->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30"><?php echo F::form()->inputText('name', array(
                'id'=>'title',
                'class'=>'form-control bigtxt',
                'placeholder'=>'模型名称',
            ))?></div>
            <div class="mb30 cf"><?php $this->renderPartial('_description')?></div>
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
<?php if(in_array('props', $enabled_boxes)){
    $this->renderPartial('_add_prop_dialog');
    $this->renderPartial('_edit_prop_dialog');
}?>
<script type="text/javascript" src="<?php echo $this->appAssets('js/model.js')?>"></script>
<script>
$(function(){
    common.dragsortKey = 'admin_model_box_sort';
    model.boxes = <?php echo json_encode($enabled_boxes)?>;
    model.init();
});
</script>