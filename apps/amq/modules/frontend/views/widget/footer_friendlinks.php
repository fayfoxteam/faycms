<?php
/**
 * @var $links array
 */
?>
<div class="friendlink hidden-xs">
    <dl>
        <dt><a href="http://www.22.cn/link.html" title="更多友情链接">友情链接</a>：</dt>
        <dd><?php foreach($links as $key => $link){?>
            <?php echo \fay\helpers\HtmlHelper::link($link['title'], $link['url'], array(
                'target'=>empty($link['target']) ? false : $link['target'],
                'before'=>$key ? ' | ' : '',
            ));?>
        <?php }?>
        &nbsp;&nbsp;<a href="http://www.22.cn/link.html" title="更多友情链接">更多</a></dd>
    </dl>
</div>