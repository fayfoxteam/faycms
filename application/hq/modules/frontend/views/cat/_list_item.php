<?php
use fay\helpers\String;
use fay\helpers\Html;
use fay\models\Post;
$post = Post::model()->get($data['id']);
?>

        <li>
            <a href="<?= $this->url('post/'.$data['id']) ?>">
            <div class="gyah-minrtltit"><?= $data['title'] ?></div>
            <span class="gyah-minrtltime">发布于 <?= date('Y-m-d', $data['publish_time']) ?></span>
            </a>
        </li>
