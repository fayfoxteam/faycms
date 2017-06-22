<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<title>可视化编辑 | <?php echo \cms\services\OptionService::get('site:sitename')?>后台</title>
<style>
*{border:0 none;margin:0;outline:0 none;padding:0}
html,body{overflow:hidden;height:100%}
#customize-iframe{width:100%;height:100%}
</style>
</head>
<body>
<iframe src="<?php echo $this->url()?>" id="customize-iframe" name="customize-iframe"></iframe>
<script>
$(function(){
    
});
</script>
</body>
</html>