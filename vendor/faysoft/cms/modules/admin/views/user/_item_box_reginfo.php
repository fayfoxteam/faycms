<?php
use fay\helpers\DateHelper;

/**
 * @var $this \fay\core\View
 * @var $user array
 * @var $iplocation IpLocation
 */
?>
<div class="box">
    <div class="box-title">
        <h3>注册信息</h3>
    </div>
    <div class="box-content">
        <div class="form-group">
            <label class="col-2 title">注册时间</label>
            <div class="col-10 pt7"><?php echo DateHelper::format($user['profile']['reg_time'])?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">注册IP</label>
            <div class="col-10 pt7"><?php if($user['profile']['reg_ip']){?>
                    <em class="abbr" title="<?php echo long2ip($user['profile']['reg_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['profile']['reg_ip']))?></em>
                <?php }?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">最后登陆时间</label>
            <div class="col-10 pt7"><?php echo DateHelper::format($user['profile']['last_login_time'])?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">最后登陆IP</label>
            <div class="col-10 pt7"><?php if($user['profile']['last_login_ip']){?>
                    <em class="abbr" title="<?php echo long2ip($user['profile']['last_login_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['profile']['last_login_ip']))?></em>
                <?php }?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">最后在线时间</label>
            <div class="col-10 pt7"><?php echo DateHelper::format($user['profile']['last_time_online'])?></div>
        </div>
    </div>
</div>