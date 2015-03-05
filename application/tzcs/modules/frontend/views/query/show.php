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
       <table class="table table-hover">
        <?php foreach ($student as $k => $value){?>
            <tr>
                <th><?php echo $k?></th><td><?php echo $value?></td>
            </tr>
         <?php }?>
       </table>
    	
    </div>



</div>
