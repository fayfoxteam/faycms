<?php 
use fay\helpers\String;
?>

<div class="news fleft cs">
        	<div class="t1"><a href="<?php echo $this->url('cat/'.$data['top'])?>"><img src="<?php echo $this->staticFile('images/more.jpg')?>"></a><span><?php echo $data['title']?></span></div>
            <ul>
            <?php foreach ($posts as $p){?>
            	<li><a href="<?php echo $this->url('post/'.$p['id'])?>" title="<?php echo $p['title']?>"><?php echo String::niceShort($p['title'], 26)?></a></li>
                
               <?php }?>
            </ul>
        </div>