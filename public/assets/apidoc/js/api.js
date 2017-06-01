/**
 * api管理
 */
var api = {
    'inputTypeMap': {},
    'outputForm': null,
    /**
     * 添加请求参数
     */
    'addInputParameter': function(){
        common.loadFancybox(function(){
            $('#add-input-parameter-link').fancybox({
                'onComplete':function(){
                    //初始化编辑框
                    var $addInputParameterForm = $('#add-input-parameter-form');
                    $addInputParameterForm.find('[name="name"]').val('');
                    $addInputParameterForm.find('[name="required"][value="0"]').prop('checked', 'checked');
                    $addInputParameterForm.find('[name="description"]').val('');
                    $addInputParameterForm.find('[name="sample"]').val('');
                    $addInputParameterForm.find('[name="since"]').val('');
                }
            });
        });
    },
    /**
     * 编辑请求参数
     */
    'editInputParameter': function(){
        common.loadFancybox(function(){
            $('.edit-input-parameter-link').fancybox({
                'onComplete':function(instance, slide){
                    var $container = slide.opts.$orig.parent().parent();
                    //初始化编辑框
                    $('#editing-input-parameter-name').text($container.find('.input-name').val());
                    var $editInputParameterForm = $('#edit-input-parameter-form');
                    $editInputParameterForm.find('[name="selector"]').val($container.parent().attr('id'));
                    $editInputParameterForm.find('[name="name"]').val($container.find('.input-name').val());
                    $editInputParameterForm.find('[name="type"]').val($container.find('.input-type').val());
                    $editInputParameterForm.find('[name="required"][value="'+$container.find('.input-required').val()+'"]').prop('checked', 'checked');
                    $editInputParameterForm.find('[name="description"]').val($container.find('.input-description').val());
                    $editInputParameterForm.find('[name="sample"]').val($container.find('.input-sample').val());
                    $editInputParameterForm.find('[name="since"]').val($container.find('.input-since').val());
                }
            });
        });
    },
    /**
     * 验证输入参数表单
     * 这个表单并不会被提交，只是做一下表单验证
     */
    'validInputParameter': function(rules, labels){
        system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
            $('.input-parameter-form').validform({
                'onError': function(obj, msg){
                    var last = $.validform.getElementsByName(obj).last();
                    last.poshytip('destroy');
                    //报错
                    last.poshytip({
                        'className': 'tip-twitter',
                        'showOn': 'none',
                        'alignTo': 'target',
                        'alignX': 'inner-right',
                        'offsetX': -60,
                        'offsetY': 5,
                        'content': msg
                    }).poshytip('show');
                },
                'onSuccess': function(obj){
                    var last = $.validform.getElementsByName(obj).last();
                    last.poshytip('destroy');
                },
                'beforeSubmit': function(form){
                    //获取输入值
                    var name = form.find('[name="name"]').val();
                    var type = form.find('[name="type"]').val();
                    var required = form.find('[name="required"]:checked').val();
                    var description = form.find('[name="description"]').val();
                    var sample = form.find('[name="sample"]').val();
                    var since = form.find('[name="since"]').val();
                    
                    if(form.attr('id').indexOf('add') == 0){
                        //添加
                        var timestamp = new Date().getTime();
                        
                        //插入表格行
                        var $inputParameterTable = $('#input-parameter-table');
                        $inputParameterTable.find('tbody').append(['<tr id="new-', timestamp, '" valign="top">',
                            '<td>',
                                '<input type="hidden" name="inputs[', timestamp, '][name]" value="', name, '" class="input-name" />',
                                '<input type="hidden" name="inputs[', timestamp, '][type]" value="', type, '" class="input-type" />',
                                '<input type="hidden" name="inputs[', timestamp, '][required]" value="', required, '" class="input-required" />',
                                '<input type="hidden" name="inputs[', timestamp, '][description]" value="', description, '" class="input-description" />',
                                '<input type="hidden" name="inputs[', timestamp, '][sample]" value="', sample, '" class="input-sample" />',
                                '<input type="hidden" name="inputs[', timestamp, '][since]" value="', since, '" class="input-since" />',
                                '<strong>', system.encode(name), '</strong>',
                                '<div class="row-actions">',
                                    '<a href="#edit-input-parameter-dialog" class="edit-input-parameter-link">编辑</a>',
                                    '<a href="javascript:;" class="fc-red remove-input-parameter-link">删除</a>',
                                '</div>',
                            '</td>',
                            '<td>', api.inputTypeMap[type], '</td>',
                            '<td>', (required == 1 ? '<span class="fc-green">是</span>' : '否'), '</td>',
                            '<td>', system.encode(since), '</td>',
                            '<td>', system.encode(description), '</td>',
                        '</tr>'].join(''));
                        api.editInputParameter();
                        $inputParameterTable.find('tbody tr').removeClass('alternate');
                        $inputParameterTable.find('tbody tr:even').addClass('alternate');
                    }else{
                        //编辑
                        var selector = form.find('[name="selector"]').val();
                        var $input = $('#'+selector);
                        
                        //编辑隐藏域
                        $input.find('.input-name').val(name);
                        $input.find('.input-type').val(type);
                        $input.find('.input-required').val(required);
                        $input.find('.input-description').val(description);
                        $input.find('.input-sample').val(sample);
                        $input.find('.input-since').val(since);
                        
                        //修改表格行显示
                        $input.find('td:eq(0) strong').text(name);
                        $input.find('td:eq(1)').text(api.inputTypeMap[type]);
                        $input.find('td:eq(2)').html(required == 1 ? '<span class="fc-green">是</span>' : '否');
                        $input.find('td:eq(3)').text(since);
                        $input.find('td:eq(4)').text(description);
                    }
                    
                    $.fancybox.close();
                    return false;
                }
            }, rules, labels);
        });
    },
    /**
     * 移除请求参数
     */
    'removeInputParameter': function(){
        $('#input-parameter-table').on('click', '.remove-input-parameter-link', function(){
            if(confirm('确定要删除此请求参数吗？')){
                $(this).parent().parent().parent().fadeOut(function(){
                    $(this).remove();
                })
            }
        })
    },
    /**
     * 添加属性
     */
    'addOutput': function(){
        common.loadFancybox(function(){
            $('#add-output-link').fancybox({
                'onComplete': function(){
                    //初始化编辑框
                    var $addOutputForm = $('#add-output-form');
                    $addOutputForm.find('[name="name"]').val('');
                    $addOutputForm.find('[name="required"][value="0"]').prop('checked', 'checked');
                    $addOutputForm.find('[name="description"]').val('');
                    $addOutputForm.find('[name="sample"]').val('');
                    $addOutputForm.find('[name="since"]').val('');
                }
            });
        });
    },
    /**
     * 编辑属性
     */
    'editOutput': function(){
        common.loadFancybox(function(){
            $('.edit-output-link').fancybox({
                'onComplete':function(instance, slide){
                    var $container = slide.opts.$orig.parent().parent().parent().parent();
                    //初始化编辑框
                    $('#editing-output-name').text($container.find('.input-name').val());
                    var $editOutputForm = $('#edit-output-form');
                    $editOutputForm.find('[name="selector"]').val($container.attr('id'));
                    $editOutputForm.find('[name="name"]').val($container.find('.input-name').val());
                    $editOutputForm.find('[name="model_name"]').val($container.find('.input-model-name').val());
                    $editOutputForm.find('[name="is_array"][value="'+$container.find('.input-is-array').val()+'"]').prop('checked', 'checked');
                    $editOutputForm.find('[name="description"]').val($container.find('.input-description').val());
                    $editOutputForm.find('[name="sample"]').val($container.find('.input-sample').val());
                    $editOutputForm.find('[name="since"]').val($container.find('.input-since').val());
                }
            });
        });
    },
    /**
     * 模型选择自动补全
     */
    'autocomplete': function(){
        system.getScript(system.assets('faycms/js/fayfox.autocomplete.js'), function(){
            $("#add-output-model-name").autocomplete({
                "url" : system.url('apidoc/admin/model/search'),
                'startSuggestLength': 0,
                'onSelect': function(obj, data){
                    obj.val(data.name);
                    api.outputForm.check(obj);
                },
                'zindex': '111150'
            });
            $("#edit-output-model-name").autocomplete({
                "url" : system.url('apidoc/admin/model/search'),
                'startSuggestLength': 0,
                'onSelect': function(obj, data){
                    obj.val(data.name);
                    api.outputForm.check(obj);
                },
                'zindex': '111150'
            });
        });
    },
    /**
     * 验证输入参数表单
     * 这个表单并不会被提交，只是做一下表单验证
     */
    'validOutput': function(rules, labels){
        system.getScript(system.assets('faycms/js/fayfox.validform.min.js'), function(){
            api.outputForm = $('.output-form').validform({
                'onError': function(obj, msg){
                    var last = $.validform.getElementsByName(obj).last();
                    last.poshytip('destroy');
                    //报错
                    last.poshytip({
                        'className': 'tip-twitter',
                        'showOn': 'none',
                        'alignTo': 'target',
                        'alignX': 'inner-right',
                        'offsetX': -60,
                        'offsetY': 5,
                        'content': msg
                    }).poshytip('show');
                    $('.dialog').unblock();
                },
                'onSuccess': function(obj){
                    var last = $.validform.getElementsByName(obj).last();
                    last.poshytip('destroy');
                },
                'beforeCheck': function(form){
                    if(form.attr('id').indexOf('add') == 0){
                        $('#add-output-dialog').block({
                            'zindex': 120000
                        });
                    }else{
                        $('#edit-output-dialog').block({
                            'zindex': 120000
                        });
                    }
                },
                'beforeSubmit': function(form){
                    //获取输入值
                    var name = form.find('[name="name"]').val();
                    var model_name = form.find('[name="model_name"]').val();
                    var is_array = form.find('[name="is_array"]:checked').val();
                    var description = form.find('[name="description"]').val();
                    var sample = form.find('[name="sample"]').val();
                    var since = form.find('[name="since"]').val();
                    
                    if(form.attr('id').indexOf('add') == 0){
                        //添加
                        var timestamp = new Date().getTime();
                        
                        //插入行
                        $('#model-list').append(['<div class="dragsort-item" id="model-', timestamp, '">',
                            '<input type="hidden" name="outputs[', timestamp, '][name]" value="', name, '" class="input-name" />',
                            '<input type="hidden" name="outputs[', timestamp, '][model_name]" value="', model_name, '" class="input-model-name" />',
                            '<input type="hidden" name="outputs[', timestamp, '][is_array]" value="', is_array, '" class="input-is-array" />',
                            '<input type="hidden" name="outputs[', timestamp, '][description]" value="', description, '" class="input-description" />',
                            '<input type="hidden" name="outputs[', timestamp, '][sample]" value="', sample, '" class="input-sample" />',
                            '<input type="hidden" name="outputs[', timestamp, '][since]" value="', since, '" class="input-since" />',
                            '<a class="dragsort-rm" href="javascript:;"></a>',
                            '<a class="dragsort-item-selector"></a>',
                            '<div class="dragsort-item-container">',
                                '<span class="ib wp25">',
                                    '<strong>', name, '</strong>',
                                    '<p>',
                                        '<a href="#edit-output-dialog" class="edit-output-link">编辑</a>',
                                    '</p>',
                                '</span>',
                                '<span class="ib wp15 vat">', model_name, (is_array == '1' ? ' []' : ''), '</span>',
                                '<span class="ib vat">', description, '</span>',
                            '</div>',
                        '</div>'].join(''));
                        api.editOutput();
                    }else{
                        //编辑
                        var selector = form.find('[name="selector"]').val();
                        var $output = $('#'+selector);
                        
                        //编辑隐藏域
                        $output.find('.input-name').val(name);
                        $output.find('.input-model-name').val(model_name);
                        $output.find('.input-is-array').val(is_array);
                        $output.find('.input-description').val(description);
                        $output.find('.input-sample').val(sample);
                        $output.find('.input-since').val(since);
                        
                        //修改行显示
                        $output.find('span:eq(0) strong').text(name);
                        $output.find('span:eq(1)').text(model_name + (is_array == '1' ? ' []' : ''));
                        $output.find('span:eq(2)').text(description);
                    }
                    
                    $('.dialog').unblock();
                    $.fancybox.close();
                    return false;
                }
            }, rules, labels);
        });
    },
    'autoFormatSampleResponse': function(){
        var $sampleResponse = $('#sample-response');
        $sampleResponse.on('blur', function(){
            try{
                var jsonObj = $.parseJSON($sampleResponse.val());
            }catch(e){
                jsonObj = false;
            }

            if(jsonObj){
                $sampleResponse.val(JSON.stringify(jsonObj, null, 4));
                autosize.update($sampleResponse);
            }
        });
    },
    'init': function(){
        this.addInputParameter();
        this.editInputParameter();
        this.removeInputParameter();
        this.addOutput();
        this.editOutput();
        this.autocomplete();
        this.autoFormatSampleResponse();
    }
};