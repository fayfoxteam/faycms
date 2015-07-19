<?php
use w\models\tables\sci;
use fay\helpers\Date;
use fay\helpers\Html;
?>
<tr>
    <td><?php echo $data['id']?></td>
    <td>
        <?php
        echo Html::link($data['name'], array('admin/sci/item', array(
        'id'=> $data['id'],
        )));
        ?>
    </td>
    <td><?php echo $data['short_name']?></td>
    <td><?php echo $data['issn_id']?></td>
    <td><?php echo $data['factor']?></td>
    <td><?php echo $data['research_dir']?></td>
</tr>