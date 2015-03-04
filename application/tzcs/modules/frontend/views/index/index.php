<?php 

use fay\helpers\Html;
use fay\helpers\String;
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
    
    	<div class="clearfix">
        
    	<!--简介-->
    	<?php F::app()->widget->load('index-box-new')?>
        
        <!--新闻中心-->
        <?php F::app()->widget->load('index-box-download')?>
        
        
        </div>
        
        <!--展示-->
        <?php F::app()->widget->load('index-box-gallery') ?>
        
    </div>



</div>
