<?php
use fay\helpers\HtmlHelper;

/**
 * @var $user array
 */
?>
<div class="box">
    <div class="box-title">
        <h3>附加属性</h3>
    </div>
    <div class="box-content">
        <?php
        if(!$user['props']){
            echo '无附加属性';
        }
        $k = 0;
        foreach($user['props'] as $p){
            if($k++){
                echo HtmlHelper::tag('div', array('class'=>'form-group-separator'), '');
            }?>
            <div class="form-group">
                <label class="col-2 title"><?php echo $p['title']?></label>
                <div class="col-10 pt7"><?php
                    if(is_array($p['value'])){
                        if(isset($p['value']['title'])){
                            //单选或下拉
                            echo $p['value']['title'];
                        }else{
                            //多选
                            $values = array();
                            foreach($p['value'] as $k=>$v){
                                $values[] = HtmlHelper::encode($v['title']);
                            }
                            echo implode(', ', $values);
                        }
                    }else{
                        echo HtmlHelper::encode($p['value']);
                    }
                    ?></div>
            </div>
        <?php }?>
    </div>
</div>