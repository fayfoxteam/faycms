<?php
use fay\models\Option;
?>
<div class="index-bottom">
    <div class="index-bottommin">
        <?= F::widget()->load('friend-links'); ?>
        <div class="index-bottomcopy">
            <div class="index-bottomcopyL">
                <div class="index-bottomctxt">
                    <span><?= Option::get('sitename') ?></span> <br>
                    SHAOXING UNIVERSITY YUANPEI COLLEGE</div>
            </div>

            <div class="index-bottomcopyL2">学校地址：<?= Option::get('address') ?> <br>
                <?= Option::get('copyright') ?></div>
            <div class="qcode" style="display: none;"></div>
            <div class="index-bottomcopyL3" id="weixin">
                <a href="javascript:;"><img src="<?= $img_url ?>/index_29.png" alt=""/></a>
            </div>
        </div>
    </div>
</div>
