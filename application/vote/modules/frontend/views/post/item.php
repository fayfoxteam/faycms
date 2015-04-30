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
    <div class="col-md-8">
        <div class="panel panel-primary teacher-info">
            <div class="panel-heading">
                基本信息
            </div>
            <div class="panel-body">
                <div class="col-md-4 img">
                    <div class="thumbnail">
                        <?php echo Html::img($posts['thumbnail'], File::PIC_ORIGINAL, array(
                            'width' => '100%',
                            'alt'  => $posts['title'],
                            'title' => $posts['title'],
                        )) ?>
                    </div>
                </div>
                <div class="col-md-8 info">
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
    <div class="col-md-4">
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
                                <?php echo Html::link($file['description'], array(
                                    'id' => $file['file_id'],
                                    'name' => 'date'
                                )); ?></li>
                        <?php } ?>
                    <?php } ?>

                </ul>
            </div>

        </div>
    </div>
    <div class="clear-40"></div>
    <div class="container">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           个人简介
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php echo Post::model()->getPropValueByAlias('personal', $posts['id']) ? : '暂无' ?>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            教学理念
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <?php echo Post::model()->getPropValueByAlias('concept', $posts['id']) ? : '暂无' ?>
                    </div>
                </div>
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