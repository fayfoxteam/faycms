<div id="page-item"><?php echo $page['content'];?></div>

<div id="message-online" style="padding:0 40px 100px;">
<form method="post" action="" id="leave-message-form">
    <table width="475">
        <tr>
            <td height="43" width="53" align="center">姓名</td>
            <td><input type="text" name="realname" /></td>
            <td width="90" align="center">联系电话</td>
            <td><input type="text" name="phone" /></td>
        </tr>
        <tr>
            <td height="43" align="center">邮箱</td>
            <td><input type="text" name="email" /></td>
            <td align="center">单位</td>
            <td><input type="text" name="company" /></td>
        </tr>
        <tr>
            <td height="43" align="center" valign="top">留言</td>
            <td colspan="3"><textarea name="message" style="width:401px;height:78px;"></textarea></td>
        </tr>
        <tr>
            <td colspan="4" align="right" height="43">
                <a href="javascript:;" class="big-button f-right" id="leave-message-form-submit">提 交</a>
            </td>
        </tr>
    </table>
</form>
</div>
<script>
$(function(){
    var flag = true;
    $("#leave-message-form-submit").click(function(){
        var o = this;
        if(flag){
            $(this).before('<img src="'+system.assets('images/throbber.gif')+'" style="margin-right:10px;" />');
            flag = false;
            $.ajax({
                type: "POST",
                url: system.siteUrl("contact/markmessage"),
                data: $("#leave-message-form").serialize(),
                dataType: "json",
                cache: false,
                success: function(data){
                    $(o).parent().find("img").remove();
                    alert("留言成功");
                    flag = true;
                }
            });
        }
    });
});
</script>