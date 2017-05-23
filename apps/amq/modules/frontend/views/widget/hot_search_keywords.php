<h5 class="resouci"><?php echo \fay\helpers\HtmlHelper::encode($title)?></h5>
<?php foreach($data as $d){?>
<label for="m-searchfor" class="m-amc-hotword"><?php echo \fay\helpers\HtmlHelper::encode($d)?></label>
<?php }?>