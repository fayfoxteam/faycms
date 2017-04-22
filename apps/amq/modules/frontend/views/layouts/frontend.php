<?php
use cms\services\file\FileService;
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

/**
 * @var $this \fay\core\View
 * @var $content string
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if(!empty($title)){
            echo $title, '_';
        }
        echo OptionService::get('site:sitename')?></title>
    <meta content="<?php if(isset($keywords))echo HtmlHelper::encode($keywords);?>" name="keywords" />
    <meta content="<?php if(isset($description))echo HtmlHelper::encode($description);?>" name="description" />
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->appAssets('css/amcommunity.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->appAssets('css/22bottom.css')?>">
    <?php echo $this->getCss()?>
</head>
<body>
<!--头部start-->
<div class="amc-header">
    <div class="amc-headermain clearfix">
        <?php
            $custom_logo = OptionService::get('site:logo');
            if($custom_logo){
                echo HtmlHelper::img($custom_logo, FileService::PIC_ORIGINAL, array(
                    'class'=>'amc-logo',
                ));
            }else{
                echo HtmlHelper::img($this->appAssets('images/logo.png'), 0, array(
                    'class'=>'amc-logo',
                ));
            }
        ?>
        <ul class="amc-nav hidden-xs">
            <li><a href="index.html" class="act">首页</a></li>
            <li><a href="news.html">域名资讯</a></li>
            <li><a href="">云计算</a></li>
            <li><a href="">知识产权</a></li>
            <li><a href="">互联网创业</a></li>
            <li><a href="">论坛热帖</a></li>
            <li><a href="">聚精彩</a></li>
        </ul>

        <!--移动端导航start-->
        <div class="m-amc-nav visible-xs-block">
            <img src="<?php echo $this->appAssets('images/search2.png')?>" alt="" class="m-search">
            <img src="<?php echo $this->appAssets('images/mnav.png')?>" alt="" class="m-nav">
            <div class="m-search-div">
                <div class="m-search-divmain">
                    <input type="text" placeholder="搜索" class="m-searchfor" id="m-searchfor">
                    <img src="<?php echo $this->appAssets('images/close.png')?>" alt="" class="amc-search-close">
                    <h5 class="resouci">热搜词</h5>
                    <label for="m-searchfor" class="m-amc-hotword">热搜词</label>
                    <label for="m-searchfor" class="m-amc-hotword">热搜词</label>
                    <label for="m-searchfor" class="m-amc-hotword">热搜词</label>
                    <label for="m-searchfor" class="m-amc-hotword">热搜词热搜词</label>
                    <label for="m-searchfor" class="m-amc-hotword">热搜词热搜词</label>

                </div>
            </div>
            <div class="m-nav-div">
                <ul>
                    <li><a href="index.html" class="act">首页</a></li>
                    <li><a href="news.html">域名资讯</a></li>
                    <li><a href="">云计算</a></li>
                    <li><a href="">知识产权</a></li>
                    <li><a href="">互联网创业</a></li>
                    <li><a href="">聚精彩</a></li>
                </ul>
            </div>
        </div>
        <!--移动端导航over-->
    </div>
</div>
<!--头部over-->

<?php echo $content?>

<!--移动端底部start-->
<div class="amc-footer visible-xs-block">
    <p>Copyright © 2016-2017</p>
    <p>浙江贰贰网络有限公司 (22net, Inc.) 版权所有</p>
</div>
<!--移动端底部over-->


<!--PC端底部start-->
<div class="footer">
    <div class="main-container">

        <div class="amc-footer-link clearfix">
            <div class="amc-footer-links">
                <p>贰贰网络事业群：</p>
                <a href="https://www.22.cn/ym/" target="_blank">域名注册</a>
                <a href="https://am.22.cn/" target="_blank">域名交易</a>
                <a href="https://www.22.cn/yuding/" target="_blank">预订竞价</a>
                <a href="http://www.mingyou.com/" target="_blank">名优金融</a>
                <a href="https://t.22.cn/" target="_blank">商标注册</a>
                <a href="http://club.22.cn/forum.php" target="_blank">爱名论坛</a>
                <a href="https://yun.22.cn/" target="_blank">云主机</a>
                <a href="https://p.22.cn/" target="_blank">微闪拍</a>
                <a href="http://www.qiangmi.com/" target="_blank">抢米网</a>
            </div>
            <div class="amc-footer-ewm">
                <div class="footer-ewms">
                    <img src="<?php echo $this->appAssets('images/wx_amq.jpg')?>" alt="">
                    关注爱名圈微信
                </div>

                <div class="footer-ewms">
                    <img src="<?php echo $this->appAssets('images/weixin_sidebar.jpg')?>" alt="">
                    扫描下载爱名app
                </div>
            </div>
        </div>

        <div class="footer-text-sm clearfix">
            <p class="tip">Copyright&nbsp;&nbsp;©&nbsp;&nbsp;2008-<?php echo date('Y')?>&nbsp;<?php echo OptionService::get('site:copyright')?></p>

            <p class=" hidden-xs">地址：浙江省杭州市西湖区紫霞街176号杭州互联网创新创业园2号楼11楼(310030)&nbsp;客服电话：400-660-2522&nbsp;座机1：0571-87756886&nbsp;座机2：0571-88276008&nbsp;传真：0571-88276022</p>

            <p class="text  hidden-xs">
                《中华人民共和国增值电信业务经营许可证》&nbsp;&nbsp;ISP证编号：浙B2-20100455&nbsp;&nbsp;浙ICP证B2-20090126&nbsp;&nbsp;
                <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010002000118">
                    <img src="<?php echo $this->appAssets('images/ghs.png')?>">浙公网安备 33010002000118号</a>
            </p>

            <p class="text-c9  hidden-xs">国家域名注册服务投诉中心投诉受理电话:010-58813000&nbsp;&nbsp;邮件supervise@cnnic.cn&nbsp;&nbsp;传真：010-58812666</p>

            <p class="footer-pic  hidden-xs">
                <span onclick="window.open ('http://www.cnnic.cn/jczyfw/CNym/cnzcfwjgcx/cnzcfwjg/201206/t20120612_26382.htm')" title="CNNIC认证" class="footer-pic-a"></span>
                <span onclick="window.open ('http://www.verisign.com/domain-name-services/find-registrar/index.html')" title=".COM/.NET注册局认证" class="footer-pic-b"></span>
                <span onclick="window.open ('http://www.innocom.gov.cn/gaoqi/rdba/201604/51c87197c171468da36e97be7aae7096.shtml')" title="高新技术企业认定" class="footer-pic-c"></span>
                <span onclick="window.open ('http://www.internic.net/registrars/registrar-1555.html')" title="ICANN认证" class="footer-pic-d"></span>
                <a title="《中华人民共和国增值电信业务经营许可证》ISP证编号：浙B2-20100455" target="_blank" href="http://www.22.cn/images/isp.jpg" rel="nofollow"></a>
                <span onclick="window.open ('http://www.cnnic.cn/ggfw/hyzl/yxymzcfwjghdljgtj/')" title="域名注册服务机构服务水平星级证书" class="footer-pic-f"></span>
                <a title="信息产业部域名注册服务批文号：工信部电函[2010]66号" class="footer-pic-g"></a>
            </p>

        </div>
        <?php F::widget()->load('footer-friendlinks')?>
        <div class="computer-back visible-xs"><a class="computer">返回电脑版</a></div>
    </div>
</div>
<!--PC端底部over-->



<script src="<?php echo $this->appAssets('js/jquery.min.js')?>"></script>
<script src="<?php echo $this->appAssets('js/bootstrap.min.js')?>"></script>
<script src="<?php echo $this->appAssets('js/hammer.min.js')?>"></script>
<script src="<?php echo $this->appAssets('js/amc.js')?>"></script>
</body>
</html>