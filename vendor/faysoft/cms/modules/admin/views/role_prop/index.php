<div class="row">
    <div class="col-5">
        <form id="form" action="<?php echo $this->url('cms/admin/role-prop/create')?>" method="post" class="validform">
            <?php $this->renderPartial('_edit_panel')?>
            <div class="form-field">
                <a href="javascript:;" class="btn" id="form-submit">添加</a>
            </div>
        </form>
    </div>
    <div class="col-7">
        <?php $this->renderPartial('_right', array(
            'listview'=>$listview,
        ))?>
    </div>
</div>