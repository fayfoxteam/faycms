/**
 * Created by dev on 15/6/7.
 */
function AddFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle)
    } catch(e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "")
        } catch(e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加")
        }
    }
}


$(function(){
    $('#weixin').hover(function(){
       $('.qcode').fadeIn();
    }, function(){
       $('.qcode').fadeOut();
    });
});