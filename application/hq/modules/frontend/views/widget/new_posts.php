<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/23
 * Time: 下午8:50
 */
use fay\helpers\Html;
use fay\helpers\String;

?>

<div class="index-minxwzx ">
    <div class="index-mingsjjtit"><?= Html::encode($data['title']) ?>
            <span>
                <a href="<?= $this->url('cat/'. $data['top']) ?>">
                    <img src="<?= $this->staticFile('images') ?>/index_11.png" alt=""/>
                </a>
            </span>
    </div>
    <div class="clear-10"></div>

    <div class="index-minxwzxmin" id="gg" >
        <ul>
            <?php foreach ($posts as $p) { ?>
            <li><i></i>
                <?php
                echo Html::link(String::niceShort($p['title'], 20), array(str_replace('{$id}', $p['id'], $data['uri'])));
                if(!empty($data['date_format'])){
                    echo '<span>'.date($data['date_format'], $p['publish_time']).'</span>';
                }

                ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script>
    $(function(){
        new Marquee("gg",0,1,345,200,100,0,1000,120,0);
    });
</script>
