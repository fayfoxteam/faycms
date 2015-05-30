<?php
$css_url = $this->staticFile('css');
$js_url = $this->staticFile('js');
$img_url = $this->staticFile('images');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>首页</title>

    <link href="<?= $css_url ?>/base.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/index.css" rel="stylesheet" type="text/css" />
    <link href="<?= $css_url ?>/util.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= $this->url('js/jquery-1.8.3.min.js') ?>"></script>
    <script type="text/javascript" src="<?= $js_url ?>/focux.js"></script>
</head>

<body>
<div id="header">
    <div class="header">
        <div class="hesder-top">
            <a href="">加入收藏</a>│<span><img src="<?= $img_url ?>/index_03.png" alt=""/></span>服务热线：400-800-800
        </div>

        <div class="header-nav">
            <ul>
                <li><a href="" class="index-color">首页</a></li>
                <li><a href="">关于华奥</a></li>
                <li><a href="">核心业务</a></li>
                <li><a href="">客户服务</a></li>
                <li><a href="">新闻动态</a></li>
                <li><a href="">人才中心</a></li>
                <li><a href="" class="clearpadding">联系我们</a></li>
            </ul>
        </div>

        <div class="Logo">
            <div class="Logo-img"><img src="<?= $img_url ?>/LOGO_07.png" alt=""/></div>
            <div class="Logo-min">
                <div class="Logo-mintit">这里填写广告语</div>
                <div><a href="">http://www.gdhuaao.com/</a>
                </div>
            </div>

        </div>
    </div>
</div>
<?php echo $content ?>
<div class="index-bottom">
    <div class="index-bottommin">
        <div class="index-bottommyq">
            友情链接：
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
            <a href="">广州市华奥</a>
        </div>
        <div class="index-bottomcopy">
            <div class="index-bottomcopyL">
                <div class="index-bottomctxt"><span>广州市华奥供应链管理有限公司</span> <br>
                    GuangZhou Huaao Supply Chain Management Co.LTD</div>
            </div>

            <div class="index-bottomcopyL2">总部地址：广州市天河区黄埔大道西201号金泽大厦2813-2816室 <br>
                Copyright© 2015 粤ICP备09103107号</div>
            <div class="index-bottomcopyL3">
                <a href=""><img src="<?= $img_url ?>/index_26.png" alt=""/></a>
                <a href=""><img src="<?= $img_url ?>/index_29.png" alt=""/></a>
            </div>
        </div>
    </div>

</div>


</body>
</html>
