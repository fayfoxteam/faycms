<?php
/**
 * @var $this \fay\core\View
 * @var $user array
 * @var $iplocation IpLocation
 */
?>
<div class="row">
    <div class="col-12"><?php
        echo $this->renderPartial('_item_box_baseinfo', array(
            'user'=>$user,
        ));
        echo $this->renderPartial('_item_box_reginfo', array(
            'user'=>$user,
            'iplocation'=>$iplocation,
        ));
        echo $this->renderPartial('_item_box_props', array(
            'user'=>$user,
        ));
    ?></div>
</div>