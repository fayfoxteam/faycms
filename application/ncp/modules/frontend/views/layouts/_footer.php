<?php
use cms\services\OptionService;
?>
<div class="footer">
    <div class="footer_info"> 
        <div class="about">
            <a href="#" rel="nofollow" target="_blank">找绿谷</a>
            <a href="#" target="_blank">网站地图</a>
            <a href="newncp.html" target="_blank">最新更新</a>
        </div>
        <p><?php
            echo OptionService::get(site.copyright), ' ', OptionService::get(site.beian);
        ?></p>
     </div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>