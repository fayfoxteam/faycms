<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/23
 * Time: 下午9:26
 */
use fay\helpers\Html;
?>

<ul>
    <?php
        foreach ($posts as $p) {
    ?>
    <li><i></i>
        <?php
        echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])));
            if(!empty($data['date_format'])){
                echo '<span>'.date($data['date_format'], $p['publish_time']).'</span>';
            }
        ?>
    </li>
    <?php
    }
    ?>
</ul>