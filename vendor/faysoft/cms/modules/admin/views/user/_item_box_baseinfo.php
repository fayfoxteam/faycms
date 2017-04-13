<?php
use cms\models\tables\UsersTable;
use fay\helpers\ArrayHelper;

/**
 * @var $user array
 */
?>
<div class="box">
    <div class="box-title">
        <h3>基础信息</h3>
    </div>
    <div class="box-content">
        <div class="form-group">
            <label class="col-2 title">用户名</label>
            <div class="col-10 pt7"><?php echo $user['user']['username']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">昵称</label>
            <div class="col-10 pt7"><?php echo $user['user']['nickname']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">手机</label>
            <div class="col-10 pt7"><?php echo $user['user']['mobile']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">邮箱</label>
            <div class="col-10 pt7"><?php echo $user['user']['email']?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">角色</label>
            <div class="col-10 pt7"><?php
                $role_titles = ArrayHelper::column($user['roles'], 'title');
                echo implode(', ', $role_titles);
                ?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">审核状态</label>
            <div class="col-10 pt7"><?php switch($user['user']['status']){
                    case UsersTable::STATUS_UNCOMPLETED:
                        echo '<span class="fc-blue">用户信息不完整</span>';
                        break;
                    case UsersTable::STATUS_PENDING:
                        echo '<span class="fc-orange">待审核</span>';
                        break;
                    case UsersTable::STATUS_VERIFY_FAILED:
                        echo '<span class="fc-red">未通过审核</span>';
                        break;
                    case UsersTable::STATUS_VERIFIED:
                        echo '<span class="fc-green">通过审核</span>';
                        break;
                    case UsersTable::STATUS_NOT_VERIFIED:
                        echo '<span class="fc-orange">未验证邮箱或手机</span>';
                        break;
                    
                }?></div>
        </div>
        <div class="form-group-separator"></div>
        <div class="form-group">
            <label class="col-2 title">登录</label>
            <div class="col-10 pt7"><?php if($user['user']['block']){
                    echo '<span class="fc-red">限制登录</span>';
                }else{
                    echo '<span class="fc-green">正常登录</span>';
                }?></div>
        </div>
    </div>
</div>