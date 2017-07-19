<div class="row">
    <div class="col-12">
        <div class="box" id="formatter-box">
            <div class="box-title"><h3>Formatter</h3></div>
            <div class="box-content">
                <pre id="formatter-json-editor"></pre>
                <a href="javascript:" id="format-link" class="btn mt5">格式化</a>
                <a href="javascript:" id="compress-link" class="btn mt5">压缩</a>
                <a href="javascript:" id="php-json-encode-link" class="btn mt5" title="将标准PHP语法的array解析为json">json_encode</a>
                <a href="javascript:" id="php-json-decode-link" class="btn mt5" title="将json解析成PHP array代码">json_decode</a>
            </div>
        </div>
    </div>
</div>
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
                    toolsJson.formatterEditor.session.setMode('ace/mode/json');
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
                    toolsJson.formatterEditor.session.setMode('ace/mode/json');
                    toolsJson.formatterEditor.setValue(JSON.stringify(jsonObj));
                }
            }).on('click', '#php-json-encode-link', function(){
                var code = toolsJson.formatterEditor.getValue();
                if(/^<\?php(.*)/.test(code)){
                    //清理前缀的<\?php
                    code = code.replace(/^<\?php(.*)/, '');
                }
                $.ajax({
                    'type': 'POST',
                    'url': system.url('cms/tools/function/json-encode'),
                    'data': {
                        'code': code
                    },
                    'dataType': 'json',
                    'cache': false,
                    'error': function(XMLHttpRequest, textStatus, errorThrown){
                        common.alert('PHP代码语法错误');
                    },
                    'success': function(resp){
                        if(resp.status){
                            toolsJson.formatterEditor.setValue(resp.data.code);
                            toolsJson.formatterEditor.session.setMode('ace/mode/json');
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            }).on('click', '#php-json-decode-link', function(){
                $.ajax({
                    'type': 'POST',
                    'url': system.url('cms/tools/function/json-decode'),
                    'data': {
                        'code': toolsJson.formatterEditor.getValue()
                    },
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        console.log(resp)
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