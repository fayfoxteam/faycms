<?php
use fay\helpers\Html;
use fay\models\File;
// dump($data);
?>

   <!-- products show list start -->
                    <article class="grid_3 article">
                        <img class="sale" src="<?php echo $this->staticFile('img/sale.png')?>" alt="Sale">
                        <div class="prev">
                            <a href="<?php echo $this->url('post/'.$data['id'])?>">
                            <?php echo Html::img($data['thumbnail'], File::PIC_ZOOM, array(
                                                                                    'dw' => 210, 
                                                                                    'dh' => 210, 
                                                                                    'alt' => Html::encode($data['title']),
                                                                                    'title' => Html::encode($data['title']) )
                                                                                    )?>
                            </a>
                        </div><!-- .prev -->
                        
                        <h3 class="title"><?php echo $data['title']?></h3>
                
                    </article><!-- .grid_3.article -->
                    
                    <!-- products show list end -->