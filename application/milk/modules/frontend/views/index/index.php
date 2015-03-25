<?php 
use fay\helpers\String;
?>

<script src="<?php echo $this->staticFile('js/jquery.SuperSlide.2.1.1.js')?>"></script>

  <div class="banner-box">
	<div class="bd">
        <ul>          	    
            <li style="background:#F3E5D8;">
                <div class="m-width">
                <a href="javascript:void(0);"><img src="<?php echo $this->staticFile('img/content/img1.jp') ?>" /></a>
                </div>
            </li>
            <li style="background:#B01415">
                <div class="m-width">
                <a href="javascript:void(0);"><img src="<?php echo $this->staticFile('img/img2.jp') ?>" /></a>
                </div>
            </li>
            <li style="background:#C49803;">
                <div class="m-width">
                <a href="javascript:void(0);"><img src="<?php echo $this->staticFile('img/img3.jp') ?>" /></a>
                </div>
            </li>
            <li style="background:#FDFDF5">
                <div class="m-width">
                <a href="javascript:void(0);"><img src="<?php echo $this->staticFile('img/img4.jg') ?>" /></a>
                </div>
            </li>  
         
        </ul>
    </div>
    <div class="banner-btn">
        <a class="prev" href="javascript:void(0);"></a>
        <a class="next" href="javascript:void(0);"></a>
        <div class="hd"><ul></ul></div>
    </div>
</div><!-- #slider_body -->
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
                    <div class="bottom_block about_as">
                        <h3><?php echo $about['title']?></h3>
                        <?php echo String::niceShort($about['content'], 1450)?>
                        <a href="<?php echo $this->url('page/'.$about['id'])?>">more</a>
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

            <div id="brands">
                <div class="c_header">
                    <div class="grid_10">
                        <h2>Shop by brands</h2>
                    </div><!-- .grid_10 -->

                    <div class="grid_2">
                        <a id="next_c1" class="next arows" href="#"><span>Next</span></a>
                        <a id="prev_c1" class="prev arows" href="#"><span>Prev</span></a>
                    </div><!-- .grid_2 -->
                </div><!-- .c_header -->

                <div class="brands_list">
                    <ul id="listing">
                        <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand1.png')?>" alt="Brand 1" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand2.png')?>" alt="Brand 2" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand3.png')?>" alt="Brand 3" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand4.png')?>" alt="Brand 4" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand5.png')?>" alt="Brand 5" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand6.png')?>" alt="Brand 6" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                       <li>
                            <div class="grid_2">
                                <a href="#"><div><img src="<?php echo $this->staticFile('img/content/brand7.png')?>" alt="Brand 7" title=""></div></a>
                            </div><!-- .grid_2 -->
                        </li>
                    </ul><!-- #listing -->
                </div><!-- .brands_list -->
            </div><!-- #brands -->

          
        </div><!-- .container_12 -->
    </section><!-- #main.home -->