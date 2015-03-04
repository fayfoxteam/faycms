<?php
?>
<form method="post" id="form">
	<?php echo F::form()->textarea('key', array(
		'class'=>'wp90 h200 autosize',
	));?>
	<div class="mt20">
		<a href="javascript:;" id="form-submit" class="btn-1">提交</a>
	</div>
</form>
<div class="mt20">
	<h3>执行结果</h3>
	<?php eval($key . '?>');?>
</div>

<script>
(function($){
	$.fn.extend({
		insertAtCaret: function(myValue){
			var $t=$(this)[0];
			if(document.selection){
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			}else if($t.selectionStart || $t.selectionStart == '0'){
				var startPos = $t.selectionStart;
				var endPos = $t.selectionEnd;
				var scrollTop = $t.scrollTop;
				$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
				this.focus();
				$t.selectionStart = startPos + myValue.length;
				$t.selectionEnd = startPos + myValue.length;
				$t.scrollTop = scrollTop;
			}
			else {
				this.value += myValue;
				this.focus();
			}
		}
	})
})(jQuery);
$("[name='key']").keydown(function(event){
	if(event.keyCode == 9){
		$(this).insertAtCaret('  ');
		return false;
	}
	if(event.keyCode == 83 && event.ctrlKey){
		$("#form").submit();
		return false;
	}
});
</script>