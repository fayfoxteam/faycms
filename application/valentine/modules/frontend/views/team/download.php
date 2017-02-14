<a href="javascript:;" id="download-link-1">下载</a>
<img id="local-id-1" src="">
<a href="javascript:;" id="download-link-2">下载</a>
<img id="local-id-2" src="">
<a href="javascript:;" id="download-link-3">下载</a>
<img id="local-id-3" src="">
<a href="javascript:;" id="download-link-4">下载</a>
<img id="local-id-4" src="">
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
    
    $('#download-link-1').on('click', function(){
        wx.downloadImage({
            'serverId': '41RRkY0lHAjGJ0sBTq3zZEcNXvDAA16E1QTBPGL7GHwGQUXQi2I9SII9TQhOz5tM',
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#local-id-1').attr('src', localId.toString());
            }
        });
    });

    $('#download-link-2').on('click', function(){
        wx.downloadImage({
            'serverId': 'co6w775aO0R782ArNol3Cbi1-36jzjhZ-jh0Y3oNfN0DnTBBU3Kdu35oiVoiYPDw',
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#local-id-2').attr('src', localId.toString());
            }
        });
    });

    $('#download-link-3').on('click', function(){
        wx.downloadImage({
            'serverId': '2pnEXbJk9eA4WlNo442uVwSFuEUECEiu0F5e_Mw-zO4c_SL6qI5Z0pHhRfNoylgk',
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#local-id-3').attr('src', localId.toString());
            }
        });
    });

    $('#download-link-4').on('click', function(){
        wx.downloadImage({
            'serverId': 'v0atRTqmjMdXDRQNF69EApkv0JEJnGhS1JrDuc1iDpMXCQPcMErFUYTml67U4lmH',
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#local-id-4').attr('src', localId.toString());
            }
        });
    });
</script>