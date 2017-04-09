<?php $boxes_cp = $enabled_boxes;?>
<div class="row">
    <div class="col-6">
        <div class="col-content dragsort" id="dashboard-left">
        <?php 
            if(isset($_settings['dashboard-left'])){
                foreach($_settings['dashboard-left'] as $box){
                    foreach($boxes_cp as $k =>$v){
                        if($box == $v){
                            $ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
                            F::widget()->render($box, array(), $ajax);
                            unset($boxes_cp[$k]);
                            break;
                        }
                    }
                }
            }
        ?>
        </div>
    </div>
    <div class="col-6">
        <div class="col-content dragsort" id="dashboard-right">
            <?php 
            if(isset($_settings['dashboard-right'])){
                foreach($_settings['dashboard-right'] as $box){
                    foreach($boxes_cp as $k =>$v){
                        if($box == $v){
                            $ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
                            F::widget()->render($box, array(), $ajax);
                            unset($boxes_cp[$k]);
                            break;
                        }
                    }
                }
            }
            
            foreach($boxes_cp as $box){
                $ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
                F::widget()->render($box, array(), $ajax);
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script>
common.dragsortKey = 'admin_dashboard_box_sort';
</script>