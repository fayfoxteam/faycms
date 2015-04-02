<?php 
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\String;
// dump($introduce);
?> 


<div class="container">
<h1 class="text-center">教学名师评选 <small>评选截止时间: 2015年06月01日</small></h1>
<p><?php echo Html::encode($introduce['abstract'])?><a href="<?php echo $this->url('page/'.$introduce['id'])?>" class="btn btn-xs btn-primary">查看详细</a></p>

</div>

<hr width="100%" class="full-left" />

<div class="row">
<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-5 col-xs-offset-1 text-center"><h4>候选人名单</h4></div>
    <div class="col-md-2 col-sm-5 col-xs-5">
        <h5><button class="btn btn-sm btn-danger pull-right" data-toggle="modal" id="tooltip" data-placement="top" title="登录，登录，登陆就可以投了" data-target="#login-window" >登录进行投票</button></h5>
    </div>
</div>
<div class="clear-10"></div>
<?php foreach ($lists as $list ){?>
<div class="col-md-3 col-sm-6">
<div class="panel panel-default">
    <a href="<?php echo $this->url('post/'.$list['id'])?>" class="thumbnail">
        <?php echo Html::img($list['id'], File::PIC_RESIZE, array('dw'=>251, 'dh' => 166, 'title'=> $list['title'], 'alt'=> $list['title'], 'data-toggle'=>"tooltip", 'data-placement'=>"bottom", 'title'=>"点击照片查看详情"))?>
    </a>
<div class="panel-body">
    <div class="caption">
        <h5><?php echo $list['title']?></h5>
        <p><?php echo $list['abstract']?></p>
       
        <div class="checkbox">
            <label for="data-id-<?php echo $list['id']?>">
                <input type="checkbox" name="checkout" id="data-id-<?php echo $list['id']?>" /> 
                <span data-toggle="tooltip" data-placement="top" title="选唐晓平，唐晓平，晓平，平。。come on baby">选择</span>
            </label>
            <a href="<?php echo $this->url('post/'.$list['id'])?>" class="btn btn-xs btn-info pull-right" data-toggle="tooltip" data-placement="top" title="点我查看详情">查看详情</a>
        </div>   
        </p>
    </div>
 </div>   
</div>
</div>
<?php }?>


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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">登录</button>
            </div>
        </div>
    </div>
</div>
<hr />