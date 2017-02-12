<?php
/**
 * @var $signature array 签名信息
 */
?>
<div class="index-form">
	<form method="post" id="form" action="<?php echo $this->url('team/create')?>">
		<fieldset>
			<input name="name" placeholder="组合名称" type="text" id="name">
		</fieldset>
		<fieldset>
			<input name="photo_server_id" type="hidden" id="photo-server-id">
			<div id="upload-photo-container">
				<img src="http://127.0.0.2/file/pic/f/10000?t=4&dw=400" id="photo-preview" class="hide">
				<a href="javascript:;" id="upload-photo-link">点击上传照片</a>
			</div>
		</fieldset>
		<fieldset>
			<textarea name="blessing" id="blessing" class="autosize" placeholder="亲，请点击这里，留下你们对公司最美好祝福吧"></textarea>
		</fieldset>
		<fieldset>
			<h6>请选择参加组别</h6>
			<div class="radio-container">
				<label>
					<input name="type" checked="checked" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE?>" type="radio">
					最具夫妻相
					<?php echo \fay\helpers\HtmlHelper::link('（查看作品）', array('team/list', array(
						'type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_COUPLE,
					), false), array(
						'class'=>'show-teams'
					))?>
				</label>
				<label>
					<input name="type" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY?>" type="radio">
					最佳创意奖
					<?php echo \fay\helpers\HtmlHelper::link('（查看作品）', array('team/list', array(
						'type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_ORIGINALITY,
					), false), array(
						'class'=>'show-teams'
					))?>
				</label>
				<label>
					<input name="type" value="<?php echo \valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING?>" type="radio">
					最赞祝福语
					<?php echo \fay\helpers\HtmlHelper::link('（查看作品）', array('team/list', array(
						'type'=>\valentine\models\tables\ValentineUserTeamsTable::TYPE_BLESSING,
					), false), array(
						'class'=>'show-teams'
					))?>
				</label>
			</div>
		</fieldset>
		<fieldset class="submit-container">
			<a href="javascript:;" id="submit-link">点击提交</a>
		</fieldset>
	</form>
</div>
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
$(function(){
	//文本域自适应
	system.getScript(system.assets('js/autosize.min.js'), function(){
		autosize($('textarea.autosize'));
	});
	
	//表单提交
	$('#submit-link').on('click', function(){
		if(!$('#name').val()){
			common.toast('组合名称不能为空', 'error');
			return false;
		}
		if(!$('#blessing').val()){
			common.toast('对公司的祝福不能为空', 'error');
			return false;
		}
		if(!$('#photo-server-id').val()){
			common.toast('请上传照片', 'error');
			return false;
		}
		$('#form').submit();
	});
	
	$('#upload-photo-link').on('click', function(){
		wx.chooseImage({
			'count': 1,
			'success': function(res){
				var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
				$('#img-local-id').text(localIds);
				$('#photo-preview').attr('src', localIds.toString()).show();
				
				wx.uploadImage({
					localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
					isShowProgressTips: 1, // 默认为1，显示进度提示
					success: function(res){
						var serverId = res.serverId; // 返回图片的服务器端ID
						$('#photo-server-id').val(serverId.toString());
					}
				});
			}
		});
	});
});
</script>