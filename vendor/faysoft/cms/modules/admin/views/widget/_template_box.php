<?php
/**
 * 编辑模版的界面大部分是通用的，放哪都不太合适，就先放这里了
 */
use fay\helpers\HtmlHelper;

?>
<div class="box">
    <div class="box-title">
        <h4>渲染模版</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <?php
            $views = \cms\helpers\WidgetHelper::getViews();
            $template = F::form('widget')->getData('template');
            if($views){
                echo HtmlHelper::select(
                    'template',
                    array(
                        ''=>'--默认模版--',
                    ) + array_combine($views, $views) + array(
                        'custom'=>'自定义模版',
                    ),
                    empty($template) ? '' : (in_array($template, $views) ? $template : 'custom'),
                    array(
                        'class'=>'form-control w240 ib mb5',
                        'id'=>'select-template',
                    )
                ), HtmlHelper::tag('span', array(
                    'class'=>'fc-grey'
                ), '（选择<span class="fc-orange">自定义模版</span>可在线编辑模版或指定其它路径的view文件）');
            }
            ?>
            <?php echo F::form('widget')->textarea('template_code', array(
                'class'=>'form-control h90 autosize',
                'id'=>'template-editor',
            ))?>
            <p class="fc-grey mt5">
                若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
                即类似<code>frontend/widget/template</code><br />
                则会调用当前app下符合该相对路径的view文件。<br />
                否则视为php代码<code>eval</code>执行（<code>eval</code>执行php代码必须包含<code>?&gt;</code>结尾）。若留空，会调用默认模版。
            </p>
        </div>
    </div>
</div>
