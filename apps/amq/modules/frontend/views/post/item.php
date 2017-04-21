<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('post-item')?>
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
    <!--广告start-->
    <div class="m-amc-ad">
        <a href=""><img src="images/mad.jpg" alt=""></a>
    </div>
    <!--广告over-->
    
    <!--新闻列表start-->
    <div class="m-amc-hotnews">
        <h5 class="newslist-title"><span class="orange-underline">热门资讯</span></h5>
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
            
            <li class="m-loadmore"><a href="">加载更多...</a></li>
        </ul>
    </div>
    <!--新闻列表over-->
    
    <!--论坛热帖start-->
    <div class="amc-hot">
        <h5 class="newslist-title"><span class="orange-underline">论坛热帖</span><a href="" class="morelink">更多></a></h5>
        <ul class="amc-forum">
            <li><a href=""><div class="forum-news">新手投资课程：啃老族永远不懂中小米</div><div class="forum-time">[03-02]</div></a></li>
            <li><a href=""><div class="forum-news">新手投资课程：啃老族永远不懂中小米</div><div class="forum-time">[03-02]</div></a></li>
            <li><a href=""><div class="forum-news">新手投资课程：啃老族永远不懂中小米</div><div class="forum-time">[03-02]</div></a></li>
            <li><a href=""><div class="forum-news">新手投资课程：啃老族永远不懂中小米</div><div class="forum-time">[03-02]</div></a></li>
            <li><a href=""><div class="forum-news">新手投资课程：啃老族永远不懂中小米</div><div class="forum-time">[03-02]</div></a></li>
        </ul>
    </div>
    <!--论坛热帖over-->
</div>
<!--中间部分移动端over-->
