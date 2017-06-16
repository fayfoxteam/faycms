<ul>
    <?php foreach($tables as $table){?>
        <li><a href="<?php echo $this->url('transfer/do', array(
            'table'=>$table['table'],
            'pri'=>$table['pri'],
        ))?>" target="_blank"><?php echo $table['table']?></a></li>
    <?php }?>
</ul>