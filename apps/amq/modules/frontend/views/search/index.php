<?php
/**
 * @var $listview \fay\common\ListView
 * @var $keywords string
 */
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<!--中间部分start-->
<div class="container-fluid amc-center clearfix">

    <!--左边内容start-->
    <div class="amc-main">
        <!--新闻start-->
        <div class="amc-newslist hidden-xs">
            <ul class="newslist-contain amc-search-result">
                <?php $listview->showData(array(
                    'keywords'=>$keywords,
                ));?>
            </ul>
            <div class="loadmore">加载更多...</div>
            <?php $listview->showPager()?>
        </div>
        <!--新闻start-->

    </div>
    <!--左边内容over-->

    <!--右边内容start-->
    <div class="amc-aside hidden-xs">
        <!--搜索框start-->
        <?php $this->renderPartial('common/search_form')?>
        <!--搜索框over-->
        
        <?php
            $hot_keywords = F::widget()->getData('hot-search-keywords');
            if($hot_keywords){?>
                <div class="amc-hot">
                    <h5 class="newslist-title"><span class="orange-underline">搜索热榜</span></h5>
                    <div id="hot-words">
                        <ul class="amc-forum">
                        <?php foreach($hot_keywords['data'] as $i => $keyword){
                            if($i == 0){
                                $class = 'bg-red';
                            }else if($i == 1){
                                $class = 'bg-orange';
                            }else if($i == 2){
                                $class = 'bg-green';
                            }else{
                                $class = '';
                            }
                            ?>
                            <li><a href="<?php echo $this->url('search', array(
                                'keywords'=>$keyword,
                            ))?>"><div class="forum-news hotsearch"><span class="search-circle <?php echo $class?>"><?php echo $i+1?></span><?php echo HtmlHelper::encode($keyword)?></div>
                            </a></li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
            <?php }?>

        <?php F::widget()->area('search-sidebar')?>
    </div>
    <!--右边内容over-->

    <!--侧边栏start-->
    <ul class="amc-bside">
        <li class="amc-wx"><img src="http://news.22.cn/templets/amq/images/ewm.png" alt="" class="amc-ewm"></li>
        <li class="retop"></li>
    </ul>
    <!--侧边栏over-->
</div>
<!--中间部分over-->
<!--中间部分移动端start-->
<div class="container-fluid m-amc-center visible-xs-block">
    <!--新闻列表start-->
    <ul class="m-amc-newslist">
    <?php $posts = $listview->getData();
        foreach($posts as $data){
            $thumbnail = FileService::getUrl($data['thumbnail'], FileService::PIC_RESIZE, array(
                'dw'=>150,
                'dh'=>90,
                'spare'=>'default',
            ));?>
        <li>
            <a href="http://news.22.cn/hulianwang/2702.html" class="clearfix">
                <img src="<?php echo $thumbnail?>" class="newspic" width="150"/>
                <div class="m-newslist-title"><?php
                    echo str_replace($keywords, '<span class="fc-red">'.$keywords.'</span>', HtmlHelper::encode($data['title']))
                ?></div>
                <div class="newslist-text-tip clearfix">
                    <?php if(!empty($data['source'])){?>
                        <div class="newslist-text-from">来源：<?php echo HtmlHelper::encode($data['source'])?></div>
                    <?php }?>
                    <div class="newslist-text-time"><?php echo DateHelper::niceShort($data['publish_time'])?></div>
                </div>
            </a>
        </li>
    <?php }?>
    </ul>
    <!--新闻列表over-->
</div>
<!--中间部分移动端over-->
