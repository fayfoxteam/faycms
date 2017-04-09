<?php
use cms\services\OptionService;
?>
<?php $this->renderPartial('common/_friendlinks')?>
<footer class="g-ft">
    <div class="w1000">
        <script type="text/javascript">document.write(unescape("%3Cspan id='_ideConac' %3E%3C/span%3E%3Cscript   src='http://dcs.conac.cn/js/23/333/0000/40316402/CA233330000403164020001.js' type='text/javascript'%3E%3C/script%3E"));</script>
        <p class="ft-cp"><?php echo OptionService::get('site:copyright')?></p>
        <p>
            主办：<?php echo OptionService::get('site:organizers')?>
            地址：<?php echo OptionService::get('site:address')?>
            邮编：<?php echo OptionService::get('site:postcode')?>
        </p>
        <p>[<?php echo OptionService::get('site:beian')?>] 技术支持：<a href="http://www.fayfox.com">Fayfox</a></p>
    </div>
</footer>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>