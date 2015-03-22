 <?php 
 
//  dump($cats);
 ?>
 
 
 <h3><?php echo $data['title']?></h3>
                        <ul>
                           
                           <?php foreach ($cats as $key => $cat){?>
                                <?php if ($cat['id'] != 10005){?>
                            <li>
                           
                                <a href="<?php echo $this->url('cat/'.$cat['id'])?>"><?php echo $cat['title'];?></a>
                            </li>
                            <?php }?>
                   <?php }?>
                            
                        </ul>
                    <!-- .news -->