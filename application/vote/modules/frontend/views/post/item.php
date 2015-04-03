<?php
use fay\helpers\Html;
use fay\models\File;
//dump($posts);
if (count($posts['props']) > 0)
{
    $props = $posts['props'];
}
?>

<div class="row">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->url()?>">主页</a></li>
        <li><a href="javascript:;"><?php echo $posts['title']?>老师</a></li>
        <li class="pull-right">是否登录</li>
    </ol>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                基本信息
            </div>
            <div class="panel-body">
                <div class="col-md-4">
                    <div class="thumbnail">
                        <?php echo Html::img($posts['thumbnail'], File::PIC_ORIGINAL, array(
                            'width' => '100%',
                            'alt'  => $posts['title'],
                            'title' => $posts['title'],
                        )) ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-hover">
                        <tr>
                            <th>姓名</th>
                            <td><?php echo $posts['title'] ?></td>
                        </tr>
                        <?php foreach ($props as $prop){?>
                        <tr>
                            <th><?php echo $prop['title'] ?></th>
                            <td><?php echo $prop['value'] ?></td>
                        </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                附件
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">附件1</li>
                    <li class="list-group-item">附件2</li>
                    <li class="list-group-item">附件3</li>
                    <li class="list-group-item">附件4</li>
                    <li class="list-group-item">附件5</li>
                </ul>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="row">
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                教学理念
            </a>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
                教学品德
            </a>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">
                教学改进
            </a>
        </div>
    </div>

    <div class="clear-10"></div>
    <div class="collapse" id="collapseExample1">
        <div class="well">
            <p>
                【教学理念】：以博雅教育为体，素质教育为本，知识传播与能力训练相结合，润物无声地培养学生的批判思维能力，以达到培养学生在学习过程中自我形成创造性思维习惯的目的。在担任外语学院常务副院长期间提出
            </p>
            <a href="" class="btn btn-xs btn-success">查看更多>></a>
        </div>
    </div>
    <div class="collapse" id="collapseExample2">
        <div class="well">
            <p>
                人以学生人生发展为教育、教学为基本出发点，不仅在课堂上传授知识，更重视学生大学期间所学知识在职业发展和人生道路中的潜力， 以及这些知识的生成和对社会的贡献，多年来，本人不仅在课堂上树立学科
            </p>
            <a href="" class="btn btn-xs btn-success">查看更多>></a>
        </div>
    </div>
    <div class="collapse" id="collapseExample3">
        <div class="well">
            <p>
                本人带领德语语言文学专业教学团队取得优异的教学改革成果，形成了德语专业模块教学、交叉学科课程体系， 国际化教学特色，受到学校多次表彰，浙江大学报对本专业的国际化办学特色进行过专版报道。本人.
            </p>
            <a href="" class="btn btn-xs btn-success">查看更多>></a>
        </div>
    </div>

    <div class="clear-30"></div>
    <div class="container">
        <div class="row">
            <!-- UY BEGIN -->
            <div id="uyan_frame"></div>
            <script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=1984360"></script>
            <!-- UY END -->
        </div>
    </div>



</div>