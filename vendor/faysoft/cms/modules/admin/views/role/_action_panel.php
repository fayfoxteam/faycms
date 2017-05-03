<?php
/**
 * @var $actions array
 */
?>
<div class="row">
    <div class="col-6">
        <div class="col-content">
        <?php $i = 0;
            foreach($actions as $cat_title => $action){
                $i++;
                if($i > $col_left_count)continue;
        ?>
            <div class="box">
                <div class="box-title">
                    <h4><input type="checkbox" class="select-all" title="全选" /><?php echo $cat_title?></h4>
                </div>
                <div class="box-content">
                <?php foreach($action as $a){?>
                    <span class="w200 ib" title="<?php echo $a['router']?>">
                        <?php echo F::form()->inputCheckbox('actions[]', $a['id'], array(
                            'label'=>$a['title'],
                            'data-parent'=>$a['parent'],
                        ))?>
                    </span>
                <?php }?>
                    <div class="clear"></div>
                </div>
            </div>
        <?php }?>
        </div>
    </div>
    <div class="col-6">
        <div class="col-content">
        <?php $i = 0;
            foreach($actions as $cat_title => $action){
                $i++;
                if($i <= $col_left_count)continue;
        ?>
            <div class="box">
                <div class="box-title">
                    <h4><input type="checkbox" class="select-all" title="全选" /><?php echo $cat_title?></h4>
                </div>
                <div class="box-content">
                <?php foreach($action as $a){?>
                    <span class="w200 ib" title="<?php echo $a['router']?>">
                        <?php echo F::form()->inputCheckbox('actions[]', $a['id'], array(
                            'label'=>$a['title'],
                            'data-parent'=>$a['parent'],
                        ))?>
                    </span>
                <?php }?>
                    <div class="clear"></div>
                </div>
            </div>
        <?php }?>
        </div>
    </div>
</div>