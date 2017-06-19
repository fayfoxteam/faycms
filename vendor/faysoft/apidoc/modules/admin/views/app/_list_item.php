<?php
use fay\helpers\HtmlHelper;

?>
<tr valign="top" id="option-<?php echo $data['id']?>">
    <td>
        <strong><?php echo $data['name']?></strong>
        <div class="row-actions">
            <a href="<?php echo $this->url('apidoc/admin/app/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
            <a href="<?php echo $this->url('apidoc/admin/app/remove', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">永久删除</a>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data['description'])?></td>
    <td><?php echo $data['need_login'] ? '<span class="fc-green">是</span>' : '否'?></td>
</tr>