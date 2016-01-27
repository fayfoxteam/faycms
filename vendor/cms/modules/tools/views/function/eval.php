
<div class="row">
	<div class="col-12">
		<form method="post" id="form">
			<div class="box">
				<div class="box-title"><h3>Code</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('key', array(
						'class'=>'hide',
						'id'=>'key',
					), "<?php\r\n");?>
					<pre id="php-code"></pre>
					<a href="javascript:;" id="form-submit" class="btn mt5">Run</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-12">
		<div class="box">
			<div class="box-title"><h3>Result</h3></div>
			<div class="box-content">
				<div style="min-height:239px"><?php eval('?>'. $key);?></div>
			</div>
		</div>
	</div>
</div>
<style type="text/css" media="screen">
.ace_editor {
	border: 1px solid lightgray;
	height: 200px;
}
</style>
<script type="text/javascript" src="<?php echo $this->assets('js/ace/src-min/ace.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/ace/src-min/ext-language_tools.js')?>"></script>
<script>
var editor = ace.edit('php-code');
editor.setTheme('ace/theme/monokai');
editor.session.setMode('ace/mode/php');
editor.setAutoScrollEditorIntoView(true);
editor.setOptions({
	enableBasicAutocompletion: true,
	enableSnippets: true,
	enableLiveAutocompletion: true,
	maxLines: 30,
	minLines: 10
});
editor.getSession().on('change', function(e) {
	$('#key').val(editor.getValue());
});
editor.commands.addCommand({
    name: 'run',
    bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
    exec: function(editor) {
        $('#form').submit();
    }
});
editor.setValue($('#key').val(), 1);
</script>