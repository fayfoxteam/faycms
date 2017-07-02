/**
 * 表单验证插件
 * {
 *   文档:{
 *     表单状态:{
 *       normal:常规状态,
 *       checking:提交中（等待ajax验证返回）,
 *       checked:所有验证完成（包括ajax验证）,
 *       submitted:已提交
 *     }
 *   }
 * }
 */
;(function($){
    $.extend({
        'validform':{
            //这是一堆验证器
            'validators':{
                'required':function(value, params, attribute){
                    var settings = $.extend({
                        'message':'{$attribute}不能为空'
                    }, params);
                    
                    if((typeof(value) == 'object' && $.isEmptyObject(value)) || value == ''){
                        return $.validform._renderMsg(settings.message, {
                            'max':settings.max,
                            'min':settings.min,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }else{
                        return true;
                    }
                },
                'int':function(value, params, attribute){
                    var settings = $.extend({
                        'max':null,
                        'min':null,
                        'tooBig':'{$attribute}必须是不大于{$max}的整数',
                        'tooSmall':'{$attribute}必须是不小于{$min}的整数',
                        'message':'{$attribute}必须是整数'
                    }, params);
                    
                    //单个数字
                    if(!/^(-?[1-9]\d*|0)$/.test(value)){
                        return $.validform._renderMsg(settings.message, {
                            'max':settings.max,
                            'min':settings.min,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    
                    value = parseInt(value);
                    if(settings.max !== null && value > settings.max){
                        return $.validform._renderMsg(settings.tooBig, {
                            'max':settings.max,
                            'min':settings.min,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    if(settings.min !== null && value < settings.min){
                        return $.validform._renderMsg(settings.tooSmall, {
                            'max':settings.max,
                            'min':settings.min,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    return true
                },
                'string':function(value, params, attribute){
                    var settings = $.extend({
                        'format':null,//特殊格式
                        'max':null,
                        'min':null,
                        'equal':null,//定长
                        'tooLong':'{$attribute}不能超过{$max}个字符',
                        'tooShort':'{$attribute}不能少于{$min}个字符',
                        'notEqual':'{$attribute}长度必须为{$min}个字符',
                        'formatError':'{$attribute}格式不正确'
                    }, params);
                    
                    var formats = {
                        'alias':/^[a-zA-Z_][a-zA-Z_0-9-]{0,49}$/,//字母开头，不包含数字，字母，下划线和中横线以外的特殊字符
                        'numeric':/^\d+$/,//纯数字
                        'alnum':/^[a-zA-Z0-9]+$/,//数字+字母
                        'alias_space':/^[a-zA-Z_0-9- ]+$///数字，字母，下划线，中横线和空格
                    };
                    
                    if(settings.format){
                        var pattern;
                        if(typeof(formats[settings.format]) != 'undefined'){
                            pattern = formats[settings.format];
                        }else{
                            if(typeof(settings.format) == 'object'){
                                pattern = settings.format;
                            }else{
                                eval('pattern = '+settings.format);
                            }
                        }
                        if(!pattern.test(value)){
                            return $.validform._renderMsg(settings.formatError, {
                                'min':settings.min,
                                'max':settings.max,
                                'equal':settings.equal,
                                'attribute':attribute ? attribute : '参数'
                            });
                        }
                    }
                    
                    if(settings.equal){
                        if(value.length == settings.equal){
                            return true;
                        }else{
                            return $.validform._renderMsg(settings.notEqual, {
                                'min':settings.min,
                                'max':settings.max,
                                'equal':settings.equal,
                                'attribute':attribute ? attribute : '参数'
                            });
                        }
                    }
                    
                    if(settings.max !== null && value.length > settings.max){
                        return $.validform._renderMsg(settings.tooLong, {
                            'min':settings.min,
                            'max':settings.max,
                            'equal':settings.equal,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    if(settings.min !== null && value.length < settings.min){
                        return $.validform._renderMsg(settings.tooShort, {
                            'min':settings.min,
                            'max':settings.max,
                            'equal':settings.equal,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    return true;
                },
                'float':function(value, params, attribute){
                    var settings = $.extend({
                        'length':null,
                        'decimal':2,
                        'max':null,
                        'min':null,
                        'tooLong':'{$attribute}必须是{$min}到{$max}的数字',
                        'decimalTooLong':'{$attribute}小数位不能多于{$decimal}位',
                        'tooBig':'{$attribute}必须是不大于{$max}的数字',
                        'tooSmall':'{$attribute}必须是不小于{$min}的数字',
                        'message':'{$attribute}必须是数字'
                    }, params);
                    
                    if(!/^-?\d+(\.\d+)?$/.test(value)){
                        return $.validform._renderMsg(settings.message, {
                            'length':settings.length,
                            'decimal':settings.decimal,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    
                    var pointPos = value.indexOf('.');
                    if(pointPos != -1 && value.length - pointPos - 1 > settings.decimal){
                        return $.validform._renderMsg(settings.decimalTooLong, {
                            'length':settings.length,
                            'decimal':settings.decimal,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    
                    if(settings.length){
                        var max = Math.pow(10, settings.length - settings.decimal);
                        if(parseFloat(value) > max || parseFloat(value) < -max){
                            return $.validform._renderMsg(settings.tooLong, {
                                'length':settings.length,
                                'decimal':settings.decimal,
                                'max':(settings.max !== null && settings.max < max) ? settings.max : max,
                                'min':(settings.min !== null && settings.min > -max) ? settings.min : -max,
                                'attribute':attribute ? attribute : '参数'
                            });
                        }
                    }
                    
                    if(settings.max !== null && parseFloat(value) > settings.max){
                        return $.validform._renderMsg(settings.tooBig, {
                            'max':settings.max,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    
                    if(settings.min !== null && parseFloat(value) < settings.min){
                        return $.validform._renderMsg(settings.tooSmall, {
                            'min':settings.min,
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    
                    return true;
                },
                'range':function(value, params, attribute){
                    var settings = $.extend({
                        'range':[],
                        'not':false,
                        'message':'{$attribute}的取值非法'
                    }, params);
                    
                    if((settings.not && $.validform.inArray(value, settings.range))
                        || (!settings.not && !$.validform.inArray(value, settings.range))){
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    return true;
                },
                'email':function(value, params, attribute){
                    var settings = $.extend({
                        'pattern':/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
                        'message':'{$attribute}格式不正确'
                    }, params);
                    
                    if(settings.pattern.test(value)){
                        return true;
                    }else{
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                },
                'mobile':function(value, params, attribute){
                    var settings = $.extend({
                        'pattern':/^1[0-9]{10}$/,
                        'message':'{$attribute}格式不正确'
                    }, params);
                    
                    if(settings.pattern.test(value)){
                        return true;
                    }else{
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                },
                'url':function(value, params, attribute){
                    var settings = $.extend({
                        'pattern':/^(http|https):\/\/\w+.*$/,
                        'message':'{$attribute}格式不正确'
                    }, params);
                    
                    if(settings.pattern.test(value)){
                        return true;
                    }else{
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                },
                'datetime':function(value, params, attribute){
                    var settings = $.extend({
                        'pattern':/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/,
                        'message':'{$attribute}格式不正确'
                    }, params);
                    
                    if(settings.pattern.test(value)){
                        return true;
                    }else{
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                },
                'chinese':function(value, params, attribute){
                    var settings = $.extend({
                        'pattern':/^[\u4e00-\u9fa5]+$/,
                        'message':'{$attribute}必须是中文'
                    }, params);
                    
                    if(settings.pattern.test(value)){
                        return true;
                    }else{
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                },
                'idcard':function(value, params, attribute){
                    var settings = $.extend({
                        'message':'{$attribute}格式不正确'
                    }, params);
                    
                    var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子;
                    var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值，10代表X;
                    
                    if (value.length == 15){
                        if(isValidityBrithBy15IdCard(value)){
                            return true;
                        }else{
                            return $.validform._renderMsg(settings.message, {
                                'attribute':attribute ? attribute : '参数'
                            });
                        }
                    }else if (value.length == 18){
                        var a_idCard = value.split('');// 得到身份证数组
                        if (isValidityBrithBy18IdCard(value)&&isTrueValidateCodeBy18IdCard(a_idCard)) {
                            return true;
                        }
                        return $.validform._renderMsg(settings.message, {
                            'attribute':attribute ? attribute : '参数'
                        });
                    }
                    return $.validform._renderMsg(settings.message, {
                        'attribute':attribute ? attribute : '参数'
                    });
                    
                    function isTrueValidateCodeBy18IdCard(a_idCard){
                        var sum = 0; // 声明加权求和变量
                        if (a_idCard[17].toLowerCase() == 'x') {
                            a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
                        }
                        for ( var i = 0; i < 17; i++) {
                            sum += Wi[i] * a_idCard[i];// 加权求和
                        }
                        var valCodePosition = sum % 11;// 得到验证码所位置
                        return a_idCard[17] == ValideCode[valCodePosition];
                        
                    }
                    
                    function isValidityBrithBy18IdCard(idCard18){
                        var year = idCard18.substring(6,10);
                        var month = idCard18.substring(10,12);
                        var day = idCard18.substring(12,14);
                        var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                        // 这里用getFullYear()获取年份，避免千年虫问题
                        return !(temp_date.getFullYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day));
                        
                    }
                    
                    function isValidityBrithBy15IdCard(idCard15){
                        var year =  idCard15.substring(6,8);
                        var month = idCard15.substring(8,10);
                        var day = idCard15.substring(10,12);
                        var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                        // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
                        return !(temp_date.getYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day));
                        
                    }
                }
            },
            '_renderMsg':function(msg, params){
                if(typeof(params) == 'object'){
                    for(var k in params){
                        if(typeof(params[k]) == 'number' || typeof(params[k]) == 'string'){
                            msg = msg.replace(eval('/\\{\\$'+k+'}/g'), params[k]);
                        }
                    }
                }
                return msg;
            },
            /**
             * 相对于jquery的inArray只能作用于数组，且类型必须匹配
             * 这个inArray可以作用于对象，且可以选择是否强制类型匹配
             */
            'inArray':function(needle, haystack, argStrict){
                var key = '',
                strict = !! argStrict;
            
                if(strict){
                    for(key in haystack){
                        if(haystack[key] === needle){
                            return true;
                        }
                    }
                }else{
                    for(key in haystack) {
                        if(haystack[key] == needle) {
                            return true;
                        }
                    }
                }
                return false;
            },
            /**
             * 获取表单下所有同名元素
             */
            'getElementsByName':function(obj){
                if(obj.is(':radio')){
                    return $(obj[0].form).find(':radio[name="'+obj.attr('name')+'"]');
                }else if(obj.is(':checkbox')){
                    return $(obj[0].form).find(':checkbox[name="'+obj.attr('name')+'"]');
                }else{
                    return obj;
                }
            }
        }
    });
    $.fn.validform = function(params, rules, labels){
        var validform = function(forms, rules, labels, params){
            validform.prototype.settings = $.extend({
                'showAllErrors':true,//若为false，则一碰到验证不通过的就停止检测后面的元素，只显示该元素的错误信息
                'ignoreHidden':true,//可选项 true|false 默认为true，当为true时对:hidden的表单元素将不做验证;
                'onError':function(obj, msg, rule){},//单元素验证失败时触发
                'onSuccess':function(obj){},//单元素验证成功时触发
                'beforeCheck':function(form){},//全局验证前执行
                'beforeSubmit':function(form){},//表单提交前执行，若返回false，则表单不会被提交
                'onAjaxStart':function(obj){},//ajax验证发送前执行
                'onAjaxEnd':function(obj, resp){},//ajax验证完成时执行
                'ajaxSubmit':false,//ajax提交表单
                'afterAjaxSubmit':function(resp, form){}//如果是ajax提交表单，表单提交后执行此回调函数
            }, params);
            
            validform.prototype.rules = rules ? rules : [];
            validform.prototype.labels = labels ? labels : [];
            
            var _this = this;
            
            forms.each(function(){
                var $form = $(this);
                if($form.data('status')){
                    //防止重复绑定验证
                    return true;
                }
                $form.on('blur', 'select,input,textarea', function(){
                    if($(this).is(':radio') || $(this).is(':checkbox')){
                        //如果是复选框或者单选框，则每次失去焦点必然伴随着值改变，重新验证
                        _this.check($(this));
                    }else{
                        if(_this.getValue($(this)) == '' || $(this).data('validate_status') == 'error'){
                            //为空或者之前验证状态为错误的情况下，每次都执行验证
                            _this.check($(this));
                            $(this).data('validate_last_value', $(this).val());
                        }else if(!$(this).data('validate_last_value') || $(this).data('validate_last_value') != $(this).val()){
                            //如果值有改变或未记录之前的用户输入，则进行验证
                            _this.check($(this));
                            $(this).data('validate_last_value', $(this).val());
                        }
                    }
                }).on('change', 'select,input,textarea', function(){
                    if($form.data('status') == 'checking'){
                        //若是在最后验证ajax的时候，用户又改了某些输入框的值，则不能提交表单
                        //由于ajax验证一般很快，不太会出现这种情况，就不做精细处理了
                        $form.data('status', 'normal');
                    }
                }).on('submit', function(){
                    var status = $form.data('status');
                    if(status == 'normal'){
                        _this.settings.beforeCheck($form);
                        var result = _this.checkAll($form);
                        if(result === false){
                            return false;
                        }else if(result === 'ajax'){
                            $form.data('status', 'checking');
                            return false;
                        }else{
                            $form.data('status', 'checked');
                        }
                    }else if(status == 'checking'){
                        //有ajax验证正在执行
                        return false;
                    }
                    
                    if(_this.settings.beforeSubmit($form) === false){
                        return false;
                    }
                    
                    if(_this.settings.ajaxSubmit){
                        var action = $form.attr('action'),
                            method = $form.attr('method');
                        if(!action){
                            action = window.location.href;
                        }
                        if(!method){
                            method = 'post';
                        }
                        $.ajax({
                            'type': method,
                            'url': action,
                            'data': $form.serialize(),
                            'dataType': 'json',
                            'cache': false,
                            'success': function(resp){
                                _this.settings.afterAjaxSubmit(resp, $form);
                                $form.data('status', 'normal');
                            }
                        });
                        return false;
                    }
                    $form.data('status', 'submitted');
                })
                .data('status', 'normal')
                .data('ajaxQueue', []);
            });
        };
        
        validform.prototype = {
            /**
             * 传入jquery对象
             * 验证一个元素
             *     返回null：没有设置name属性直接跳过
             *     返回true：验证成功
             *     返回"ajax"：本地验证通过，正在进行ajax验证
             *     返回非ajax字符串：错误信息
             */
            'check':function(obj){
                if(this.settings.ignoreHidden && obj.is(':hidden')){
                    return true;//忽略隐藏元素
                }
                var eleName = obj.attr('name');
                if(!eleName)return null;//没有name的元素不做验证
                var eleNameMatch = eleName.match(/.+\[(\w*)]$/);
                if(eleNameMatch !== null && typeof(eleNameMatch[1]) != 'undefined'){
                    //若存在方括号，去掉最后一个方括号及其内容。
                    //此逻辑用于files[], files[100]这样的情况
                    //不支持如db[host]这样的多维数组
                    eleName = eleName.replace(new RegExp('\\['+eleNameMatch[1]+']$'), '');
                }
                var attrLabel = obj.attr('data-label'),
                    eleLabel = attrLabel ? attrLabel : (validform.prototype.labels[eleName] ? validform.prototype.labels[eleName] : eleName),
                    value = this.getValue(obj),
                    isEmpty = !!((typeof(value) == 'object' && $.isEmptyObject(value)) || (typeof(value) == 'string' && value == '')),
                    msg = true,
                    rule = validform.prototype.rules[eleName] ? validform.prototype.rules[eleName] : {},
                    attrRequired = obj.attr('data-required'),
                    attrRule = obj.attr('data-rule'),
                    attrParams = obj.attr('data-params'),
                    attrAjax = obj.attr('data-ajax');
                if(attrParams){
                    eval('attrParams = '+attrParams);
                }else{
                    attrParams = {};
                }
                
                if(typeof(attrRequired) != 'undefined'){
                    rule.required = true;
                    rule.requiredMsg = attrRequired;
                }
                if(attrAjax){
                    rule.ajax = attrAjax;
                }
                if(attrRule){
                    if(rule.validators){
                        rule.validators.push({'name':attrRule,'params':attrParams});
                    }else{
                        rule.validators = [{'name':attrRule,'params':attrParams}];
                    }
                }
                
                if(rule.required){
                    //先进行非空验证
                    msg = $.validform.validators.required(value, (function(message){
                        if(typeof(message) != 'undefined' && message != 'required'){
                            return {'message':message}
                        }else{
                            return {};
                        }
                    })(rule.requiredMsg), eleLabel);
                }
                
                if(msg === true){
                    if(!isEmpty){
                        //非空的情况下执行常规验证器
                        msg = this._validate(obj, value, eleLabel, rule.validators);
                    }
                }else{
                    obj.data('validate_status', 'error');
                    this.settings.onError(obj, msg, 'required');
                    return msg;
                }
                
                if(msg === true){
                    if(rule.ajax && !isEmpty){
                        //ajax验证
                        this.ajax(this.getValue(obj), rule.ajax, obj);
                        return 'ajax';
                    }else{
                        obj.data('validate_status', 'success');
                        this.settings.onSuccess(obj);
                    }
                }
                return msg;
            },
            '_validate':function(obj, value, label, validators){
                //常规验证器验证
                var msg = true;
                validatorsLoop:
                for(var k in validators){//常规验证器
                    if($.validform.validators[validators[k].name]){//验证器不存在则直接跳过
                        if(typeof(value) == 'object'){//为空不调用验证器
                            for(var i in value){
                                msg = $.validform.validators[validators[k].name](value[i], validators[k].params, label);
                                if(msg !== true){
                                    obj.data('validate_status', 'error');
                                    this.settings.onError(obj, msg, validators[k].name);
                                    break validatorsLoop;
                                }
                            }
                        }else if(typeof(value) == 'string'){
                            msg = $.validform.validators[validators[k].name](value, validators[k].params, label);
                            if(msg !== true){
                                obj.data('validate_status', 'error');
                                this.settings.onError(obj, msg, validators[k].name);
                                break validatorsLoop;
                            }
                        }
                    }
                }
                return msg;
            },
            'checkAll':function(obj){
                //传入表单对象
                var flag = true,
                    ajax = false,
                    _this = this;
                
                obj.find('input,textarea,select').each(function(){
                    var result = _this.check($(this));
                    if(result == 'ajax'){
                        ajax = true;
                    }else if(result !== true && result !== null){
                        //滚动到第一个错误位置
                        if(flag){
                            var oo = parseInt($(this).get(0).getBoundingClientRect().top);//元素相对于当前窗口的偏移
                            var sh = document.documentElement.clientHeight;//屏幕高度
                            if(oo < 0){
                                //元素在当前屏幕上面
                                $('html,body').animate({
                                    'scrollTop':$(this).offset().top - 60
                                }, 300);
                            }else if(oo > sh){
                                //元素在当前屏幕下面
                                $('html,body').animate({
                                    'scrollTop':$(this).offset().top - 60
                                }, 300);
                            }else{
                                //元素在可见区域
                            }
                        }
                        flag = false;
                        if(!_this.settings.showAllErrors){
                            return false;
                        }
                    }
                });
                
                if(flag && ajax){
                    return 'ajax';
                }else{
                    return flag;
                }
            },
            'ajax':function(value, url, obj){
                //ajax验证
                var name = obj.attr('name'),
                    attrAjaxParamName = obj.attr('data-ajax-param-name'),
                    form = $(obj[0].form),
                    ajaxQueue = form.data('ajaxQueue'),
                    ajaxParamName = attrAjaxParamName ? attrAjaxParamName : name,
                    data = {};
                data[ajaxParamName] = value;
                
                if(ajaxQueue[name]){
                    ajaxQueue[name].abort();
                    delete ajaxQueue[name];
                }
                this.settings.onAjaxStart(obj);
                ajaxQueue[name] = $.ajax({
                    'type': 'POST',
                    'url': url,
                    'data': data,
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        var ajaxQueue = form.data('ajaxQueue');
                        delete ajaxQueue[name];
                        form.data('ajaxQueue', ajaxQueue);
                        validform.prototype.settings.onAjaxEnd(obj, resp);
                        if(resp.status){
                            obj.data('validate_status', 'success');
                            validform.prototype.settings.onSuccess(obj);
                            if(form.data('status') == 'checking' && $.isEmptyObject(ajaxQueue)){
                                form.data('status', 'checked');
                                $(obj[0].form).submit();//等待提交且所有ajax都已经验证完成，则提交表单
                            }
                        }else{
                            form.data('status', 'normal');
                            obj.data('validate_status', 'error');
                            validform.prototype.settings.onError(obj, resp.message ? resp.message : '未知的验证错误', 'ajax');
                        }
                    }
                });
                form.data('ajaxQueue', ajaxQueue);
            },
            'getValue':function(obj){
                //获取一个元素的值
                var value;
                if(obj.is(':radio')){
                    value = $(obj[0].form).find(':radio[name="'+obj.attr('name')+'"]:checked').val();
                    return value ? value : '';
                }else if(obj.is(':checkbox')){
                    value = [];
                    $(obj[0].form).find(':checkbox[name="'+obj.attr('name')+'"]:checked').each(function(){ 
                        value.push($(this).val()); 
                    });
                    return value;
                }else{
                    value = obj.val();
                    return value ? value : '';
                }
            },
            /**
             * 添加一条规则，格式如下
             *     demo.addRule('username', {'required':true, 'validators':[{
             *        'name':'string',
             *        'params':{'min':2}
             *    }]});
             */
            'addRule':function(name, rule){
                if(this.rules[name]){
                    if(rule.required){
                        this.rules[name].required = rule.required;
                    }
                    if(rule.ajax){
                        this.rules[name].ajax = rule.ajax;
                    }
                    if(rule.validators){
                        if(!this.rules[name].validators){
                            this.rules[name].validators = [];
                        }
                        for(var k in rule.validators){
                            this.rules[name].validators.push(rule.validators[k]);
                        }
                    }
                }else{
                    this.rules[name] = rule;
                }
            },
            'getRules':function(){
                return this.rules;
            },
            'addLabels':function(labels){
                for(var k in labels){
                    this.labels[k] = labels[k];
                }
            },
            'getLabels':function(){
                return this.labels;
            },
            'addValidator':function(name, validator){
                $.validform.validators[name] = validator;
            },
            'setAjax':function(name, ajax){
                this.rules[name].ajax = ajax;
            }
        };
        
        return new validform(this, rules, labels, params);
    };
    
})(jQuery);