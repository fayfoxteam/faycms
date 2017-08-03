<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-6">
        <?php echo F::form()->open(array('cms/admin/tag/edit', F::input()->get()))?>
            <?php $this->renderPartial('_edit_panel');?>
            <div class="form-field">
                <a href="javascript:" class="btn" id="form-submit">编辑标签</a>
            </div>
        <?php echo F::form()->close()?>
    </div>
    <div class="col-6">
        <?php $this->renderPartial('_right', array(
            'listview'=>$listview
        ));?>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
    $(".tag-sort").feditsort({
        'url':system.url("cms/admin/tag/sort")
    });
});
</script>