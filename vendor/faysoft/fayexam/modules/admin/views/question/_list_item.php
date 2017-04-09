<?php
use fay\helpers\HtmlHelper;
use fayexam\models\tables\ExamQuestionsTable;
use fay\helpers\DateHelper;
?>
<tr valign="top">
    <td><?php echo HtmlHelper::inputCheckbox('ids[]', $data['id'], false, array(
        'class'=>'batch-ids',
    ));?></td>
    <td>
        <strong><?php echo strip_tags($data['question'], '<u>')?></strong>
        <div class="row-actions">
            <?php 
                echo HtmlHelper::link('编辑', array('fayexam/admin/question/edit', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('删除', array('fayexam/admin/question/delete', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'fc-red remove-link',
                ), true);
            ?>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data['cat_title']);?></td>
    <td><?php echo HtmlHelper::encode($data['score']);?></td>
    <td><?php switch($data['type']){
        case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
            echo '单选题';
            break;
        case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
            echo '多选题';
            break;
        case ExamQuestionsTable::TYPE_INPUT:
            echo '输入题';
            break;
        case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
            echo '判断题';
            break;
    }?></td>
    <td><?php switch($data['status']){
        case ExamQuestionsTable::STATUS_ENABLED:
            echo '<span class="fc-green">启用</span>';
            break;
        case ExamQuestionsTable::STATUS_DISABLED:
            echo '<span class="fc-red">禁用</span>';
            break;
    }?></td>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['create_time'])?>">
        <?php echo DateHelper::niceShort($data['create_time'])?>
    </abbr></td>
</tr>