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
            alert('111');
        });
    },
    'init': function()
    {
        this.login_in();
    }
}

login.init();