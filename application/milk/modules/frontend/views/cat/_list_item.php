<?php
// dump($data);
?>

   <!-- products show list start -->
                    <article class="grid_3 article">
                        <img class="sale" src="<?php echo $this->staticFile('img/sale.png')?>" alt="Sale">
                        <div class="prev">
                            <a href="<?php echo $this->url('post/'.$data['id'])?>"><img src="img/content/product1.png" alt="Product 1" title=""></a>
                        </div><!-- .prev -->
                        
                        <h3 class="title"><?php echo $data['title']?></h3>
                        <div class="cart">
                            <div class="price">
                                <div class="vert">
                                    $550.00
                                    <div class="price_old">$725.00</div>
                                </div>
                            </div>
                            <a href="#" class="obn"></a>
                            <a href="#" class="like"></a>
                            <a href="#" class="bay"><img src="<?php echo $this->staticFile('img/bg_cart.png')?>" alt="Buy" title=""></a>
                        </div><!-- .cart -->
                    </article><!-- .grid_3.article -->
                    
                    <!-- products show list end -->