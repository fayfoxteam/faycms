<?php 

use fay\models\Option;

$redis = new Redis();
$redis->connect('redis', 6379, 300);
?>
<nav class="footer">
<div class="container">
<div class="row">
<p class="text-center" style="position: relative;"><?php echo Option::get('copyright')?> 
    <?php if (F::session()->get('user_type') == 1 && $redis->exists(getStudentKey(F::session()->get('id')))){?>
        <img src="<?php echo $this->staticFile('img/weixin.png')?>" id="weixin" alt="" width="35" height="35" style="margin-left: 35px;" />
        <img class="wx-img" style="display: none;" src="<?php echo $this->staticFile('img/weixincode.png')?>" alt="" />
        <?php }?>
</p>
</div>

</div>
</nav>

<script>
$(function(){
    $('#weixin').hover(function(){
       $('.wx-img').show();
    }, function(){
        $('.wx-img').hide();
    });
});

</script>
