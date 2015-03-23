<?php if(!empty($installed)){?>
<div class="notification">
	系统检测到数据库已被安装，若继续此流程，数据库将被覆盖，请谨慎操作！！
</div>
<?php }?>
<h1>系统环境检测</h1>
<p></p>
<?php $error_flag = false;?>
<table class="info-table">
	<tr>
		<th>MySQL</th>
		<td>
			<p><label>主机：</label><?php echo F::app()->config->get('db.host')?></p>
			<p><label>数据库名：</label><?php echo F::app()->config->get('db.dbname')?></p>
		</td>
	</tr>
	<tr>
		<th>读写权限</th>
		<td><?php foreach($writable as $k=>$w){?>
			<p><label class="w300" style="display:block;float:left;"><?php echo $k?></label><?php if($w){
				echo '<span class="color-green">可写</span>';
			}else{
				$error_flag = true;
				echo '<span class="color-red">不可写</span>';
			}?></p>
		<?php }?></td>
	</tr>
	<tr>
		<th>PHP版本</th>
		<td>
			<p>
				<label class="w300" style="display:block;float:left;"><?php echo PHP_VERSION?></label>
				<?php if(version_compare(PHP_VERSION, '5.2.0') == -1){
					$error_flag = true;
					echo '<span class="color-red">PHP版本不能低于5.2.0</span>';
				}?>
			</p>
		</td>
	</tr>
	<tr>
		<th>PHP扩展</th>
		<td>
			<p>
				<label class="w300" style="display:block;float:left;">gd</label>
				<?php if(in_array('gd', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					$error_flag = true;
					echo '<span class="color-red">不支持</span>';
				}?>
			</p>
			<p>
				<label class="w300" style="display:block;float:left;">curl</label>
				<?php if(in_array('curl', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					$error_flag = true;
					echo '<span class="color-red">不支持</span>';
				}?>
			</p>
			<p>
				<label class="w300" style="display:block;float:left;">mbstring</label>
				<?php if(in_array('mbstring', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					$error_flag = true;
					echo '<span class="color-red">不支持</span>';
				}?>
			</p>
			<p>
				<label class="w300" style="display:block;float:left;">pdo_mysql</label>
				<?php if(in_array('pdo_mysql', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					$error_flag = true;
					echo '<span class="color-red">不支持</span>';
				}?>
			</p>
			<p>
				<label class="w300" style="display:block;float:left;">mcrypt<span class="color-grey">(不一定用到)</span></label>
				<?php if(in_array('mcrypt', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					echo '<span class="color-orange">不支持</span>';
				}?>
			</p>
			<p>
				<label class="w300" style="display:block;float:left;">memcache<span class="color-grey">(不一定用到)</span></label>
				<?php if(in_array('memcache', $extensions)){
					echo '<span class="color-green">支持</span>';
				}else{
					echo '<span class="color-orange">不支持</span>';
				}?>
			</p>
		</td>
	</tr>
</table>

<?php if($error_flag){?>
<p class="color-red">您有一项或多项服务器环境配置不符合要求，请搭建好环境后重试</p>
<p><a href="" class="btn-1">重新检查</a></p>
<?php }else{?>
<p><a href="<?php echo $this->url('install/index/doing')?>" class="btn-1">开始安装</a></p>
<?php }?>