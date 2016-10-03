<?php if(!empty($installed)){?>
<div class="notification">
	系统检测到数据库已被安装，若继续此流程，数据库将被覆盖，请谨慎操作！！
</div>
<?php }?>
<h1>欢迎使用Fayfox建站系统。</h1>
<p>若能正确看到此界面，说明您的基础配置文件已设置正确。下面将开始数据库初始化过程。</p>
<ol>
	<li>系统环境检测</li>
	<li>初始化数据库</li>
	<li>设置超级管理员账号</li>
	<li>完成</li>
</ol>
<p><a href="<?php echo $this->url('install/index/check-system')?>" class="btn-1">系统环境检测</a></p>