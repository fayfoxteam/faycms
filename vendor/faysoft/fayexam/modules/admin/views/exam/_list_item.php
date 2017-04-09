<?php
use fay\helpers\HtmlHelper;
use fay\helpers\DateHelper;
?>
<tr valign="top">
    <td>
        <strong><?php echo HtmlHelper::encode($data['paper_title'])?></strong>
        <div class="row-actions">
            <?php
                echo HtmlHelper::link('查看', array('fayexam/admin/exam/item', array(
                    'id'=>$data['id'],
                )), array(), true);
                echo HtmlHelper::link('永久删除', array('fayexam/admin/exam/remove', array(
                    'id'=>$data['id'],
                )), array(
                    'class'=>'fc-red remove-link',
                ), true);
            ?>
        </div>
    </td>
    <td><?php echo HtmlHelper::encode($data[$display_name]);?></td>
    <td><?php echo $data['score'], ' / ', $data['total_score']?></td>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['start_time']), ' - ', DateHelper::format($data['end_time'])?>">
        <?php echo DateHelper::diff($data['start_time'], $data['end_time'])?>
    </abbr></td>
    <td><abbr class="time" title="<?php echo DateHelper::format($data['start_time'])?>">
        <?php echo DateHelper::niceShort($data['start_time'])?>
    </abbr></td>
</tr>