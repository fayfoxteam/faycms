<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top" id="action-<?php echo $data['id']?>">
    <td>
        <strong><?php 
            echo HtmlHelper::encode($data['cat_title']),
                ' - ',
                HtmlHelper::encode($data['title']);
        ?></strong>
        <div class="row-actions">
            <a href="<?php echo $this->url('cms/admin/action/edit', array('id'=>$data['id']) + F::input()->get())?>">编辑</a>
            <a href="<?php echo $this->url('cms/admin/action/remove', array('id'=>$data['id']) + F::input()->get())?>" class="fc-red remove-link">永久删除</a>
        </div>
    </td>
    <td><?php echo $data['router']?></td>
    <td><?php 
        echo HtmlHelper::encode($data['parent_router']);
        if($data['parent_title']){
            echo HtmlHelper::tag('em', array('wrapper'=>'p'), HtmlHelper::encode("({$data['parent_cat_title']} - {$data['parent_title']})"));
        }
    ?></td>
    <td><?php 
        if($data['is_public'] == 1){
            echo '是';
        }else{
            echo '否';
        }
    ?></td>
</tr>