<?php
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 */
?>
<tr valign="top" id="tag-<?php echo $data['id']?>">
    <td>
        <strong><?php echo HtmlHelper::encode($data['title'])?></strong>
        <div class="row-actions"><?php
            echo HtmlHelper::link('查看文章', array('cms/admin/post/index', array(
                'tag_id'=>$data['id'],
            )));
            echo HtmlHelper::link('编辑', array('cms/admin/tag/edit', array(
                'id'=>$data['id'],
            )), array(), true);
            echo HtmlHelper::link('永久删除', array('cms/admin/tag/remove', array(
                'id'=>$data['id'],
            )), array(
                'class'=>'fc-red remove-link',
            ), true);
        ?>
        </div>
    </td>
    <td><?php echo $data['posts']?></td>
    <td><?php echo HtmlHelper::inputText("sort[{$data['id']}]", $data['sort'], array(
        'size'=>3,
        'maxlength'=>3,
        'data-id'=>$data['id'],
        'class'=>'form-control tag-sort w50',
    ))?></td>
</tr>