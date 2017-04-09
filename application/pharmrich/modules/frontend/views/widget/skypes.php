<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget
 */
?>
<div id="skypes">
    <a href="javascript:;" class="close"></a>
    <div class="wrap">
        <ul><?php
            foreach($widget->config['data'] as $d){
                echo HtmlHelper::link($d['key'], 'skype:'.$d['value'], array(
                    'wrapper'=>'li',
                    'prepend'=>array(
                        'tag'=>'img',
                        'src'=>$this->appAssets('images/skype.png'),
                    )
                ));
            }
        ?></ul>
    </div>
</div>