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
        'I_x4hB8BYInpsnr0jH3eAxKbjw8CK8asufIXKTLpJAHjx0mvTq6Jni-F1433EBbi',
        'mspgifEHoILlVPrE1Usp5TQDPfwk4Rg2b_kbC7_z7kYcN5XqlcpqKAzeFnxMotui',
        'hzrIm0aL3JYuXynm8BJu6sz4E8Vyq1gwBpDFQWgyP9qP-s5fBOFho4fH8fau8kEy',
        'gFie17X2HgX4IiJ7yeaoXaK5Nj6uVLqSZLx2PDSVH8yfcoU3vi6FDRfPEdoI3YK6',
        'xcnbMRXjT7aa5hgBbowU54CtH8AYyOROejz1hYxNdKUraQengJZTQMQKz_wx2RMI',
        'fhUjt21TerYyU2OxGiUBpyy3cfaocTcacd603-8w2Pe5VE6gaApFicMB0fVnXcKq',
        '6fR8t9YtD8RNa0sA8fH_CgNs3NQtyZwOoJsyUw64OtmmUBbtrnYqof_M3eM66feH',
        '5pyPwzkQJRirMreR2seEEX1F6NUPcfLETvw9B8VKm9TDOZmU5eeUYPPAN24vn1D8',
        '76WxVEcsB6p2FXpuTLIXRyfqwWaX2xdIPjcSlJNvzod78iTllnuEL2xQaTuji9dz',
        'xVFbgIvZIAn7F9J6DTvr4eZ7PPBkK-fpQFXUTD3MGAJq5qiLCM1Qy5-JZP-kUc0W'
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