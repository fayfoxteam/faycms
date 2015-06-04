<?php 
use fay\helpers\Html;

// dump($tables);
?>

<div class="row">
       
  <?php echo F::form()->open(array('admin/tasks/input'), 'post', array(
      'id' => 'input-form',
  ));?>
        <div class="col-6">
        <?php 
            foreach ($tables as $key => $table)
            {
        ?>
            <div class="form-field">
    			<label class="title"><?= $table['biao_name'] ?><em class="required">*</em>&nbsp;Id: <?= $table['biao_id'] ?></label>
    			<?php echo F::form()->input('biao-'.$table['id'], 'text', array(
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

<script>
$(function(){
	$('#form-submit').click(function(){
	    $("#input-form input").each(function(){
            if ($(this).val() == '')
            {
                alert('请将数据填写完整再提交');
                return false;
            }
        });
    });
});
</script>