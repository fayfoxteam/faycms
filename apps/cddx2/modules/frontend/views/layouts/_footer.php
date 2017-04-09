<?php
use cms\services\OptionService;
?>
<footer class="g-ft">
    <div class="w1000">
        <p>
            <span class="ft-cp">
                <label>版权所有：</label>
                <?php echo OptionService::get('site:copyright')?>
            </span>
            <span class="ft-oganizer">
                <label>主办：</label>
                <?php echo OptionService::get('site:oganizer')?>
            </span>
        </p>
        <p>
            <span class="ft-cp-en">
                <?php echo OptionService::get('site:copyright_en')?>
            </span>
        </p>
        <p>
            <span class="ft-address">
                <label>地址：</label>
                <?php echo OptionService::get('site:address')?>
            </span>
            <span class="ft-postcode">
                <label>邮编：</label>
                <?php echo OptionService::get('site:postcode')?>
            </span>
        </p>
    </div>
</footer>