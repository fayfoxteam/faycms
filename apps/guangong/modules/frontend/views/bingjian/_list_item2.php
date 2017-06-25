<?php
/**
 * @var $data array
 * @var $this \fay\core\View
 */
?>
<a href="<?php echo $this->url('bingjian/user', array(
    'user_id'=>$data['user_id'],
    'type'=>$data['type'],
))?>" class="cf message-item">
    <div class="thumbnail">
        <img src="<?php echo $this->appAssets('images/forum/jian.png')?>">
    </div>
    <div class="content">
        <div class="author">
            <span class="daihao"><?php echo \guangong\helpers\UserHelper::getCode($data['user_id'])?></span>
        </div>
        <?php echo \fay\helpers\HtmlHelper::encode($data['title'])?>
    </div>
</a>