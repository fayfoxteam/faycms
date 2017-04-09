<?php
use fay\services\OptionService;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="image/x-icon" href="<?php echo $this->assets('favicon.ico" rel="shortcut icon')?>" />
<meta content="<?php if(isset($keywords))echo $keywords;?>" name="keywords" />
<meta content="<?php if(isset($description))echo $description;?>" name="description" />
<!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->appAssets('css/login.css')?>" />
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<title><?php if(!empty($title))echo $title . ' | '?><?php echo OptionService::get('site:sitename')?></title>
</head>
<body>
<div id="wrapper">
    <div id="bg"></div>
    <div id="main">
        <div id="login">
            <form id="login-form" method="post">
                <h1 id="logo">SIWI.ME</h1>
                <div id="desc">加入SIWI.ME，和大家一起交流设计，分享快乐吧！</div>
                <fieldset>
                    <label>账号/邮箱</label>
                    <i class="user"></i>
                    <input type="text" name="username" />
                </fieldset>
                <fieldset>
                    <label>密码</label>
                    <i class="lock"></i>
                    <input type="password" name="password" />
                </fieldset>
                <a href="javascript:;" id="login-btn">登录</a>
                <div class="options">
                    <a href="<?php echo $this->url('register')?>" class="register-link">还没注册 现在就去</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo $this->assets('static/siwi/js/cufon-yui.js" type="text/javascript')?>"></script>
<script src="<?php echo $this->assets('static/siwi/fonts/login/siwi_400.font.js" type="text/javascript')?>"></script>
<script type="text/javascript">
Cufon.replace('#desc');
Cufon.replace('#login-btn');
Cufon.replace('label');
Cufon.replace('.register-link');
</script>
<script>
$("fieldset label").each(function(i){
    if($(this).find("input").val() != ""){
        $(this).find("label").hide();
    }
});
$('fieldset').on('focus', 'input', function(){
    $(this).parent().find("label").hide();
}).on('blur', 'input', function(){
    if($(this).val()==""){
        $(this).parent().find("label").show();
    }
});

$("#login-btn").on('click', function(){
    $("#login-form").submit();
});
</script>
</body>
</html>