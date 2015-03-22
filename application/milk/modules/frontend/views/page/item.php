
    <div class="breadcrumbs">
        <div class="container_12">
            <div class="grid_12">
                 <a href="<?php echo $this->url()?>">主页</a><span></span><span class="current"><?php echo $page['title']?></span>
            </div><!-- .grid_12 -->
        </div><!-- .container_12 -->
    </div><!-- .breadcrumbs -->
    
    <section id="main">
        <div class="container_12">
            <div id="content" class="grid_12">
                <header>
                    <h1 class="page_title"><?php echo $page['title']?></h1>
                </header>
                    
                <article class="product_page">
                    
                    <div class="grid_12" >
		

			    <div class="clear"></div>

			    <div class="tab1 tab_body">
                                <?php echo $page['content']?>
                                <div class="clear"></div>
			    </div><!-- .tab1 .tab_body -->

			    <div class="tab2 tab_body">
			     
               
                                <div class="clear"></div>
			    </div><!-- .tab2 .tab_body -->

			    <div class="tab3 tab_body">
				
				<div class="clear"></div>
			    </div><!-- .tab3 .tab_body -->
			    <div class="clear"></div>
			</div>​<!-- #wrapper_tab -->
			<div class="clear"></div>
		    </div><!-- .grid_12 -->
                    
		</article><!-- .product_page -->
                
        <?php F::widget()->load('hot-products-inside')?>
                <div class="clear"></div>
            </div><!-- #content -->

            <div class="clear"></div>
        </div><!-- .container_12 -->
    </section><!-- #main -->
    <div class="clear"></div>
 