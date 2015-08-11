<?php
use fay\helpers\Html;
use fay\helpers\String;
?>
<ul>
    <?php foreach ($posts as $p) { ?>
        <li><i></i>
            <?php
            echo Html::link(String::niceShort($p['title'], 20), array(str_replace('{$id}', $p['id'], $data['uri'])), ['title' => $p['title']]);
            if(!empty($data['date_format'])){
                echo '<span>'.date($data['date_format'], $p['publish_time']).'</span>';
            }

            ?>
        </li>
    <?php } ?>
</ul>