<?php 
use fay\helpers\Html;
use fay\models\File;
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

    	<div class="title clearfix"><font class="yh f16"><?php echo $content['cat_title']?></font><span class="fright f12"><a href="<?php echo $this->url()?>">网站首页</a> > <a href="<?php echo $this->url('cat/'.$content["cat_id"])?>"><?php echo $content['cat_title']?></a></span></div>
    	
        <div class="newsnr">
      <h1 class="bt"><?php echo Html::encode($content['title'])?></h1>
      <div class="date"><span><?php echo date('Y年m月d日 H:i:s',$content['publish_time'])?></span><span>作者：<?php echo $content['username']?></span><span>浏览数: <?php echo $content['views']?></span></div>
      <div class="nr">
        <?php echo $content['content']?>
        
        <div class="download">
		     <?php
		   
							if(!empty($content['files'])){
								echo "附件：";
								foreach($content['files'] as $k => $f){
								    $k++;
								    echo "<div class='files'>".$k.". ";
									echo Html::link($f['description'], array('file/download', array(
										'id'=>$f['file_id'],
									    'name'=>'date',
									)));
									echo "<span> (下载次数:".File::model()->getDownloads($f['file_id']).")</span></div>";
								}
							}
						?>
			
				
		  </div>
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
        
        
        <div class="fleft">
    	<p>上一篇：<?php if($content['nav']['prev']){
								echo Html::link($content['nav']['prev']['title'], array(
									'post/'.$content['nav']['prev']['id'],
								));
							}else{
								echo '没有了';
							}?></p>
							<p>下一篇：<?php if($content['nav']['next']){
								echo Html::link($content['nav']['next']['title'], array(
									'post/'.$content['nav']['next']['id'],
								));
							}else{
								echo '没有了';
							}?></p>
        </div>
    </div>
    
  </div>
  
  
        
    </div>



</div>
