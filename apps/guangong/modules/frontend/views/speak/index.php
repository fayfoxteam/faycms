<?php
/**
 * @var $this \fay\core\View
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<?php if(!empty($canonical)){?>
    <link rel="canonical" href="<?php echo $canonical?>" />
<?php }?>
<title><?php if(!empty($title)){
    echo $title;
}?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php echo $this->getCss()?>
<style>
    html,body,h1,ul,li{padding:0;margin:0}
    .wrapper{padding-top:20px}
    .top-title{font-size:16px;font-weight:bold;background-color:#E50012;color:#FFF000;padding:10px 20px;text-align:center;}
    .banner{width:100%;border-bottom:5px solid #F6AC3B}
    .banner img{width:100%;display:block}
    .sidebar{background-color:#fff;width:30%;float:left;margin-top:30px;}
    .sidebar ul{width:100%}
    .sidebar ul li{text-align:center;list-style:none;margin-bottom:30px}
    .sidebar ul li img{height:40px;}
    .content{width:70%;float:right;background-color:#FDD000;padding:3px;box-sizing:border-box}
    .content article{margin-bottom:3px}
    .content article img{width:100%;}
</style>
</head>
<body>
<div class="wrapper">
    <h1 class="top-title">关公点兵义举和军事活动官方网站</h1>
    <div class="banner"><img src="<?php echo $this->appAssets('images/speak/banner.jpg')?>"></div>
    <div class="sidebar">
        <ul>
            <li>
                <a href="">
                    <img src="<?php echo $this->appAssets('images/speak/l-guangongdianbing.png')?>">
                </a>
            </li>
            <li>
                <a href="">
                    <img src="<?php echo $this->appAssets('images/speak/l-weibo.png')?>">
                </a>
            </li>
            <li>
                <a href="">
                    <img src="<?php echo $this->appAssets('images/speak/l-xunchengji.png')?>">
                </a>
            </li>
            <li>
                <a href="">
                    <img src="<?php echo $this->appAssets('images/speak/l-yiju.png')?>">
                </a>
            </li>
        </ul>
    </div>
    <div class="content">
        <article>
            <a href="">
                <img src="<?php echo $this->appAssets('images/speak/p-1.jpg')?>">
            </a>
        </article>
        <article>
            <a href="">
                <img src="<?php echo $this->appAssets('images/speak/p-2.jpg')?>">
            </a>
        </article>
        <article>
            <a href="">
                <img src="<?php echo $this->appAssets('images/speak/p-3.jpg')?>">
            </a>
        </article>
    </div>
</div>
</body>
</html>