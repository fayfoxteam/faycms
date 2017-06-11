<?php
use cms\models\tables\MessagesTable;
use cms\services\user\UserService;

$settings = F::form('setting')->getAllData();
?>
<div class="row">
    <div class="col-12">
        <ul class="chats-list">
            <?php $listview->showData(array(
                'settings'=>$settings,
            ));?>
        </ul>
        <?php $listview->showPager();?>
    </div>
</div>
<div class="hide">
    <div id="chat-dialog" class="dialog w650 p0">
        <div class="">
            <div class="cf cd-header">
                <img src="<?php echo $this->assets('images/avatar.png" class="circle cd-avatar')?>" />
                <div class="cd-meta">
                    <span class="cd-user"></span>
                    <i class="fa fa-share"></i>
                    <span class="cd-to"></span>
                    <div>
                        <span class="cd-time"></span>
                        <a href="javascript:" class="reply-link reply-root" data-id="0" data-username="">回复楼主</a>
                    </div>
                </div>
                <div class="cd-content"></div>
            </div>
            <div class="cd-reply-list">
                <ul class="cd-timeline"></ul>
            </div>
            <div class="reply-container <?php if(!F::app()->checkPermission('cms/admin/chat/reply'))echo 'hide'?>">
                <form id="reply-form">
                    <input type="hidden" name="parent" />
                    <input type="hidden" name="to_user_id" />
                    <textarea name="content" class="p5"></textarea>
                    <a href="javascript:" id="reply-form-submit" class="btn fr mt5 mr10">回复</a>
                    <a href="javascript:" class="btn btn-grey fr fancybox-close mt5 mr10">取消</a>
                </form>
                <br class="clear" />
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->assets('faycms/js/admin/chat.js')?>"></script>
<script>
chat.status = {
    '<?php echo MessagesTable::STATUS_APPROVED?>':'<span class="fc-green">已通过</span>',
    '<?php echo MessagesTable::STATUS_UNAPPROVED?>':'<span class="fc-red">已驳回</span>',
    '<?php echo MessagesTable::STATUS_PENDING?>':'<span class="fc-orange">待审</span>',
    'approved':'<?php echo MessagesTable::STATUS_APPROVED?>',
    'unapproved':'<?php echo MessagesTable::STATUS_UNAPPROVED?>',
    'pending':'<?php echo MessagesTable::STATUS_PENDING?>'
};
chat.display_name = '<?php echo $settings['display_name']?>';
chat.permissions = <?php echo json_encode(array(
    'approve'=>UserService::service()->checkPermission('cms/admin/chat/approve'),
    'unapprove'=>UserService::service()->checkPermission('cms/admin/chat/unapprove'),
    'delete'=>UserService::service()->checkPermission('cms/admin/chat/delete'),
    'remove'=>UserService::service()->checkPermission('cms/admin/chat/remove'),
    'reply'=>UserService::service()->checkPermission('cms/admin/chat/reply'),
))?>;
chat.init();
</script>