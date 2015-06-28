<?php
?>
<div class="box" id="box-tags" data-name="tags">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>标签</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('tags', array(
			'id'=>'tags',
			'class'=>'form-control',
		))?>
	</div>
</div>
<script>
system.getScript(system.url('faycms/js/fayfox.textext.js'), function(){
	$("#tags").ftextext({
		'url':system.url('admin/tag/search')
	});
});
</script>