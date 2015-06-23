<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/23
 * Time: 下午9:08
 */
use fay\helpers\Html;
use fay\models\File;
?>

<div class="index-mingsjj">
    <div class="index-mingsjjtit"><?= $data['title'] ?><span><a href="<?= $this->url('cat/'. $data['top']) ?>"><img src="<?= $this->staticFile('images') ?>/index_11.png" alt=""/></a></span> </div>
    <div class="clear-10"></div>
    <div class="hot_slider">
        <div class="slider_wrap">
            <div id="slider_box">
                <ul id="contentList">
                    <?php
                        foreach ($posts as $p) {
                    ?>
                            <li>
                                <a href="<?= $this->url('post/'. $p['id']) ?>">
                                    <?= Html::img($p['thumbnail'], File::PIC_THUMBNAIL, ['alt' => $p['title'], 'title' => $p['title']]) ?>
                                </a>
                                <div class="mask"></div>
                                <div class="comt">
                                    <h3><?= $p['title'] ?></h3>
                                </div>
                            </li>
                    <?php
                        }
                    ?>


                </ul>
            </div>
        </div>
    </div>
</div>