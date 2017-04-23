var widget = {
    'id': 0,
    'templateEditor': null,
    /**
     * 选择模版
     */
    'selectTemplate': function(){
        $('#select-template').on('change', function(){
            var templateCode = $(this).val();
            if(templateCode == 'custom'){
                widget.templateEditor.setReadOnly(false);
            }else{
                widget.templateEditor.setReadOnly(true);
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/widget/get-view-file'),
                    'data': {
                        'id': widget.id,
                        'view': templateCode
                    },
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        if(resp.status){
                            widget.templateEditor.setValue(resp.data, -1);
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            }
        });
    },
    /**
     * 开启缓存时，显示缓存时间输入框，关闭则隐藏
     */
    'whetherCache': function(){
        $('[name="f_widget_cache"]').on('change', function(){
            if($('[name="f_widget_cache"]:checked').val() == '1'){
                $('#cache-expire-container').show();
            }else{
                $('#cache-expire-container').hide();
            }
        });
    },
    /**
     * 编辑模版
     */
    'editTemplate': function(){
        var $codeEditor = $('#template-editor');
        if($codeEditor.length) {
            $codeEditor.addClass('hide').after('<pre id="template-editor-container"></pre>');
            system.getScript(system.assets('js/ace/src-min/ace.js'), function () {
                system.getScript(system.assets('js/ace/src-min/ext-language_tools.js'), function () {
                    ace.config.set('basePath', system.assets('js/ace/src-min/'));
                    widget.templateEditor = ace.edit('template-editor-container');
                    widget.templateEditor.setOptions({
                        enableBasicAutocompletion: true,
                        enableSnippets: true,
                        enableLiveAutocompletion: true,
                        maxLines: 30,
                        minLines: 10,
                        readOnly: $('#select-template').val() != 'custom'
                    });
                    widget.templateEditor.setTheme('ace/theme/monokai');
                    //设置语言模式
                    if($codeEditor.attr('data-mode')){
                        widget.templateEditor.session.setMode('ace/mode/' + $codeEditor.attr('data-mode'));
                    } else {
                        widget.templateEditor.session.setMode('ace/mode/php');
                    }
                    widget.templateEditor.setAutoScrollEditorIntoView(true);
                    //设置上下外边距
                    widget.templateEditor.renderer.setScrollMargin(10, 10);
                    //横向超出是否换行显示
                    if($codeEditor.attr('data-wrap-line')){
                        widget.templateEditor.getSession().setUseWrapMode(true);
                    }
                    //当文本被编辑时，实时更新隐藏的文本域
                    widget.templateEditor.getSession().on('change', function(){
                        $codeEditor.val(widget.templateEditor.getValue());
                    });
                    //初始化编辑器内容
                    widget.templateEditor.setValue($codeEditor.val(), -1);
                });
            });
        }
    },
    'init': function(){
        this.selectTemplate();
        this.whetherCache();
        this.editTemplate();
    }
};