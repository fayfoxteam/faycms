<?php
/**
 * @var $defence array
 */
?>
<div class="hide">
    <div id="defence-dialog" class="dialog">
        <div class="dialog-content">
            <div class="defence-description"><?php
                if(empty($defence['text_picture'])){
                    echo \fay\helpers\HtmlHelper::img($defence['text_picture']);
                }else{
                    echo '<img src="">';
                }
            ?></div>
        </div>
    </div>
</div>