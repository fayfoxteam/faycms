<?php
use fay\helpers\Date;
// dump($pages);
?>


    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->url()?>">主页</a></li>
            <li><a href="javascript:;"><?php echo $pages['title']?></a></li>
            <?php if (F::app()->session->get('id')){ ?>
                <li class="pull-right">用户: <span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="最后登录时间:<?php echo Date::niceShort(F::app()->session->get('last_login_time')) ?>" ><?php echo F::app()->session->get('username'); ?></span> <a
                        href="login/logout">退出登录</a></li>
            <?php }else{ ?>
                <li class="pull-right">请到首页登录后进行投票</li>
            <?php } ?>
        </ol>
    </div>
    <div class="row">
        <div class="header-title text-center">
               <h3><?php echo $pages['title']?></h3>
               <hr />
        </div>
        <p>
            <?php echo $pages['content']?>
            <div class="clear-20"></div>
        </p>
        
         
    </div>
    
   
