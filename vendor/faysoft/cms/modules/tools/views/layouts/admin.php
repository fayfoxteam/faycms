<?php
use cms\services\file\FileService;
use cms\services\FlashService;
use cms\services\OptionService;
use cms\services\user\UserRoleService;
use cms\services\user\UserService;
use fay\helpers\HtmlHelper;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />

<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/style-responsive.css')?>" />
<?php echo $this->getCss()?>

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<script>
system.base_url = '<?php echo $this->url()?>';
system.assets_url = '<?php echo \F::config()->get('assets_url')?>';
system.user_id = '<?php echo \F::app()->current_user?>';
</script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/fayfox.block.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/common.min.js')?>"></script>
<title><?php echo $subtitle?> | <?php echo OptionService::get('site:sitename')?>后台</title>
</head>
<body id="faycms">
<div class="wrapper">
    <?php include '_sidebar_menu.php'?>
    <div class="container main-content">
        <nav class="user-info-navbar">
            <ul class="user-info-menu fl">
                <li><a href="javascript:" class="toggle-sidebar"><i class="fa fa-bars"></i></a></li>
                <?php
                    $user_roles = UserRoleService::service()->getIds();
                    foreach(F::app()->_top_nav as $nav){
                        if(isset($nav['roles'])){
                            is_array($nav['roles']) || $nav['roles'] = array($nav['roles']);
                            if(!array_intersect($user_roles, $nav['roles'])){
                                continue;
                            }
                        }
                        echo HtmlHelper::link('', array($nav['router']), array(
                            'target'=>isset($nav['target']) ? $nav['target'] : false,
                            'title'=>$nav['label'],
                            'prepend'=>'<i class="'.$nav['icon'].'"></i>',
                            'wrapper'=>array(
                                'tag'=>'li',
                                'class'=>'hover-line',
                            )
                        ));
                    }
                ?>
            </ul>
            <ul class="user-info-menu fr">
            <?php if(F::session()->get('user.id')){?>
                <li class="dropdown-container hover-line message" id="faycms-message">
                    <?php echo HtmlHelper::link('', '#faycms-messages-container', array(
                        'class'=>'dropdown',
                        'prepend'=>array(
                            'tag'=>'i',
                            'class'=>'fa fa-bell-o',
                            'text'=>'',
                        ),
                        'append'=>array(
                            'tag'=>'span',
                            'class'=>'badge badge-green hide',
                            'text'=>'0',
                        )
                    ))?>
                    <ul class="dropdown-menu" id="faycms-messages-container">
                        <li>
                            <ul id="faycms-messages">
                                <li class="faycms-message-item"><span class="faycms-message-container">
                                    <span class="ellipsis" title="">暂无未读信息</span>
                                </span></li>
                            </ul>
                        </li>
                        <li class="last"><?php
                            echo HtmlHelper::link('查看全部', array('cms/admin/notification/my'), array(
                                'target'=>'_blank',
                            ));
                        ?></li>
                    </ul>
                </li>
                <li class="dropdown-container user-profile">
                    <?php $user = UserService::service()->get(\F::app()->current_user, 'avatar,username')?>
                    <a href="#user-profile-menu" class="dropdown"><?php 
                        echo HtmlHelper::img($user['user']['avatar']['thumbnail'], FileService::PIC_THUMBNAIL, array(
                            'class'=>'circle',
                            'width'=>28,
                            'spare'=>'avatar',
                        ))
                    ?><span>您好，<?php echo $user['user']['username']?><i class="fa fa-angle-down"></i></span></a>
                    <ul class="dropdown-menu" id="user-profile-menu">
                        <li><?php
                            echo HtmlHelper::link('我的个人信息', array('cms/admin/profile/index'), array(
                                'prepend'=>array(
                                    'tag'=>'i',
                                    'class'=>'fa fa-user',
                                    'text'=>'',
                                ),
                            ));
                        ?></li>
                        <li class="last"><?php
                            echo HtmlHelper::link('退出', array('cms/admin/login/logout'), array(
                                'prepend'=>array(
                                    'tag'=>'i',
                                    'class'=>'fa fa-lock',
                                    'text'=>'',
                                ),
                            ));
                        ?></li>
                    </ul>
                </li>
                <?php }?>
            </ul>
        </nav>
        <div class="page-title">
            <div class="title-env">
                <h1 class="title"><?php
                    echo isset($subtitle) ? $subtitle : '无标题';
                    if(isset($sublink)){
                        $html_options = isset($sublink['html_options']) ? $sublink['html_options'] : array();
                        $html_options['prepend'] = '<i class="fa fa-link"></i>';
                        if(isset($html_options['class'])){
                            $html_options['class'] .= ' quick-link';
                        }else{
                            $html_options['class'] = 'quick-link';
                        }
                        echo HtmlHelper::link($sublink['text'], $sublink['uri'], $html_options);
                    }?></h1>
            </div>
        </div>
        <?php echo FlashService::get();?>
        <?php echo $content?>
    </div>
</div>
<script>
$(function(){
    //系统消息提示
    if(system.user_id){
        common.headerNotification();
        setInterval(common.headerNotification, 30000);
    }
    
    <?php
        $forms = F::forms();
        foreach($forms as $k=>$f){?>
            common.validformParams.forms['<?php echo $k?>'] = {
                'rules':<?php echo json_encode($f->getJsRules())?>,
                'labels':<?php echo json_encode($f->getLabels())?>,
                'model':'<?php echo $f->getJsModel()?>',
                'scene':'<?php echo $f->getScene()?>'
            };
    <?php }?>
    common.init();
});
</script>
<img src="<?php echo $this->assets('images/throbber.gif" class="hide')?>" />
<img src="<?php echo $this->assets('images/ajax-loading.gif" class="hide')?>" />
<img src="<?php echo $this->assets('images/loading.gif" class="hide')?>" />
</body>
</html>