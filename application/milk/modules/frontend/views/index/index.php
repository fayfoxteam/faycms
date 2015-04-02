<?php 
use fay\helpers\String;
?>

<script src="<?php echo $this->staticFile('js/jquery.SuperSlide.2.1.1.js')?>"></script>

<?php F::widget()->load('index-banner-img')?>
<script type="text/javascript">
$(document).ready(function(){

	$(".prev,.next").hover(function(){
		$(this).stop(true,false).fadeTo("show",0.9);
	},function(){
		$(this).stop(true,false).fadeTo("show",0.4);
	});
	
	$(".banner-box").slide({
		titCell:".hd ul",
		mainCell:".bd ul",
		effect:"fold",
		interTime:3500,
		delayTime:500,
		autoPlay:true,
		autoPage:true, 
		trigger:"click" 
	});

});
</script>
    <section id="main" class="home">
        <div class="container_12">
        
          <div id="content_bottom">
                
                <div class="grid_4">
                    <div class="bottom_block" id="lists">
                        <?php F::widget()->load('index-lists')?>
                    </div><!-- .about_as -->
                </div><!-- .grid_4 -->
                
                <div class="grid_4">
                    <div class="bottom_block about_as lh-25">
                        <h3><?php echo $about['title']?></h3>
                        <?php echo $about['abstract']?>
                        <a href="<?php echo $this->url('page/'.$about['id'])?>">&nbsp;更多>></a>
                    </div><!-- .about_as -->
                </div><!-- .grid_4 -->

                <div class="grid_4">
                    <div class="bottom_block news">
                       <?php F::widget()->load('new-news')?>
                </div><!-- .grid_4 -->
                <div class="clear"></div>
            </div><!-- #content_bottom -->
            
            <div class="clear-20"></div>
       
                    <?php F::widget()->load('hot-products')?>

         

            <div class="clear"></div>

          
        </div><!-- .container_12 -->
    </section><!-- #main.home -->