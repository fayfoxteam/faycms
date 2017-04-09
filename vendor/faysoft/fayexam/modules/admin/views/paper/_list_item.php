<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
use fayexam\models\tables\ExamPapersTable;
use fay\helpers\StringHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['title'])?></strong>
        <div class="row-actions">
            <?php 
                echo HtmlHelper::link('编辑', array('fayexam/admin/paper/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('删除', array('fayexam/admin/paper/delete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'fc-red remove-link',
                ), true);
            ?>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data['cat_title']);?></td>
    <td><?php switch($data['status']){
        case ExamPapersTable::STATUS_ENABLED:
            echo '<span class="fc-green">启用</span>';
            break;
        case ExamPapersTable::STATUS_DISABLED:
            echo '<span class="fc-red">禁用</span>';
            break;
    }?></td>
    <td><?php echo StringHelper::money($data['score'])?></td>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
        <?php echo DateHelper::niceShort($data['create_time'])?>
    </abbr></td>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['update_time'])?>">
        <?php echo DateHelper::niceShort($data['update_time'])?>
    </abbr></td>
</tr>