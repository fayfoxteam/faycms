<?php
use fay\helpers\Date;
use hq\models\tables\Zbiaos;
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 15/6/3
 * Time: 下午9:33
 */
$type = Zbiaos::getTypeName();
$edit_url = $this->url('admin/tasks/detail?id='.$data['biao_id']);
?>
<tr>
    <td><?= $data['biao_id'] ?></td>
    <td><a href="<?= $edit_url ?>"><?= $data['biao_name'] ?></a></td>
    <td><?= $type[$data['type']] ?></td>
    <td><?= $data['zongzhi'] ?></td>
    <td><?= $data['address'] ?></td>
    <td><?= $data['shuoming'] ?></td>
    <td><?= Date::niceShort($data['updated']) ?></td>
</tr>