<?php
//dump($links);
?>
<ul class="clearfix">
    <?php foreach ($links as $link){ ?>
    <li><a href="<?= $link['url'] ?>" target="<?= $link['target'] ?>"><?php echo $link['title'] ?></a></li>
    <?php } ?>
</ul>