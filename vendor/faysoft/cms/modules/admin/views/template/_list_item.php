<?php
use fay\helpers\HtmlHelper;
use fay\services\TemplateService;
?>
<tr>
    <td>
        <strong><?php echo HtmlHelper::encode($data['alias'])?></strong>
        <div class="row-actions">
            <?php echo HtmlHelper::link('编辑', array('cms/admin/template/edit', array('id'=>$data['id'])))?>
            <?php echo HtmlHelper::link('删除', array('cms/admin/template/delete', array('id'=>$data['id'])), array(
                'class'=>'fc-red remove-link',
            ))?>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data['description'])?></td>
    <td><?php echo $data['enable'] ? HtmlHelper::link('', 'javascript:;', array(
        'class'=>'tick-circle is-enable-link',
        'data-id'=>$data['id'],
        'encode'=>false,
    )) : HtmlHelper::link('', 'javascript:;', array(
        'class'=>'cross-circle is-enable-link',
        'data-id'=>$data['id'],
        'encode'=>false,
    ));?></td>
    <td><?php echo Template::getType($data['type'])?></td>
</tr>