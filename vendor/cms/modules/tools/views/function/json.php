<form method="post" id="form">
	<div class="row">
		<div class="col-12">
			<div class="box" id="formatter-box">
				<div class="box-title"><h3>Formatter</h3></div>
				<div class="box-content">
					<pre id="formatter-json-editor"></pre>
					<a href="javascript:;" id="format-link" class="btn mt5">格式化</a>
					<a href="javascript:;" id="compress-link" class="btn mt5">压缩</a>
					<a href="javascript:;" id="php-json-encode-link" class="btn mt5">json_encode</a>
					<a href="javascript:;" id="php-json-decode-link" class="btn mt5">json_decode</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>json_decode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('json', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><textarea class="form-control h200 autosize"><?php var_export(json_decode(F::input()->post('json'), true));?></textarea></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>json_encode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('array', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
					<span class="fc-grey">Type php array code here. eg:<code>array('hello')</code></span>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><textarea class="form-control h200 autosize"><?php echo json_encode(eval('return '.F::input()->post('array').';'));?></textarea></div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
$(function(){
	var toolsJson = {
		'formatterEditor': null,
		'initFormatter': function(){
			system.getScript(system.assets('js/ace/src-min/ace.js'), function(){
				system.getScript(system.assets('js/ace/src-min/ext-language_tools.js'), function(){
					ace.config.set('basePath', system.assets('js/ace/src-min/'));
					toolsJson.formatterEditor = ace.edit('formatter-json-editor');
					toolsJson.formatterEditor.setOptions({
						enableBasicAutocompletion: true,
						enableSnippets: true,
						enableLiveAutocompletion: true,
						maxLines: 30,
						minLines: 30
					});
					toolsJson.formatterEditor.setTheme('ace/theme/monokai');
					//设置上下外边距
					toolsJson.formatterEditor.renderer.setScrollMargin(10, 10);
					//设置语言模式
					toolsJson.formatterEditor.session.setMode('ace/mode/json');
					toolsJson.formatterEditor.setAutoScrollEditorIntoView(true);
					//横向超出是否换行显示
					toolsJson.formatterEditor.getSession().setUseWrapMode(true);
				});
			});
		},
		'formatEvents': function(){
			$('#formatter-box').on('click', '#format-link', function(){
				try{
					var jsonObj = $.parseJSON(toolsJson.formatterEditor.getValue());
				}catch(e){
					common.alert('JSON格式错误');
					jsonObj = false;
				}
				
				if(jsonObj){
					toolsJson.formatterEditor.setValue(JSON.stringify(jsonObj, null, 4));
				}
			}).on('click', '#compress-link', function(){
				try{
					var jsonObj = $.parseJSON(toolsJson.formatterEditor.getValue());
				}catch(e){
					common.alert('JSON格式错误');
					jsonObj = false;
				}
				
				if(jsonObj){
					toolsJson.formatterEditor.setValue(JSON.stringify(jsonObj));
				}
			}).on('click', '#php-json-encode-link', function(){
				
			}).on('click', '#php-json-decode-link', function(){
				$.ajax({
					type: 'POST',
					url: system.url('tools/function/json-decode'),
					data: {
						'code': toolsJson.formatterEditor.getValue()
					},
					cache: false,
					success: function(resp){
						if(resp.status){
							toolsJson.formatterEditor.setValue('<\?php \r\n' + resp.data.code + ';');
							toolsJson.formatterEditor.session.setMode('ace/mode/php');
						}else{
							common.alert(resp.message);
						}
					}
				});
			});
		},
		'init': function(){
			this.initFormatter();
			this.formatEvents();
		}
	};
	
	toolsJson.init();
})
</script>