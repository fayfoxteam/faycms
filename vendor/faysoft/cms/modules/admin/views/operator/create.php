<?php
/**
 * @var $roles array
 */
?>
<?php echo F::form()->open()?>
<div class="row">
    <div class="col-6">
        <?php $this->renderPartial('_edit_panel', array(
            'roles'=>$roles,
            'user'=>empty($user) ? array() : $user,
        ))?>
    </div>
    <div class="col-6" id="prop-panel"><?php
        //预留给角色属性的div
    ?></div>
</div>
<div class="form-field">
    <?php echo F::form()->submitLink('添加', array(
        'class'=>'btn',
    ))?>
</div>
<?php echo F::form()->close()?>

<script>
user.init();
</script>