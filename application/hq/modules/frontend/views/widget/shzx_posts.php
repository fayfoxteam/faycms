<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/23
 * Time: 下午9:26
 */
use fay\helpers\Html;
use fay\helpers\String;

?>

<ul>
    <?php
    foreach ($posts as $p) {
        ?>
        <li><i></i>
            <?php
            echo Html::link(String::niceShort($p['title'], 20), array(str_replace('{$id}', $p['id'], $data['uri'])));
            if(!empty($data['date_format'])){
                echo '<span>'.date($data['date_format'], $p['publish_time']).'</span>';
            }
            ?>
        </li>
        <?php
    }
    ?>
</ul>