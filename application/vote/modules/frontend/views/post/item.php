<?php
use fay\helpers\Html;
use fay\models\File;
use fay\models\Prop;
use fay\models\Post;
use fay\helpers\Date;
use fay\models\tables\PropValues;

if (count($posts['props']) > 0)
{
    $props = $posts['props'];
}
//dump($posts);
?>


<div class="row">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->url()?>">主页</a></li>
        <li><a href="javascript:;"><?php echo $posts['title']?>老师</a></li>
        <?php if (F::app()->session->get('id')){ ?>
        <li class="pull-right">用户:
            <span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="最后登录时间:<?php echo Date::niceShort(F::app()->session->get('last_login_time')) ?>" >
                <?php echo F::app()->session->get('nickname'); ?></span>
            <a href="login/logout">退出登录</a></li>
        <?php }else{ ?>
        <li class="pull-right">请到首页登录后进行投票</li>
        <?php } ?>
    </ol>
</div>
<div class="clear-20"></div>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-primary teacher-info">
            <div class="panel-heading">
                基本信息
            </div>
            <div class="panel-body">
                <div class="col-md-5 img">
                    <div class="thumbnail">
                        <?php echo Html::img($posts['thumbnail'], File::PIC_ORIGINAL, array(
                            'width' => '100%',
                            'alt'  => $posts['title'],
                            'title' => $posts['title'],
                        )) ?>
                    </div>
                </div>
                <div class="col-md-7 info">
                    <table class="table table-hover ">
                        <tr>
                            <th>姓名</th>
                            <td><?php echo $posts['title'] ?></td>
                        </tr>
                        <tr>
                            <th>所在院系</th>
                            <td><?php echo Post::model()->getPropValueByAlias('department', $posts['id']) ?></td>
                        </tr>
                        <tr>
                            <th>工作年限(单位:年)</th>
                            <td><?php echo Post::model()->getPropValueByAlias('worktime', $posts['id']) ?></td>
                        </tr>
                        <tr>
                            <th>出生年月</th>
                            <td><?php echo Post::model()->getPropValueByAlias('birthday', $posts['id']) ?></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-5">
        <div class="panel panel-primary fujian">
            <div class="panel-heading">
                附件
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <?php  if (empty($posts['files'])) {
                        echo "暂无附件";
                    }else { ?>
                       <?php foreach ($posts['files'] as $k => $file)
                        { ?>
                            <li class="list-group-item"><span class="glyphicon glyphicon-file"></span>
                                <?php echo Html::link($file['description'], array('file/download', array(
                                    'id' => $file['file_id'],
                                    'name' => 'date'
                                ))); ?></li>
                        <?php } ?>
                    <?php } ?>

                </ul>
            </div>

        </div>
    </div>
    <div class="clear-40"></div>
    <div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">个人简介</div>
      <div class="panel-body">
           <p class="text-indent"><?php echo Post::model()->getPropValueByAlias('personal', $posts['id']) ? : '暂无' ?></p>
      </div>
    </div>
    <div class="panel panel-info">
    <div class="panel-heading">教学理念</div>
      <div class="panel-body">
           <p class="text-indent"><?php echo Post::model()->getPropValueByAlias('concept', $posts['id']) ? : '暂无' ?></p>
      </div>
    </div>

    </div>

<!--留言区域-->
    <div class="clear-30"></div>

            <!-- UY BEGIN -->
            <div id="uyan_frame" class="container"></div>
            <script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=1984360"></script>
            <!-- UY END -->
</div>