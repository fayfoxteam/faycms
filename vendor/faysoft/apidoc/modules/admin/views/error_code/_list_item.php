<?php
use fay\helpers\HtmlHelper;

?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['code'])?></strong>
        <div class="row-actions separate-actions"><?php
            echo HtmlHelper::link('编辑', array('apidoc/admin/error-code/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
            echo HtmlHelper::link('删除', array('apidoc/admin/error-code/remove', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'remove-link fc-red',
                ), true);
        ?></div>
    </td>
    <td><?php echo HtmlHelper::encode($data['description'])?></td>
    <td><?php echo HtmlHelper::encode($data['solution'])?></td>
</tr>