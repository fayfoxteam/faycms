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
            'user'=>$user,
        ))?>
    </div>
    <div class="col-6" id="prop-panel"><?php
        $this->renderPartial('prop/_edit', array(
            'prop_set'=>$prop_set,
        ))
    ?></div>
</div>
<div class="form-field">
    <?php echo F::form()->submitLink('保存', array(
        'class'=>'btn',
    ))?>
</div>
<?php echo F::form()->close()?>
<script>
user.user_id = <?php echo F::form()->getData('id')?>;
user.init();
</script>