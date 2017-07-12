<?php
use fay\helpers\HtmlHelper;

/**
 * @var $data array
 */
?>
<tr valign="top" id="option-<?php echo $data['id']?>">
    <td>
        <strong><?php echo $data['name']?></strong>
        <div class="row-actions"><?php
            echo HtmlHelper::link(
                '查看API',
                array('apidoc/admin/api/index',
                    array(
                        'app_id'=>$data['id']
                    )
                ),
                array(),
                true
            );
            echo HtmlHelper::link(
                '查看分类',
                array('apidoc/admin/api-cat/index',
                    array(
                        'app_id'=>$data['id']
                    )
                ),
                array(),
                true
            );
            echo HtmlHelper::link(
                '编辑',
                array('apidoc/admin/app/edit',
                    array(
                        'id'=>$data['id']
                    ) + F::input()->get()
                ),
                array(),
                true
            );
            echo HtmlHelper::link(
                '永久删除',
                array('apidoc/admin/app/remove',
                    array(
                        'id'=>$data['id']
                    ) + F::input()->get(),
                ),
                array(
                    'class'=>'fc-red remove-link',
                ),
                true
            );
        ?></div>
    </td>
    <td><?php echo HtmlHelper::encode($data['description'])?></td>
    <td><?php echo $data['need_login'] ? '<span class="fc-green">是</span>' : '否'?></td>
</tr>