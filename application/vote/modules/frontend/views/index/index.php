<?php 
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\String;
use fay\helpers\Date;

// dump($lists);
$redis = new Redis();
$redis->connect('redis', 6379, 300);

?> 


<div class="container">
<h2 class="text-center">教学名师评选 <small>评选截止时间: 2015年06月01日</small></h2>
<p><?php echo Html::encode($introduce['abstract'])?><a href="<?php echo $this->url('page/'.$introduce['id'])?>" class="btn btn-xs btn-primary">查看详细</a></p>

</div>

<hr width="100%" class="full-left" />

<div class="row">
<div class="row">
    <div class="col-md-8 col-sm-6 col-xs-5 col-xs-offset-1 text-center"><h4>候选人名单</h4></div>
    <div class="col-md-3 col-sm-5 col-xs-5 text-right">
        <h5>
            <?php if (F::app()->session->get('id')){ ?>
                <a href="">用户: <span class="label label-default" data-toggle="tooltip" data-placement="top" title="最后登录时间:<?php echo Date::niceShort(F::app()->session->get('last_login_time')) ?>" >
                <?php echo F::app()->session->get('nickname')?:F::app()->session->get('username'); ?></span> </a>
                <a href="login/logout">退出登录</a>
           <?php }else{ ?>
            <button class="btn btn-sm btn-danger pull-right" data-toggle="modal" data-target="#login-window" >登录进行投票</button>
            <?php } ?>
        </h5>
    </div>
</div>
<div class="clear-10"></div>
<?php
shuffle($lists);
 foreach ($lists as $list ){?>
<div class="col-md-3 col-sm-6 teach-list">
<div class="panel panel-default panel-until">
    <a href="<?php echo $this->url('post/'.$list['id'])?>" class="thumbnail">
        <?php echo Html::img($list['thumbnail'], File::PIC_ORIGINAL, array('width'=>'100%', 'title'=> $list['title'], 'alt'=> $list['title'], 'data-toggle'=>"tooltip", 'data-placement'=>"bottom", 'title'=>"点击照片查看详情"))?>
    </a>
<div class="panel-body">
    <div class="caption">
        <h5><?php echo $list['title']?></h5>
        <p><?php echo $list['abstract']?></p>
       
        <div class="checkbox">
            <label for="data-id-<?php echo $list['id']?>">
                <input type="checkbox" name="checkout" class="checked" id="data-id-<?php echo $list['id'] ?>" data-id="<?php echo $list['id']?>" />
                <span data-toggle="tooltip" data-placement="top" title="点击选择<?php echo $list['title'] ?>老师">选择</span>
            </label>
            <a href="<?php echo $this->url('post/'.$list['id'])?>" class="btn btn-xs btn-info pull-right">查看详情</a>
        </div>   
        </p>
    </div>
 </div>   
</div>
</div>

<?php }?>


    <?php if (F::app()->session->get('id')){ ?>
<div class="container">

<!--     <div class="checkbox"> -->
<!--         <label> -->
<!--           <input type="checkbox" id="checkAll"> 全选 -->
<!--         </label> -->
<!--     </div> -->
    <?php
        if ($user_id = F::session()->get('id'))
        {
            if ($redis->exists(getStudentKey($user_id)))
            {
               ?>
               <div class="btn btn-warning form-control" disabled>您已经投过了，只有一次机会的哦</div>
        <?php }else { ?>
            <div class="btn btn-primary form-control" id="vote_submit">投票</div>
        <?php } } ?>
   
</div>
    <?php } ?>

</div>

<div class="modal fade" id="login-window" tabindex="-1" role="dialog" aria-labelledby="login-title" aria-hidden="true" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="login-title">登录</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">用户名:</label>
                    <input type="text" class="form-control" id="username" name="username" />
                </div>
                <div class="form-group">
                    <label for="password">密码:</label>
                    <input type="password" name="password" id="password" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="vcode">验证码</label>
                    <?php echo F::form()->inputText('vcode', array(
									'class'=>'form-control',
                                    'id' => 'vcode'
								));
								echo Html::img($this->url('file/vcode', array(
									'w'=>128,
									'h'=>30
								)).'?', 1, array(
									'onClick'=>'this.src=this.src+Math.random()',
									'class'=>'vam mt-10',
								));?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="login_in">登录</button>
            </div>
        </div>
    </div>
</div>
<hr />

<script src="<?php echo $this->staticFile('js/jquery-check-all.min.js')?>"></script>
