<?php
use fay\models\tables\Users;
use fay\helpers\Date;
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<div class="box">
			<div class="box-title">
				<h3>基础信息</h3>
			</div>
			<div class="box-content">
				<div class="form-group">
					<label class="col-2">用户名</label>
					<div class="col-10 pt7"><?php echo $user['username']?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">昵称</label>
					<div class="col-10 pt7"><?php echo $user['nickname']?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">手机</label>
					<div class="col-10 pt7"><?php echo $user['mobile']?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">邮箱</label>
					<div class="col-10 pt7"><?php echo $user['email']?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">审核状态</label>
					<div class="col-10 pt7"><?php switch($user['status']){
						case Users::STATUS_UNCOMPLETED:
							echo '<span class="fc-blue">用户信息不完整</span>';
						break;
						case Users::STATUS_PENDING:
							echo '<span class="fc-orange">待审核</span>';
						break;
						case Users::STATUS_VERIFY_FAILED:
							echo '<span class="fc-red">未通过审核</span>';
						break;
						case Users::STATUS_VERIFIED:
							echo '<span class="fc-green">通过审核</span>';
						break;
						case Users::STATUS_NOT_VERIFIED:
							echo '<span class="fc-orange">未验证邮箱或手机</span>';
						break;
							
					}?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">登录</label>
					<div class="col-10 pt7"><?php if($user['block']){
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
					<label class="col-2">注册时间</label>
					<div class="col-10 pt7"><?php echo Date::format($user['reg_time'])?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">注册IP</label>
					<div class="col-10 pt7"><?php if($user['reg_ip']){?>
						<em class="abbr" title="<?php echo long2ip($user['reg_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['reg_ip']))?></em>
					<?php }?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">最后登陆时间</label>
					<div class="col-10 pt7"><?php echo Date::format($user['last_login_time'])?></div>
				</div>
				<div class="form-group-separator"></div>
				<div class="form-group">
					<label class="col-2">最后登陆IP</label>
					<div class="col-10 pt7"><?php if($user['last_login_ip']){?>
						<em class="abbr" title="<?php echo long2ip($user['last_login_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['last_login_ip']))?></em>
					<?php }?></div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="box-title">
				<h3>附加属性</h3>
			</div>
			<div class="box-content">
			<?php
			$k = 0;
			foreach($user['props'] as $p){
				if($k++){
					echo Html::tag('div', array('class'=>'form-group-separator'), '');
				}?>
				<div class="form-group">
					<label class="col-2"><?php echo $p['title']?></label>
					<div class="col-10 pt7"><?php
						if(is_array($p['value'])){
							if(isset($p['value']['title'])){
								//单选或下拉
								echo $p['value']['title'];
							}else{
								//多选
								$values = array();
								foreach($p['value'] as $k=>$v){
									$values[] = Html::encode($v['title']);
								}
								echo implode(', ', $values);
							}
						}else{
							echo Html::encode($p['value']);
						}
					?></div>
				</div>
			<?php }?>
			</div>
		</div>
	</div>
</div>