<a href="javascript:;" id="download-link">下载</a>
<img id="local-id" src="">
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
    
    $('#download-link').on('click', function(){
        wx.downloadImage({
            'serverId': 'MS-rI3E5YDoKYZF8E867GIJ0ZbagaFprY6tP8W6KbDUDuAFCBel_oa-0Kq7aONZQ',
            'isShowProgressTips': 1,
            'success': function(res){
                var localId = res.localId; // 返回图片下载后的本地ID
                $('#local-id').attr('src', localId.toString());
            }
        });
    });
</script>