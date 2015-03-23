<?php 

?>
    
    <div class="breadcrumbs">
        <div class="container_12">
            <div class="grid_12">
                 <a href="<?php echo $this->url()?>">主页</a><span></span><span class="current"><?php echo $cat['title']?></span></a>
            </div><!-- .grid_12 -->
        </div><!-- .container_12 -->
    </div><!-- .breadcrumbs -->
    
    <section id="main">
        <div class="container_12">
            <div id="content" class="grid_9">
                <h1 class="page_title"><?php echo $cat['title']?></h1>
                 
             
                <div class="products catalog">
                
                    <?php $listview->showData();?>
                    
                    <div class="clear"></div>
                </div><!-- .products -->
                <div class="clear"></div>
	      
                <div class="pagination">
                
                <?php $listview->showPager();?>
		 
                </div><!-- .pagination -->
                
                <div class="clear"></div>
            </div><!-- #content -->
            
            <div id="sidebar" class="grid_3">
                <?php F::widget()->load('product-list')?>

      
                
            </div><!-- .sidebar -->
            <div class="clear"></div>
        </div><!-- .container_12 -->
    </section><!-- #main -->
    <div class="clear"></div>
        
   
