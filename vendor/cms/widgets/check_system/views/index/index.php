<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>系统检测</h4>
	</div>
	<div class="box-content">
		<table class="form-table">
			<tr>
				<th>操作系统</th>
				<td><?php 
					echo PHP_OS;
					if(strtolower(PHP_OS) == 'linux' && $str = @file_get_contents('/etc/system-release')){
						echo ' ', $str;
					}
				?></td>
			</tr>
			<?php if(strtolower(PHP_OS) == 'linux'){
				//内存
				$str = @file_get_contents('/proc/meminfo');
				if($str){
					preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
					preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);
					$mem_total = round($buf[1][0]/1024, 2);
					$mem_free = round($buf[2][0]/1024, 2);
					$mem_cached = round($buf[3][0]/1024, 2);
					$mem_buffers = round($buffers[1][0]/1024, 2);
					$mem_real_used = $mem_total - $mem_free - $mem_cached - $mem_buffers; //真实内存使用
					$mem_real_free = $mem_total - $mem_real_used;//真实空闲
					$mem_real_percent = (floatval($mem_total) != 0) ? round($mem_real_used/$mem_total*100,2) : 0; //真实内存使用率
				}
				
				//CPU
				$str = @file_get_contents("/proc/loadavg");
				if($str){
					$str = explode(' ', $str);
					$str = array_chunk($str, 4);
					$load_avg = implode(' ', $str[0]);
				}
			?>
				<?php if(isset($mem_real_percent)){?>
				<tr>
					<th>物理内存</th>
					<td><?php echo "{$mem_real_used}MB / {$mem_total}MB ({$mem_real_percent}%)";?></td>
				</tr>
				<?php }?>
				<?php if(!empty($load_avg)){?>
				<tr>
					<th>load average</th>
					<td><?php echo $load_avg;?></td>
				</tr>
				<?php }?>
			<?php }?>
			<tr>
				<th>PHP版本</th>
				<td>
					<p><label class="w150 fl block">版本</label><?php echo PHP_VERSION?></p>
					<p><label class="w150 fl block">upload_max_filesize</label><?php echo ini_get('upload_max_filesize')?></p>
					<p><label class="w150 fl block">post_max_size</label><?php echo ini_get('post_max_size')?></p>
				</td>
			</tr>
			<tr>
				<th>MySQL</th>
				<td>
					<p><label class="w150 fl block">版本</label><?php echo $mysql_version?></p>
					<p><label class="w150 fl block">主机</label><?php echo F::app()->config->get('db.host')?></p>
					<p><label class="w150 fl block">数据库名</label><?php echo F::app()->config->get('db.dbname')?></p>
					<p><label class="w150 fl block">编码方式</label><?php echo F::app()->config->get('db.charset')?></p>
				</td>
			</tr>
			<tr>
				<th>读写权限</th>
				<td><?php foreach($writable as $k=>$w){?>
					<p><label class="w150 fl block"><?php echo $k?></label><?php if($w){
						echo '<span class="fc-green">可写</span>';
					}else{
						echo '<span class="fc-orange">不可写</span>';
					}?></p>
				<?php }?></td>
			</tr>
			<tr>
				<th>扩展</th>
				<td>
					<p>
						<label class="w150 fl block">gd</label>
						<?php if(in_array('gd', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">curl</label>
						<?php if(in_array('curl', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">mbstring</label>
						<?php if(in_array('mbstring', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">PDO</label>
						<?php if(in_array('PDO', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">mcrypt</label>
						<?php if(in_array('mcrypt', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">memcache</label>
						<?php if(in_array('memcache', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
					<p>
						<label class="w150 fl block">redis</label>
						<?php if(in_array('redis', $extensions)){
							echo '<span class="fc-green">支持</span>';
						}else{
							echo '<span class="fc-red">不支持</span>';
						}?>
					</p>
				</td>
			</tr>
			<tr>
				<th>Memcache</th>
				<td><?php 
					if(!in_array('memcache', $extensions)){
						echo '<span class="fc-red">扩展未开启</span>';
					}else{
						$version = @F::cache()->getDriver('memcache')->_cache->getVersion();
						if($version){
							echo '版本:', $version;
						}else{
							echo '<span class="fc-red">连接失败</span>';
						}
					}
				?></td>
			</tr>
		</table>
		<div class="clear"></div>
	</div>
</div>