<ul>
<?php foreach($tables as $table){?>
    <li><a href="<?php echo $this->url('model/download', array('table'=>$table))?>" target="_blank"><?php echo $table?></a></li>
<?php }?>
</ul>