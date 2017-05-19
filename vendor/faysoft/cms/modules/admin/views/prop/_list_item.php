<?php
use fay\helpers\HtmlHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['title'])?></strong>
        <?php if($data['alias']){?>
            <em class="fc-grey">[ <?php echo $data['alias']?> ]</em>
        <?php }?>
        <div class="row-actions"><?php
            echo HtmlHelper::link('编辑', array('cms/admin/prop/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
            echo HtmlHelper::link('删除', array('cms/admin/prop/delete', array(
                    'id'=>$data['id'],
                )), array(
                'class'=>'remove-link fc-red',
            ), true);
        ?></div>
    </td>
    <td><?php echo \cms\models\tables\PropsTable::$element_map[$data['element']]?></td>
    <td><?php echo \cms\services\prop\PropService::service()->getUsageModel($data['usage_type'])->getUsageName()?></td>
    <td><?php echo $data['required'] ? '<span class="fc-green">是</span>' : '否';?></td>
</tr>