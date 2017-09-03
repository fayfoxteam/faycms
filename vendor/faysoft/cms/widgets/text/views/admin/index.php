<div class="mb30"><?php
    echo F::form('widget')->textarea('content', array(
        'id'=>'visual-editor',
        'class'=>'h200 visual-simple',
    ));
?></div>
<?php echo F::app()->view->renderPartial('admin/widget/_template_box')?>
