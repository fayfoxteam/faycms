<?php 
use fay\helpers\Html;

// dump($tables);
?>

<div class="row">
       
  <?php echo F::form()->open();?>
        <div class="col-6">
        <?php 
            foreach ($tables as $key => $table)
            {
        ?>
            <div class="form-field">
    			<label class="title"><?= $table['biao_name'] ?><em class="required">*</em>&nbsp;Id: <?= $table['biao_id'] ?></label>
    			<?php echo F::form()->inputText('biao-'.$table['id'], array(
    				'class'=>'form-control mw400',
    			))?>
		   </div>
		 <?php 
            }
		 ?>
        </div>
        
        <div class="form-field">
        	<?php echo F::form()->submitLink('添加记录', array(
        		'class'=>'btn',
        	))?>
        </div>

    <?php echo F::form()->close()?>
</div>