<div id="images"></div>
<a href="javascript:;" id="download-link">下载</a>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?php echo $signature['appId']?>', // 必填，公众号的唯一标识
        timestamp: <?php echo $signature['timestamp']?>, // 必填，生成签名的时间戳
        nonceStr: '<?php echo $signature['nonceStr']?>', // 必填，生成签名的随机串
        signature: '<?php echo $signature['signature']?>',// 必填，签名，见附录1
        jsApiList: ['chooseImage', 'uploadImage', 'downloadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    
    var serverIds = [
        'mbWWV51AOHLv3h_lTnDXF3ImCjtUJFX4kdrnrWyy4RQPHtLzKDFcgYsHw3FrLjxj',
        'xBztv03uFs0_imD3RbEam337QxfITZmG2maheXGyu7VH-dt6xvMe2xf8HYwN9tTY',
        'Td1AsuKZ-krmqNuwQ6oCDbbKpB4d8UuzPTP9jkrDm4uyLz-rhHoaKCuMl3Tgj5Vj',
        '9TO2EAnK_QxnivbgGEKJwQMjbaeA7x2a7IRdj9Hsj756gVLQcTDZQbtEz3q-GmyD',
        'le8xtv5x_5Y8iiFc424iuSWWLY-Fm_6vaFrieimJZLXCqY2nJ3Bgq293XzCMEFBc',
        'eGP5y8hX7pJmQMCb4sd9gJJbgRZlkiMCW0UFAYfiLO6QJveANuRQfXQDpROXA0bl',
        'SMrHMC_srluGVqTOXQRvujVMEyu6x7diZxmRhF-C5Hj7BTCaK9y4Tu9ni7qZtRS0',
        'd04MkJ5b-vNC1Xoe17gQY2aaarR31BluoFZrbNriv4hLuQU8vlo_3Nh1KRTtqptI',
        'r575hp-bJmAzfJAXUVJcWjnFpM3VMeCiHVntrssydpMUujl_UxFrj7sGC3WMXz-6',
        'Hrz8Cxch0fDGZHyMZusk-Pok9lHK8r2ZYXnsmcRBuRPTamVWI2TK2Z9_J3HXv5hQ'
    ];
    
    var i = 0;
    $('#download-link').on('click', function(){
        download(i);
    });
    
    function download(j){
        wx.downloadImage({
            'serverId': serverIds[j],
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#images').append('<img src="'+localId.toString()+'">');
                i++;
                if(i <= serverIds.length){
                    download(i);
                }
            }
        });
    }

    
</script>