<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('post-item')?>
    </div>
    <!--左边内容over-->
    
    <!--右边内容start-->
    <div class="amc-aside hidden-xs" id="startBottom">
        <!--搜索框start-->
        <?php $this->renderPartial('common/search_form')?>
        <!--搜索框over-->

        <div class="amc-fix fix-bottom">
            <?php F::widget()->area('item-sidebar-fixed')?>
        </div>
        <?php F::widget()->area('item-sidebar')?>
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
    <?php F::widget()->area('mobile-item-sidebar')?>
</div>
<!--中间部分移动端over-->
