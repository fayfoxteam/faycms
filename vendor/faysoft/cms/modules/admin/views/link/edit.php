<form id="form" action="" method="post" class="validform">
    <?php $this->renderPartial('_edit_panel', array(
        'cats'=>$cats,
    ))?>
    <div class="form-field">
        <a href="javascript:;" class="btn" id="form-submit">编辑链接</a>
    </div>
</form>
<?php $this->renderPartial('_js')?>