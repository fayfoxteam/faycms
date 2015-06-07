<?php
use fay\helpers\String;
use fay\helpers\Html;
use fay\models\Post;
$post = Post::model()->get($data['id']);
?>

        <li>
            <div class="gyah-minrtltit"><a href="<?= $this->url('post/'.$data['id']) ?>"><?= $data['title'] ?></a></div>
            <div class="gyah-minrtltime">发布于 <?= date('Y-m-d', $data['publish_time']) ?></div>
            <div class="gyah-minrtltxt">
                <?= String::niceShort($post['content'], 105) ?>
                <span>
                    <?= Html::link('[详情]', ['post/'.$data['id']]) ?>
                </span>
            </div>
        </li>
