<?php
/**
 * @var $user array
 */

$sql = new \fay\core\Sql();
$user_extra = $sql->from(array('ue'=>'guangong_user_extra'), array('birthday', 'sign_up_time', 'rank_id', 'military'))
    ->joinLeft(array('r1'=>'regions'), 'ue.state = r1.id', array('name AS state_name'))
    ->joinLeft(array('r2'=>'regions'), 'ue.city = r2.id', array('name AS city_name'))
    ->joinLeft(array('r3'=>'regions'), 'ue.district = r3.id', array('name AS district_name'))
    ->joinLeft(array('a'=>'guangong_arms'), 'ue.arm_id = a.id', array('name AS arm_name'))
    ->joinLeft(array('r'=>'guangong_ranks'), 'ue.rank_id = r.id', array('captain AS rank_name'))
    ->joinLeft(array('d'=>'guangong_defence_areas'), 'ue.defence_area_id = d.id', array('name AS defence_area_name'))
    ->where('ue.user_id = ?', $user['user']['id'])
    ->fetchRow();
?>
<div class="box">
    <div class="box-title">
        <h3>军籍</h3>
    </div>
    <div class="box-content">
        <div class="form-group">
            <label class="col-2 title">识别号</label>
            <div class="col-10 pt7"><?php echo $user['user']['mobile']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">出生期</label>
            <div class="col-10 pt7"><?php echo $user_extra['birthday']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">所在地</label>
            <div class="col-10 pt7"><?php echo $user_extra['state_name'], ' ', $user_extra['city_name']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">报名期</label>
            <div class="col-10 pt7"><?php echo \fay\helpers\DateHelper::format($user_extra['sign_up_time'])?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">服役期</label>
            <div class="col-10 pt7"><?php
                if($user_extra['sign_up_time']){
                    echo \fay\helpers\DateHelper::format($user_extra['sign_up_time'] + 86400 * 365);
                }
            ?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">防区</label>
            <div class="col-10 pt7"><?php echo $user_extra['district_name']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">兵种</label>
            <div class="col-10 pt7"><?php echo $user_extra['arm_name']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">军职</label>
            <div class="col-10 pt7"><?php echo $user_extra['rank_name']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">军团代号</label>
            <div class="col-10 pt7"><?php echo \guangong\helpers\UserHelper::getCode($user['user']['id'])?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">缴纳军费</label>
            <div class="col-10 pt7"><?php echo $user_extra['military'] / 100?>元</div>
        </div>
    </div>
</div>