<form method="post" id="form">
	<div class="row">
		<div class="col-12">
			<div class="box">
				<div class="box-title"><h3>Code</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('key', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">运行</a>
					<a href="javascript:;" id="form-reset" class="btn btn-grey mt5">重置</a>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:200px"><?php eval($key . '?>');?></div>
				</div>
			</div>
		</div>
	</div>
</form>
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
	if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
		$("#form").submit();
		return false;
	}
});
</script>