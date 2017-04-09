<?php
use fay\helpers\HtmlHelper;

$widget_instance = F::widget()->get($widget['widget_name'], true);
?>
<div class="widget-item <?php if(!$widget['enabled'])echo 'bl-yellow'?>" data-widget-id="<?php echo $widget['id']?>">
    <a class="widget-item-selector"></a>
    <div class="widget-item-container">
    <?php if($show_alias){?>
        <strong><?php echo HtmlHelper::tag('span', array(
            'title'=>'小工具实例描述',
        ), $widget['description'] ? $widget['description'] : '无描述'), ' - ', HtmlHelper::tag('span', array(
            'title'=>'小工具实例别名',
        ), $widget['alias'])?></strong>
        <span class="operations"><?php
            echo HtmlHelper::link('编辑', array('cms/admin/widget/edit', array(
                'id'=>$widget['id'],
            )), array(), true);
            echo HtmlHelper::link('删除', array('cms/admin/widget/remove-instance', array(
                'id'=>$widget['id'],
            )), array(
                'class'=>'fc-red remove-link',
            ), true);
            echo HtmlHelper::link('复制', array('cms/admin/widget/copy', array(
                'id'=>$widget['id'],
            )), array(), true);
        ?></span>
        <p class="fc-grey"><?php echo HtmlHelper::tag('span', array(
            'title'=>'小工具名称',
        ), $widget_instance->title), ' - ', $widget['widget_name']?></p>
    <?php }else{?>
        <?php echo HtmlHelper::tag('strong', array(
            'title'=>'小工具实例描述',
        ), $widget['description'] ? $widget['description'] : '无描述');
        echo HtmlHelper::tag('span', array(
            'title'=>'小工具名称',
            'class'=>'fc-grey',
        ), ' （'.$widget_instance->title.'）')?>
        <span class="operations"><?php
            echo HtmlHelper::link('编辑', array('cms/admin/widget/edit', array(
                'id'=>$widget['id'],
            )), array(), true);
            echo HtmlHelper::link('删除', array('cms/admin/widget/remove-instance', array(
                'id'=>$widget['id'],
            )), array(
                'class'=>'fc-red remove-link',
            ), true);
            echo HtmlHelper::link('复制', array('cms/admin/widget/copy', array(
                'id'=>$widget['id'],
            )), array(), true);
        ?></span>
    <?php }?>
    </div>
</div>