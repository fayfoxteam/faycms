<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<style>
.contact-item{margin-bottom:30px;background-color:#fff;padding:20px;}
.contact-item .ci-header{color:#979898;}
.contact-item h3{color:#2c2e2f;font-size:23px;font-weight:normal;margin-bottom:10px;}
.contact-item .ci-options{float:right;}
.contact-item .ci-header{margin-bottom:20px;}
.contact-item .ci-header span{margin-left:11px;}
.contact-item .ci-header span:first-child{margin-left:0;}
.contact-item .ci-header .fa{margin-right:5px;}
.contact-item .ci-content{color:#555;margin-bottom:20px;}
.contact-item .ci-reply{border:1px solid #e4e4e4;padding:15px 20px;color:#979898;}
.contact-item .ci-reply strong{color:#575858;}
#contact-reply-dialog .user-message{border:1px solid #e4e4e4;padding:15px 20px;margin-bottom:20px;color:#979898;}
#contact-reply-dialog .user-message strong{color:#575858;}
</style>
<div class="row">
    <div class="col-12">
        <ul class="contact-list">
            <?php $listview->showData(array(
                'settings'=>F::form('setting')->getAllData(),
                'iplocation'=>$iplocation,
            ));?>
        </ul>
        <?php $listview->showPager();?>
    </div>
</div>
<div class="hide">
    <div id="contact-reply-dialog" class="dialog w650">
        <div class="dialog-content">
            <h4>回复留言</h4>
            <div>
                <div class="user-message">
                    <strong>用户留言：</strong>
                    <span class="user-message-container"></span>
                </div>
                <form id="contact-reply-form">
                    <input type="hidden" name="id" />
                    <textarea name="reply" class="form-control p5 wp100 h90 autosize" placeholder="管理员回复"></textarea>
                    <a href="javascript:" class="btn btn-grey fr fancybox-close mt5">取消</a>
                    <a href="javascript:" id="contact-reply-form-submit" class="btn fr mt5 mr10">回复</a>
                </form>
                <br class="clear" />
            </div>
        </div>
    </div>
</div>
<script>
var contact = {
    'reset': function(){
        var $contactReplyDialog = $('#contact-reply-dialog');
        $contactReplyDialog.find('[name="id"]').val('');
        $contactReplyDialog.find('[name="reply"]').val('');
        $contactReplyDialog.find('.user-message-container').html('');
    },
    'reply': function(){
        common.loadFancybox(function(){
            var $contactReplyDialog = $('#contact-reply-dialog');
            $('.contact-item .reply-link').fancybox({
                'onComplete': function(instance, slide){
                    var id = slide.opts.$orig.attr('data-id');
                    if(id != $contactReplyDialog.find('[name="id"]').val()){
                        contact.reset();
                        $contact = $('#contact-' + id);
                        $contactReplyDialog.find('[name="id"]').val(id);
                        if($contact.find('.ci-reply-container').length){
                            var reply = $contact.find('.ci-reply-container').html().replace(/<br>/g, '');
                        }else{
                            var reply = '';
                        }
                        $contactReplyDialog.find('[name="reply"]').val(reply);
                        $contactReplyDialog.find('.user-message-container').html($contact.find('.ci-content').html());
                    }
                    autosize.update($contactReplyDialog.find('[name="reply"]'));
                }
            });
        });
    },
    'submitReply': function(){
        $(document).on('submit', '#contact-reply-form', function(){
            $('#contact-reply-dialog').block({
                'zindex': 120000
            });
            $.ajax({
                'type': 'POST',
                'url': system.url('cms/admin/contact/reply'),
                'data': $('#contact-reply-form').serialize(),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    $('#contact-reply-dialog').unblock();
                    if(resp.status){
                        contact.reset();
                        $.fancybox.close();
                        $('#contact-'+resp.data.id).find('.ci-reply').html(['<strong>管理员回复：</strong>',
                            '<span class="ci-reply-container">', resp.data.reply.replace(/\n/g, '<br>'), '</span>'].join(''));
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
            return false;
        });
    },
    'init': function(){
        this.reply();
        this.submitReply();
    }
};
$(function(){
    contact.init();
});
</script>