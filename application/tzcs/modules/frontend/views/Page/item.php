<?php 
use fay\helpers\Html;
?>

<div class="main clearfix ofHidden block yh">

	<!--左侧-->
	<div class="sidebar fleft">
    	<div class="title">服务项目 Service</div>
        <ul class="menu">
        	<li>拆旧、敲墙、酒店、商场</li>
            <li>宾馆拆旧工程</li>
            <li>建筑工地废旧厂房拆酒店</li>
            <li>娱乐场所</li>
            <li>建筑工地及家庭</li>
        </ul>
        <div class="title mt10"><?php echo $contact['title']?></div>
        <div class="contact_nr">
        	<?php echo $contact['content']?>
        </div>
        
    </div>

	<!--右侧-->
    <div class="main_right fright">

    	<div class="title clearfix"><font class="yh f16"><?php echo $content['title']?></font><span class="fright f12"><a href="<?php echo $this->url()?>">网站首页</a> > <a href=""><?php echo $content['title']?></a></span></div>
    	
        <div class="newsnr">
      <h1 class="bt"><?php echo Html::encode($content['title'])?></h1>
      
      <div class="nr">
        <?php echo $content['content']?>
      </div>
      
      <div class="share clearfix">
		<div class="fleft"><a href="javascript:window.print()" class="print">打印本页</a></div>
		<div class="fleft"><a href="javascript:window.close()" class="close">关闭窗口</a></div>
    </div>
    
    <div class="down clearfix">
    	<div class="fright">
        <div class="bdsharebuttonbox"><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script></script>
        </div>
        
     
    </div>
    
  </div>
  
  
        
    </div>



</div>
