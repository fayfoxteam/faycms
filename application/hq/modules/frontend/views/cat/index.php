
<?php


?>
<link href="<?= $this->staticFile('css/newslist.css') ?>" rel="stylesheet" type="text/css" />



<div class="gyah-min">
    <?php F::widget()->load('cat_posts') ?>
    <div class="gyah-minright">
        <div class="gyah-minrtop">
            <div class="gyah-minrtoptit"><?= $cat['title'] ?></div>

            <div class="gyah-minrtopmin">
                <ul>
                    <?php $listview->showData();?>
                </ul>
        </div>
        </div>
        <div class="clear-20"></div>
        <?php $listview->showPager();?>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){

        $(".suspend").mouseover(function() {
            $(this).stop();
            $(this).animate({width: 140}, 400);
        })

        $(".suspend").mouseout(function() {
            $(this).stop();
            $(this).animate({width: 40}, 400);
        });

    });
</script>
