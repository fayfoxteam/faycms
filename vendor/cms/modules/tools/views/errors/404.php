<?php
/**
 * 用于fay\core\HttpException显示404报错页面
 * 注：main.php中environment为production时候显示该页面，否则显示debug页面
 * 您可以在自己项目中创建views/errors/404.php文件用于覆盖该页面
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Error</title>
<style type="text/css">
::selection{background-color:#E13300;color:white;}
::moz-selection{background-color:#E13300;color:white;}
::webkit-selection{background-color:#E13300;color:white;}
body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica, Arial, sans-serif;color:#4F5155;}
a{color:#003399;background-color:transparent;font-weight:normal;}
h1{color:#444;background-color:transparent;border-bottom:1px solid #D0D0D0;font-size:19px;font-weight:bold;margin:0 0 14px 0;padding:14px 15px 10px 0;}
code{font-family:Consolas, Monaco, Courier New, Courier, monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #D0D0D0;color:#002166;display:block;margin:14px 0 14px 0;padding:12px 10px 12px 10px;word-wrap:break-word; white-space:pre-wrap; }
#container{margin:10px;border:1px solid #D0D0D0;-webkit-box-shadow:0 0 8px #D0D0D0;padding:12px 15px;}
p, pre, code, .track-table{margin:12px 15px 12px 15px;}
.trace-table{width:100%;border-top:1px solid #D0D0D0;}
.trace-table th{text-align:left;padding:6px;}
.trace-table th, .trace-table td{padding-left:12px;}
.content{padding:12px 0;}
</style>
</head>
<body>
	<div id="container">
		<h1>404</h1>
		<div class="content">您访问的页面不存在</div>
	</div>
</body>
</html>