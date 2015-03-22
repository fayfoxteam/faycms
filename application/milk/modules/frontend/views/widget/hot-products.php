<?php
use fay\helpers\Html;
use fay\models\File;
// dump($posts);
?>

     <div id="content">
                <div class="grid_12">
                    <h2 class="product-title"><?php echo $data['title']?></h2>
                </div><!-- .grid_12 -->

                <div class="clear"></div>

                <div class="products">
                <?php foreach ($posts as $key => $post){?>
            <article class="grid_3 article">
                        <img class="sale" src="<?php echo $this->staticFile('img/sale.png')?>" alt="Sale">
                        <div class="prev">
                            <a href="<?php echo $this->url('post/'.$post['id'])?>">
                            <?php echo Html::img($post['thumbnail'], File::PIC_ZOOM, array(
                                                                                    'dw' => 210, 
                                                                                    'dh' => 210, 
                                                                                    'alt' => Html::encode($post['title']), 
                                                                                    'title' => Html::encode($post['title']))
                                                                                    );?>
                            </a>
                        </div><!-- .prev -->

                        <h3 class="title"><?php echo $post['title']?></h3>
             
                    </article><!-- .grid_3.article -->
                    <?php }?>
                               <div class="clear"></div>
                </div><!-- .products -->
                <div class="clear"></div>
            </div><!-- #content -->

              