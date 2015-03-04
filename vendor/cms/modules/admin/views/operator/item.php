<?php
use fay\helpers\Date;
use fay\helpers\Html;
?>
<div class="col-1">
	<div class="detail-panel">
		<div class="order-dashboard">
			<div class="bd">
				<div class="trade-status">
					<strong>
						登陆状态：
						<?php if($user['block']){?>
							<span class="color-red">限制登陆</span>
						<?php }else{?>
							<span class="color-green">正常登陆</span>
						<?php }?>
					</strong>
				</div>
				<div class="trade-option">
				<?php 
					if(F::app()->checkPermission('admin/operator/set-status')){
						echo Html::link('设置状态', '#status-dialog', array(
							'title'=>'',
							'id'=>'set-status-link',
							'class'=>'btn-1 fancybox-inline',
						));
					}?>
				</div>
			</div>
		</div>
		<div class="bd">
			<table class="form-table col4">
				<tbody>
					<tr>
						<th>用户名</th>
						<td><?php echo $user['username']?></td>
						<th>邮箱</th>
						<td><?php echo $user['email']?></td>
					</tr>
					<tr>
						<th>手机</th>
						<td><?php echo $user['cellphone']?></td>
						<th>真名</th>
						<td><?php echo $user['realname']?></td>
					</tr>
					<tr>
						<th>注册时间</th>
						<td><?php echo Date::format($user['reg_time'])?></td>
						<th>注册IP</th>
						<td>
							<?php if($user['reg_ip']){?>
							<em class="abbr" title="<?php echo long2ip($user['reg_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['reg_ip']))?></em>
							<?php }?>
						</td>
					</tr>
					<tr>
						<th>最后登陆时间</th>
						<td><?php echo Date::format($user['last_login_time'])?></td>
						<th>最后登陆IP</th>
						<td>
							<?php if($user['last_login_ip']){?>
							<em class="abbr" title="<?php echo long2ip($user['last_login_ip'])?>"><?php echo $iplocation->getCountryAndArea(long2ip($user['last_login_ip']))?></em>
							<?php }?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php if(!empty($user['props'])){?>
		<div class="bd">
			<table class="form-table">
				<tbody>
				<?php foreach($user['props'] as $p){
					if(!$p['is_show'])continue;?>
					<tr>
						<th><?php echo $p['title']?></th>
						<td><?php
						if(is_array($p['value'])){
							if(isset($p['value']['title'])){
								//单选或下拉
								echo $p['value']['title'];
							}else{
								//多选
								$values = array();
								foreach($p['value'] as $k=>$v){
									$values[] = Html::encode($v['value']);
								}
								echo implode(', ', $values);
							}
						}else{
							echo Html::encode($p['value']);
						}
						?></td>
					</tr>
				<?php }?>
				</tbody>
			</table>
		</div>
		<?php }?>
	</div>
</div>
<div class="hide">
	<div id="status-dialog" class="common-dialog">
		<div class="common-dialog-content">
			<h4>设置状态</h4>
			<form action="<?php echo $this->url('admin/operator/set-status')?>" method="post" id="set-status-form">
				<?php echo Html::inputHidden('id', $user['id'])?>
				<div class="form-field">
					<label class="title">登陆状态<span class="color-grey normal"> (设置为限制登陆的用户将无法登陆系统)</span></label>
					<label class="color-green">
						<?php echo Html::inputRadio('block', 0, !$user['block'])?>
						正常
					</label>
					<label class="color-red">
						<?php echo Html::inputRadio('block', 1, $user['block'])?>
						限制登录
					</label>
				</div>
				<div class="form-field">
					<a href="javascript:;" class="btn-1" id="set-status-form-submit">提交修改</a>
					<a href="javascript:;" class="btn-2 fancybox-close">取消</a>
				</div>
			</form>
		</div>
	</div>
</div>