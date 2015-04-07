<?php 
use fay\helpers\Html;
use fay\models\File;

?>
  
  <div class="banner-box">
	<div class="bd">
        <ul style="width: 100%!important;">        
        <?php foreach ($files as $f){?>  	    
            <li style="width: 100%!important;">
                <div class="m-width">
            <?php echo Html::img($f['file_id'], File::PIC_ORIGINAL, array('width'=>'100%'))?>
                </div>
            </li>
            <?php }?>
       
         
        </ul>
    </div>
    <div class="banner-btn">
        <a class="prev" href="javascript:void(0);"></a>
        <a class="next" href="javascript:void(0);"></a>
        <div class="hd"><ul></ul></div>
    </div>
</div><!-- #slider_body -->
  