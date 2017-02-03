<?php
/**
 * @var $jsApiParameters string json字符串
 * @var $trade \fay\services\payment\methods\models\PaymentTradeModel
 */
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>微信支付-支付</title>
	<script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					if(res.err_msg=='get_brand_wcpay_request:cancel'){
						//取消付款
						alert('取消付款');
						window.location.href = '<?php echo $trade->getReturnUrl()?>';
					}else if(res.err_msg=='get_brand_wcpay_request:ok'){
						//付款成功
						alert('支付成功');
						window.location.href = '<?php echo $trade->getReturnUrl()?>';
					}else if(res.err_msg=='get_brand_wcpay_request:fail' && res.err_code==3 && "{$payUserAgent}"=="wap"){
						//不允许跨号支付
						alert('不允许跨号支付');
					}else{
						alert("res.err_code:"+res.err_code+";res.err_desc:"+res.err_desc+";res.err_msg:"+res.err_msg);
					}
				}
			);
		}
		
		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', jsApiCall);
					document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				}
			}else{
				jsApiCall();
			}
		}
		
		callpay();
	</script>
</head>
<body></body>
</html>