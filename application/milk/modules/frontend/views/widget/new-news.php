<?php
 use fay\helpers\Date;

 ?>
 
 <h3><?php echo $data['title']?></h3>
                        <ul>
                           <?php foreach ($posts as $post){?>

                            <li>
                                <time datetime="<?php echo Date::format($post['publish_time'])?>"><?php echo Date::niceShort($post['publish_time'])?></time>
                                <a href="<?php echo $this->url('post/'.$post['id'])?>"><?php echo $post['title']?></a>
                            </li>
                        <?php }?>
                            
                        </ul>
                   <!-- .news -->