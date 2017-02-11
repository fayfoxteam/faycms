<?php
/**
 * @var $signature array 签名信息
 */
?>
<form method="post">
	<fieldset>
		<input name="name" placeholder="组合名称" type="text">
	</fieldset>
	<fieldset>
		<input name="photo" type="hidden">
		<div id="img-local-id"></div>
		<a href="javascript:;" id="upload-photo-link">点击上传照片</a>
	</fieldset>
	<fieldset>
		<textarea name="blessing" placeholder="点击输入对公司祝福语"></textarea>
	</fieldset>
	<fieldset>
		<h6>请选择参加组别</h6>
		<label>
			<input name="type" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE?>" type="radio">
			最具夫妻相
		</label>
		<label>
			<input name="type" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY?>" type="radio">
			最佳创意奖
		</label>
		<label>
			<input name="type" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING?>" type="radio">
			最赞祝福语
		</label>
	</fieldset>
	<fieldset>
		<a href="javascript:;" id="submit-link">点击提交</a>
	</fieldset>
</form>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
	debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	appId: '<?php echo $signature['app_id']?>', // 必填，公众号的唯一标识
	timestamp: <?php echo $signature['timestamp']?>, // 必填，生成签名的时间戳
	nonceStr: '<?php echo $signature['nonceStr']?>', // 必填，生成签名的随机串
	signature: '<?php echo $signature['signature']?>',// 必填，签名，见附录1
	jsApiList: ['chooseImage', 'uploadImage', 'downloadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
$(function(){
	$('#upload-photo-link').on('click', function(){
		wx.chooseImage({
			'count': 1,
			'success': function (res) {
				var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
				$('#img-local-id').text(localIds);
			}
		});
	});
});
</script>