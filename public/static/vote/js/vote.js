/**
 * 
 */

$(function(){
	//小工具提示
	$('[data-toggle="tooltip"]').tooltip();
	$('#tooltip').tooltip();


});


var login =
{
    'login_in': function()
    {
        $('#login_in').on('click', function(){
            var username = $('#username').val();
            if (!username)
            {
                alert('请输入用户名');
                return;
            }
            var password = $('#password').val();
            if (!password)
            {
                alert('请输入密码');
            }
            $.ajax({
                url:'login/index',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: username,
                    password: password
                },
                success: function(data)
                {
                    if (data.code == 0)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            });
        });
    },
    'init': function()
    {
        this.login_in();
    }
}

login.init();