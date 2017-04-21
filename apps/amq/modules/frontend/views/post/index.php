<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('category-post-list')?>
    </div>
    <!--左边内容over-->
    
    <!--右边内容start-->
    <div class="amc-aside hidden-xs">
        <!--搜索框start-->
        <div class="PR"><input type="text" class="amc-search" placeholder="请输入关键字"><a href=""><img src="<?php echo $this->appAssets('images/search.png')?>" alt="" class="amc-searchfor"></a></div>
        <!--搜索框over-->
    
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
    <!--滑动二级导航start-->
    
    <div class="m-amc-gallery" id="gallery">
        <div class="m-amc-holder holder">
            <div class="list">
                <div class="item"><a class="active" href="">域名资讯</a></div>
                <div class="item"><a href="">域名数据</a></div>
                <div class="item"><a href="">交易投资</a></div>
                <div class="item"><a href="">经验交流</a></div>
                <div class="item"><a href="">域名知识</a></div>
                <div class="item"><a href="">域名爆料</a></div>
            </div>
        </div>
    </div>
    
    <!--滑动二级导航over-->
    
    <!--新闻列表start-->
    <ul class="m-amc-newslist">
        <li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li>
        <li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li>
        <li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li>
        <li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li>
        <li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li><li>
            <a href="" class="clearfix">
                <img src="images/news1.png" alt="" class="newspic">
                <div class="m-newslist-title">这里是文章标题这里是文章标题文章标题文章标题文章</div>
                <div class="newslist-text-tip clearfix"><div class="newslist-text-from">来源：爱名网</div><div class="newslist-text-time">5分钟前</div></div>
            </a>
        </li>
    </ul>
    <!--新闻列表over-->
</div>
<!--中间部分移动端over-->