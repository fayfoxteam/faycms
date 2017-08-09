<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('category-post-list')?>
    </div>
    <!--左边内容over-->
    
    <!--右边内容start-->
    <div class="amc-aside hidden-xs" id="startBottom">
        <!--搜索框start-->
        <?php echo $this->renderPartial('common/search_form')?>
        <!--搜索框over-->

        <div class="amc-fix fix-bottom">
            <?php F::widget()->area('list-sidebar-fixed')?>
        </div>
        <?php F::widget()->area('list-sidebar')?>
    </div>
    <!--右边内容over-->
    
    <!--侧边栏start-->
    <ul class="amc-bside">
        <li class="amc-wx"><img src="<?php echo $this->appAssets('images/ewm.png')?>" alt="" class="amc-ewm"></li>
        <li class="retop"></li>
    </ul>
    <!--侧边栏over-->
</div>
<!--中间部分over-->

<!--中间部分移动端start-->
<div class="container-fluid m-amc-center visible-xs-block">
    <!--滑动二级导航start-->
    
    <div class="m-amc-gallery" id="gallery">
        <div class="m-amc-holder holder">
            <div class="list">
            <?php
                $cat = \cms\services\CategoryService::service()->get(F::input()->get('cat', 'trim'));
                if($cat){
                    echo '<div class="item"><a class="active" href="javascript:">' . \fay\helpers\HtmlHelper::encode($cat['title']) . '</a></div>';
                }
            ?>
            </div>
        </div>
    </div>
    
    <!--滑动二级导航over-->
    
    <!--新闻列表start-->
    <?php
        //不想折腾了，直接从pc端的widget获取数据然后格式化输出
        $posts = F::widget()->getData('category-post-list');
    ?>
    <div id="mobile-newslist-container">
        <ul class="m-amc-newslist">
            <?php foreach($posts['data'] as $post){?>
            <li>
                <a href="<?php echo $post['post']['link']?>" class="clearfix">
                    <img src="<?php echo $post['post']['thumbnail']['thumbnail']?>" alt="" class="newspic">
                    <div class="m-newslist-title"><?php
                        echo \fay\helpers\HtmlHelper::encode($post['post']['title'])
                    ?></div>
                    <div class="newslist-text-tip clearfix">
                        <?php if(!empty($post['extra']['source'])){?>
                            <div class="newslist-text-from">来源：<?php echo \fay\helpers\HtmlHelper::encode($post['extra']['source'])?></div>
                        <?php }?>
                        <?php if($post['post']['format_publish_time']){?>
                            <div class="newslist-text-time"><?php echo $post['post']['format_publish_time']?></div>
                        <?php }?>
                    </div>
                </a>
            </li>
            <?php }?>
        </ul>
    </div>
    <ul class="m-amc-newslist">
        <li class="m-loadmore"><a href="javascript:">加载更多...</a></li>
    </ul>
    
    <!--新闻列表over-->
</div>
<!--中间部分移动端over-->
<script src="<?php echo $this->appAssets('js/jquery.touchSlider.min.js')?>"></script>