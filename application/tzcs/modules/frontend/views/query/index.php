<?php 

?>
<div class="main clearfix ofHidden block yh">

	<!--左侧-->
	<div class="sidebar fleft">
    	 <?php F::app()->widget->load('friendly_links')?>
    	 
         <div class="title mt10"><?php echo $contact['title']?></div>
        <div class="contact_nr">
        	<?php echo $contact['content']?>
        </div>
        
    </div>

	<!--右侧-->
    <div class="main_right fright">
    <div class="title clearfix"><font class="yh f16"><?php echo $title?></font><span class="fright f12"><a href="<?php echo $this->url()?>">网站首页 </a>> <a href="#"><?php echo $title?></a></span></div>
      <div class="login">
            <form action="<?php echo $this->url('query/show')?>" method="post">
            <label for="realName">姓名：</label>
                <input type="text" name="realName" id="realName"/>
            <label for="idNum">学号:</label>
                <input type="text" name="idNum" id="idNum"/>
            <input type="submit" class="btn" value="查询" />
            </form>
    	</div>
    	
    </div>



</div>
