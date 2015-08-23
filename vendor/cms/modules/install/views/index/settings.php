<?php
use fay\models\Option;
?>
<h1>初始化站点信息</h1>
<form method="post">
	<input type="hidden" name="_token" value="<?php echo F::app()->getToken()?>" />
	<table class="form-table">
		<tr>
			<th>站点名称</th>
			<td><input type="text" name="site:sitename" datatype="*1-50" value="<?php echo Option::get('site:sitename')?>" /></td>
			<td><span class="desc Validform_checktip">后期可修改</span></td>
		</tr>
		<tr>
			<th>用户名</th>
			<td><input type="text" name="username" datatype="*1-50" /></td>
			<td><span class="desc Validform_checktip">超级管理员账户，用户名将不可修改</span></td>
		</tr>
		<tr>
			<th>密码</th>
			<td><input type="password" name="password" datatype="*" /></td>
			<td><span class="desc Validform_checktip"></span></td>
		</tr>
		<tr>
			<th>确认密码</th>
			<td><input type="password" name="repassword" datatype="*" recheck="password" /></td>
			<td><span class="desc Validform_checktip"></span></td>
		</tr>
	</table>
	<p><input type="submit" value="提交" class="btn-1" /></p>
</form>
<script type="text/javascript" src="<?php echo $this->assets('js/Validform_v5.3.2_min.js')?>"></script>
<script>
$("form").Validform({
	showAllError:true,
	tiptype:2,
	datatype : {
		"*":/[\w\W]+/,
		"*1-50":/^[\w\W]{1,50}$/
	}
});
</script>