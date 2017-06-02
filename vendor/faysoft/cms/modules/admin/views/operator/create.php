<?php
/**
 * @var $roles array
 * @var $prop_set array
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
        //添加的时候也可能默认有角色
        $this->renderPartial('prop/_edit', array(
            'prop_set'=>$prop_set,
        ))
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