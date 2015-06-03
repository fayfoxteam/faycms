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
?>
<tr>
    <td><?= $data['biao_id'] ?></td>
    <td><?= $data['biao_name'] ?></td>
    <td><?= $type[$data['type']] ?></td>
    <td><?= $data['zongzhi'] ?></td>
    <td><?= $data['address'] ?></td>
    <td><?= $data['shuoming'] ?></td>
    <td><?= Date::niceShort($data['updated']) ?></td>
</tr>