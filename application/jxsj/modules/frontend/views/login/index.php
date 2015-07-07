<?php
use fay\models\Option;
use fay\models\Flash;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo Option::get('site:sitename')?>--学生登录</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="<?php echo $this->appStatic('css/login.css')?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->appStatic('css/box.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.7.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>

</head>

<body>
<form method="post" action="">
<div id="IndexBox">
	<div id="IndexBoxTitle">学生登录</div>
    <div id="IndexBoxContent">
    	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    	    <tr>
    	        <td height="35" align="right">用户名：</td>
    	        <td><input type="text" name="username" id="Users_LoginName" style="width:140px; height:20px;" /></td>
	        </tr>
    	    <tr>
    	        <td height="35" align="right">密码：</td>
    	        <td><input type="password" name="password" id="Users_LoginPass" style="width:140px; height:20px;" /></td>
	        </tr>
    	    <tr>
    	        <td height="40" colspan="2" align="center"><input type="image" src="<?php echo $this->appStatic('images/Signin.jpg')?>" style="height:22px; width:81px; border:none;" />
 
                    &nbsp;&nbsp;<a href=""><img src="<?php echo $this->appStatic('images/reset.jpg')?>" width="81" height="22" /></a>

                    </td>
	        </tr>
    	    <tr>
    	        <td height="40" colspan="2" align="center"><?php echo Option::get('site:copyright')?></td>
    	        </tr>
	    </table>
    </div>
</div>
</form>
<div id="Boxs" style="display:none">
    <div id="BoxContents"></div>
    <div id="AlphaBox"></div>
</div>
<?php echo Flash::get();?>
</body>
</html>