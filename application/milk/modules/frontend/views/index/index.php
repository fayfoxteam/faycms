<?php 
use fay\helpers\String;
?>



  <div id="slider_body">
        <ul id="slider">
            <li>
                <div class="slid_content">
                    <h2 style="color:#6f566f;">Engagement Rings</h2>
                    <p style="color:#6f566f;">The hardest part is over – you’ve found the love of<br>
                    your life. Now is the time to find the perfect diamond<br>
                    engagement ring and plan a beautiful proposal.</p>
                    <a class="buy_now" href="#">Buy now</a>
                </div><!-- .slid_content -->
                <img src="<?php echo $this->staticFile('img/content/slid-1.png') ?>" alt="Slid 1" title="">
            </li>

            <li>
                <div class="slid_content">
                    <h2 style="color:#744747;">Precious Metals</h2>
                    <p style="color:#744747;">There’s no gift quite like diamond jewelry. Whether<br>
                    you’re looking for a diamond ring, bracelet,<br>
                    earrings or necklace, we’ll give you tips.</p>
                    <a class="buy_now" href="#">Buy now</a>
                </div><!-- .slid_content -->
                <img src="<?php echo $this->staticFile('img/content/slid-2.png') ?>" alt="Slid 2" title="">
            </li>

            <li>
                <div class="slid_content">
                    <h2 style="color:#6d5956;">Handmade jewelry</h2>
                    <p style="color:#6d5956;">Congratulations on your engagement. As you<br>
                    begin to plan the many details of your wedding<br>
                    day, don’t forget the rings!</p>
                    <a class="buy_now" href="#">Buy now</a>
                </div><!-- .slid_content -->
                <img src="<?php echo $this->staticFile('img/content/slid-3.png') ?>" alt="Slid 3" title="">
            </li>
        </ul>
    </div><!-- #slider_body -->

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