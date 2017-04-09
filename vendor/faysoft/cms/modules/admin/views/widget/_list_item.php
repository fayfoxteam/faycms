<?php
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 */
$widget = F::widget()->get($data['widget_name'], 'Admin');
?>
<tr valign="top">
    <td>
        <strong><?php
            echo $data['description'] ? HtmlHelper::encode($data['description']) : '&nbsp';
        ?></strong>
        <div class="row-actions"><?php
            echo HtmlHelper::link('编辑', array('cms/admin/widget/edit', array(
                'id'=>$data['id'],
            )), array(), true);
            echo HtmlHelper::link('删除', array('cms/admin/widget/remove-instance', array(
                'id'=>$data['id'],
            )), array(
                'class'=>'fc-red remove-link',
            ), true);
        ?></div>
    </td>
    <td><?php echo $data['alias']?></td>
    <td><?php if($data['enabled']){
        echo '<span class="fc-green">是</span>';
    }else{
        echo '<span class="fc-orange">否</span>';
    }?></td>
    <td><?php if($widget == null){
        echo '<span class="fc-red">小工具已被移除</span>';
    }else{
        echo $widget->title;
    }?></td>
    <td><?php echo $data['widget_name']?></td>
</tr>