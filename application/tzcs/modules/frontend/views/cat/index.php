<?php 

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

    	<div class="title clearfix"><font class="yh f16"><?php echo $cat['title']?></font><span class="fright f12"><a href="<?php echo $this->url()?>">网站首页 </a>> <a href="#"><?php echo $cat['title']?></a></span></div>
    	<ul class="text_list">
            <?php $listview->showData();?>
      </ul>
        <div class="page clearfix">
            <?php $listview->showPage();?>
        </div>
        
    </div>



</div>
