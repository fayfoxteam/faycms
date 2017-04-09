<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['title'])?></strong>
        <div class="row-actions">
            <?php 
            echo HtmlHelper::link('编辑', array('cms/admin/analyst-site/edit', array(
                'id'=>$data['id'],
            ) + F::input()->get()), array(), true);
            echo HtmlHelper::link('永久删除', array('cms/admin/analyst-site/delete', array(
                'id'=>$data['id'],
            ) + F::input()->get()), array(
                'class'=>'fc-red remove-link',
            ), true);
            ?>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data['description'])?></td>
</tr>