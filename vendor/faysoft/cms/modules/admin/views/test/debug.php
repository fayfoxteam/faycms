<?php
use fay\services\file\FileService;
use fay\helpers\HtmlHelper;
use fay\core\Uri;

$_backtrace = debug_backtrace(false);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Error</title>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prettify.js')?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/debug.css')?>" />
</head>
<body>
<div class="header">
	<h1>Undefined variable: heading</h1>
	<i class="icon"></i>
</div>
<div class="backtrace">
	<h3>Backtrace</h3>
	<?php foreach($_backtrace as $k => $b){?>
		<div <?php if(!$k)echo 'class="act"'?>>
			<div class="element-wrap">
				<p class="function"><span class="index"><?php echo $k+1?>.</span><?php
					if(isset($b['class'])){
						echo "{$b['class']}{$b['type']}{$b['function']}()";
					}else{
						echo "{$b['function']}()";
					}
				?></p>
				<p class="file"><?php echo $b['file'], ':(', $b['line'], ')'?></p>
			</div>
			<div class="code-wrap" <?php if(!$k)echo 'style="display:block"'?>>
				<pre class="prettyprint linenums:<?php echo $b['line'] - 10 < 1 ? 1 : $b['line'] - 10?>" data-line="<?php echo $b['line']?>"><?php
					echo HtmlHelper::encode(FileService::getFileLine($b['file'], $b['line'], 10));
				?></pre>
			</div>
		</div>
	<?php }?>
</div>
<div class="system-data">
	<h3>System Data</h3>
	<table class="data-table">
		<tr>
			<th>APPLICATION</th>
			<td><?php echo APPLICATION?></td>
		</tr>
		<tr>
			<th>Router</th>
			<td><?php echo Uri::getInstance()->router?></td>
		</tr>
		<tr>
			<th>BASEPATH</th>
			<td><?php echo BASEPATH?></td>
		</tr>
		<tr>
			<th>SYSTEM_PATH</th>
			<td><?php echo SYSTEM_PATH?></td>
		</tr>
		<tr>
			<th>PHP_VERSION</th>
			<td><?php echo PHP_VERSION?></td>
		</tr>
	</table>
	<h3>SERVER</h3>
	<table class="data-table">
		<tr>
			<th>OS</th>
			<td><?php echo $_SERVER['OS']?></td>
		</tr>
	<?php foreach($_SERVER as $k => $v){?>
		<tr>
			<th><?php echo $k?></th>
			<td><?php print_r($v)?></td>
		</tr>
	<?php }?>
	</table>
	<h3>Cookies</h3>
	<table class="data-table">
	<?php foreach($_COOKIE as $k => $v){?>
		<tr>
			<th><?php echo $k?></th>
			<td><?php print_r($v)?></td>
		</tr>
	<?php }?>
	</table>
</div>
<script>
$(function(){
	prettyPrint();

	function highlightCurrentLine(){
		$('.prettyprinted').each(function(){
			var firstLine = $(this).find('li:first').attr('value');
			var currentLine = $(this).attr('data-line');
			var offset = parseInt(currentLine) - parseInt(firstLine);
			$(this).find('li:eq('+offset+')').addClass('crt');
		});
	}
	highlightCurrentLine();
	
	$('.backtrace').on('click', '.element-wrap', function(){
		var $parent = $(this).parent();
		if($parent.hasClass('act')){
			$parent.removeClass('act').find('.code-wrap').slideUp();
			return false;
		}
		$('.backtrace').find('.code-wrap').slideUp();
		$('.backtrace > div').removeClass('act');
		$parent.addClass('act').find('.code-wrap').slideDown();
	});
})
</script>
</body>
</html>
