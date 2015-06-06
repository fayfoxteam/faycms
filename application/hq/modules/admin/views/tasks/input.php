<?php 
use fay\helpers\Html;
use hq\models\tables\Zbiaos;

// dump($tables);
?>

<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array('class' => 'form-inline')); ?>
        请选择需要查看的类型:
        <select name="type_id" class="form-control">
            <option value="" >--分类--</option>
            <option value="<?= Zbiaos::TYPE_ELECTRICITY ?>" <?= isset($_GET['type_id']) && $_GET['type_id'] == Zbiaos::TYPE_ELECTRICITY ? 'selected' : '' ?> >电表</option>
            <option value="<?= Zbiaos::TYPE_WATER ?>" <?= isset($_GET['type_id']) && $_GET['type_id'] == Zbiaos::TYPE_WATER ? 'selected' : '' ?>>水表</option>
        </select>
        <?php echo F::form('search')->submitLink('确认', array(
            'class'=>'btn btn-sm',
        ))?>
        <?php echo F::form()->close(); ?>
    </div>
</div>

<div class="row">

  <?php echo F::form()->open();?>
        <div class="col-6">
        <?php
            if (isset($tables))
            {
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
            <div class="form-field">
                <?php echo F::form()->submitLink('添加记录', array(
                    'class'=>'btn',
                ))?>
            </div>
            <?php } ?>
        </div>

    <?php echo F::form()->close()?>

</div>
