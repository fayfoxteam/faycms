<?php
use fay\models\File;
use fay\helpers\Html;
// dump($data);
?>

                <div class="related grid_12">
                    
                        <div class="c_header">
                            <div class="grid_10">
                                <h2><?php echo Html::encode($data['title']);?></h2>
                            </div><!-- .grid_10 -->

                            <div class="grid_2">
                                <a id="next_c1" class="next arows" href="#"><span>Next</span></a>
                                <a id="prev_c1" class="prev arows" href="#"><span>Prev</span></a>
                            </div><!-- .grid_2 -->
                        </div><!-- .c_header -->

                        <div class="related_list">
                            <ul id="listing" class="products">
                            <?php foreach ($posts as $key => $post){?>
                                <li>
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
                                </li>
                                <?php }?>
                         
                  
                            </ul><!-- #listing -->
                         </div><!-- .brands_list -->
                </div><!-- .related -->