<?php 
use fay\models\Category;
use fay\helpers\Html;
?>
<div class="head clearfix yh">

	<!--logo-->
    <div class="logo block clearfix">
    	<a href="/" class="fleft"><img src="<?php echo $this->staticFile('images/logo.gif');?>" height="75px" width="380px"></a><span class="logo_h">大学生体质测试中心</span>
        <div class="fright">
        	<p class="tright"><a target="__blank" href="http://www.ypcol.com/">元培首页</a> | <a href="<?php echo $this->url('page/contact')?>">联系我们</a> | <a onclick="SetHome(window.location)" href="javascript:void(0)">设为首页</a> | <a onclick="AddFavorite(window.location,document.title)" href="javascript:void(0)">加入收藏</a></p>
            <br>
            <p class="f16 c_red"></p>
        </div>
    </div>
    
    <!--nav-->
	<div class="nav clearfix">
    	<ul class="block">
        	<li><a href="<?php echo $this->url()?>">网站首页</a></li>
        	<?php
							//文章分类列表
							$cats = Category::model()->getTree('_system_post');
							foreach($cats as $cat){
								if(!$cat['is_nav'])continue;
								echo '<li class="livea">', Html::link($cat['title'], array('cat/'.$cat['id']), array(
									'class'=>'L',
									'title'=>false,
								));
								if(!empty($cat['children'])){
									echo '<div class="livs" style="display:none;"><ul>';
									foreach($cat['children'] as $c){
										if(!$c['is_nav'])continue;
										echo '<li>', Html::link($c['title'], array('cat/'.$c['id']), array(
											'title'=>false,
										)), '</li>';
									}
									echo '</ul></div>';
								}
							}
							echo '</li>';
						?>
            <li><a href="<?php echo $this->url('query')?>" class="L">成绩查询</a></li>
        </ul>
    </div>
    
    <div class="focusBox">
			<ul class="pic">
					<li><a href="#" target="_blank"><img src="<?php echo $this->staticFile('images/banner.jpg');?>"/></a></li>
					<li><a href="#" target="_blank"><img src="<?php echo $this->staticFile('images/banner.jpg');?>"/></a></li>
					<li><a href="#" target="_blank"><img src="<?php echo $this->staticFile('images/banner.jpg');?>"/></a></li>
					<li><a href="#" target="_blank"><img src="<?php echo $this->staticFile('images/banner.jpg');?>"/></a></li>
			</ul>
			<ul class="hd">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
	</div>


</div>