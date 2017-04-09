<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['keyword'])?></strong>
        <div class="row-actions"><?php
            echo HtmlHelper::link('编辑', array('cms/admin/keyword/edit', array(
                'id'=>$data['id'],
            ) + F::input()->get()), array(), true),
            HtmlHelper::link('永久删除', array('cms/admin/keyword/remove', array(
                'id'=>$data['id'],
            ) + F::input()->get()), array(
                'class'=>'fc-red remove-link',
            ), true);
        ?></div>
    </td>
    <td><?php echo $data['link']?></td>
</tr>