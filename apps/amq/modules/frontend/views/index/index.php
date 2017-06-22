<?php
/**
 * @var $this \fay\core\View
 */
?>
<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('index-banner')?>
        <?php F::widget()->load('post-list')?>
    </div>
    <!--左边内容over-->
    
    <!--右边内容start-->
    <div class="amc-aside hidden-xs" id="startBottom">
        <!--搜索框start-->
        <?php $this->renderPartial('common/search_form')?>
        <!--搜索框over-->
        
        <div class="amc-fix fix-bottom">
            <?php F::widget()->area('index-sidebar-fixed')?>
        </div>
        <?php F::widget()->area('index-sidebar')?>
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
    <?php F::widget()->load('index-mobile-post-list')?>
    <?php F::widget()->load('club-hots')?>
</div>
<!--中间部分移动端over-->



<script src="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.css')?>">
<script>
$(function(){
    $('.edit-widget-link').fancybox({
        'iframe': {
            'css': {
                'max-width': '1000px'
            },
            'scrolling': 'yes'
        }
    });
});
</script>