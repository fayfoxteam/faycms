<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 15/6/30
 * Time: 下午10:04
 */
use hq\models\ZbiaoRecord;
$biao = ZbiaoRecord::getBiaoName($data['biao_id']);
?>
<tr>
    <td><?= $data['id'] ?></td>
    <td><?= $biao['biao_name'] ?></td>
    <td><?= $data['zongliang'] ?></td>
    <td><?= $data['day_use'] ?></td>
    <td><?= $data['week_num'] ?></td>
    <td><?= $data['month_num'] ?></td>
    <td><?= date('Y-m-d', $data['created']) ?></td>
</tr>