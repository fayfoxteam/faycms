/**
 * 
 */

$(function(){
	//小工具提示
	$('[data-toggle="tooltip"]').tooltip();
	$('#tooltip').tooltip();

});


var vote =
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
                return;
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
    'vote': function()
    {
        $('#vote_submit').on('click', function(){
            var data = [];
            $('.checked:checked').each(function(){
                data.push($(this).data('id'));
            });
            console.log(data);
            var user_id = system.user_id;
            if (!user_id)
            {
                alert('请登录后再进行投票');
                return;
            }
            if (data.length == 0)
            {
            	alert('请选择老师进行投票');
            	return;
            }
            $.ajax({
                url: 'index/vote',
                type: 'POST',
                dataType: 'json',
                data: {
                    data: data,
                    user_id: user_id
                },
                success: function(data)
                {
                    if (data.code == 0)
                    {
                        alert('投票成功');
                        window,location.reload();
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
        this.vote();
    }
}

vote.init();

