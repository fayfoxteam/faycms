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
        'U3-Mh85jyTDxACOXmjq4IoY-_3LofRO2z_5rLyEwABShch-cpF01RIzRGG5dgCGy',
        'TALxmMtG6jRlKOLBd9nyPD9rwfDW-WxbnbLp89a2uPPDu6hLeK0RgLiwCDG8J9Nk',
        'BIuUyPnlxA3G88H5V7V8a_30EtG4-E9bfYK6YjR_01_5pBrbvYTQamPtH9P4HVSl',
        'E_8i2pSpI8y5LCifTgbi9Tp3rIpFQ-Ic-fiRknaKCxPlJFQPoeI6W79lYagiubtt',
        'QlyppEAnIL3fAdTmCrKtWhxTPdMQFvfSmEq8ijr2x3eEFFGWFXORWzdVv0HW0nHd',
        '1xieteNf2xocVAHbQGGcUL6psI98RVtu3oKvMlIdRV-quzLPTIb5Ofv0ySg-FY1k',
        'n47Oi8w8WiMFLmWcLYtOZWaKvhIOHR-Vbra-WQnY_1S6RH4yvA7BDZu1QAeaLgSe',
        '6nYQWNwDPPibzttin5RVMgUznbymZ5oNWXtCJPCG1IWfJRgtp9n-usNLHIVwQzVj',
        'MVd5p2rQOxfwhDdMPgBi6GIWNXi-6wng6tKL7MPCZNykWGvPsJCAMnKLz_EyeUdT',
        '8mVz2FkAVIGbJWIiBLzbi662crfra0p_qxMgqw3eSRjAqPNdNh68OFTWd1UbCm_9',
        'AP_-v-Lz_iMqKpIrBbz9kOI71w9YPYQnDqh8FNboL7S-aC5kYQqMBFyZp7be0pUo',
        'v6yyp7F78N0bon20bdRb22aSRjMIO-y4tbGIsad043j_-FR_I_VQqJ5znTnam7fP',
        'LjwFtopunW_rohdeeiEbgnBlTNcVps7pBe-ihNZEsm4Gcc_tL3zIszCJNbvJ4cAZ'
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