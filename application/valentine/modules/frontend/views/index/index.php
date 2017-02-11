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
<script>
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