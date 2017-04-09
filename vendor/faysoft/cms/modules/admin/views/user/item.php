<?php
use cms\models\tables\UsersTable;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fay\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-12">
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
        <div class="box">
            <div class="box-title">
                <h3>附加属性</h3>
            </div>
            <div class="box-content">
            <?php
            if(!$user['props']){
                echo '无附加属性';
            }
            $k = 0;
            foreach($user['props'] as $p){
                if($k++){
                    echo HtmlHelper::tag('div', array('class'=>'form-group-separator'), '');
                }?>
                <div class="form-group">
                    <label class="col-2 title"><?php echo $p['title']?></label>
                    <div class="col-10 pt7"><?php
                        if(is_array($p['value'])){
                            if(isset($p['value']['title'])){
                                //单选或下拉
                                echo $p['value']['title'];
                            }else{
                                //多选
                                $values = array();
                                foreach($p['value'] as $k=>$v){
                                    $values[] = HtmlHelper::encode($v['title']);
                                }
                                echo implode(', ', $values);
                            }
                        }else{
                            echo HtmlHelper::encode($p['value']);
                        }
                    ?></div>
                </div>
            <?php }?>
            </div>
        </div>
    </div>
</div>