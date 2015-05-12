<?php
use fay\models\Category;
use fay\helpers\Html;
?>
<!--头部代码开始-->
<div class="navbar navbar-fixed-top" style="_position: relative;_z-index: 10000;">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse">
                <ul class="nav" id="navID">

                    <li class="<?= F::session()->get('tab') == 'index' ? 'active' : '' ?>"><a href="<?= $this->url() ?>"><b>首页</b></a></li>
                    <?php
                        $cats = Category::model()->getTree('_system_post');
                        foreach ($cats as $cat)
                        {
                            if(!$cat['is_nav'])continue;
                    ?>
                            <li class="<?= F::session()->get('tab') == $cat['id'] ? 'active' : '' ?>">
                    <?php
                            echo Html::link($cat['title'], array('cat/'.$cat['id']), array(
                                'class'=>'',
                                'title'=>false,
                            ));
                            echo '</li>';
                        }
                    ?>
                    <li class=""><a href="http://ypc.edu.cn" target="_blank"><b>学校主页</b></a></li>
                </ul>
            </div><!--/.nav-collapse -->

            <div class="popup-div tips-div" style="position: absolute;z-index: 10000001;display: none;"></div>

        </div>
    </div>
</div>
<div class="top-div">
    <div class="container clearfix">
        <div class="span10 logo-img">
            <a href="/" title="医疗器械创新网">
                <img src="<?php echo $imgUrl ?>logo.gif" width="350"/>
            </a>
            <span class="fs-26">后勤管理处</span>
        </div>
    </div>
</div>
<!--头部代码结束-->