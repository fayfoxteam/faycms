/**
 * 将一些后台上传场景抽离出来。例如：单图上传，组图上传。
 * 这不是一个独立的插件，它依赖于system.js和common.js
 */
var uploader = {
    /**
     * 上传单张图片
     * 可选参数：
     * options.max_file_size: 文件大小限制。默认为2
     * options.cat: 上传文件所属分类。默认为other
     * options.preview_image_params: 预览图参数。默认裁剪为宽度为254，高度等比缩放的图片
     * options.field: 图片字段名（相当于是唯一标识符）。默认为thumbnail
     * options.remove_text: 移除图片文案。默认为：移除缩略图
     * options.scene: 场景，当一个页面中包含多个表单，可能会出现同一个字段多次出现造成id重复的情况。默认为空
     */
    'image': function(options){
        options = options || {};
        var settings = {
            'max_file_size': common.max_upload_file_size,
            'cat': 'other',
            'preview_image_params': {
                't': 4,
                'dw': 254
            },
            'field': 'thumbnail',
            'remove_text': '移除缩略图',
            'scene': ''
        };
        settings = $.extend(settings, options);

        var clean_field = settings.field.replace(/[\[\]:]/g, '');
        var $container = $('#upload-' + clean_field + '-preview-container' + settings.scene);
        system.getScript(system.assets('js/plupload.full.js'), function(){
            //设置缩略图
            var uploader = new plupload.Uploader({
                'runtimes': 'html5,html4,flash,gears,silverlight',
                'multi_selection': false,
                'flash_swf_url': system.url()+'flash/plupload.flash.swf',
                'silverlight_xap_url': system.url()+'js/plupload.silverlight.xap',
                'filters': [
                    {title: 'Image files', extensions: 'jpg,gif,png,jpeg'}
                ],
                'browse_button': 'select-' + clean_field + settings.scene,
                'container': 'select-' + clean_field + '-container' + settings.scene,
                'max_file_size': settings.max_file_size,
                'url': system.url('cms/admin/file/img-upload', {'cat': settings.cat ? settings.cat : 'other'})
            });
            
            uploader.init();
            uploader.bind('FilesAdded', function(){
                $container.html([
                    '<img src="'+system.assets('images/loading.gif')+'" />',
                    '<p>上传进度：<span class="progress">0</span>%</p>'
                ].join(''));
                uploader.start();
            });
            
            uploader.bind('UploadProgress', function(up, file){
                $container.find('.progress').text(file.percent);
            });
            
            uploader.bind('FileUploaded', function(up, file, response){
                var resp = $.parseJSON(response.response);
                var picParams = settings.preview_image_params;
                picParams['f'] = resp.data.id;
                $container.html([
                    '<input type="hidden" name="', settings.field, '" value="', resp.data.id, '" />',
                    '<a href="', resp.data.url, '" data-fancybox data-type="image" class="mask ib">',
                        '<img src="', system.url('cms/admin/file/pic', picParams), '" />',
                    '</a>',
                    '<br>',
                    '<a href="javascript:;" class="remove-', clean_field, '-link">', settings.remove_text, '</a>'
                ].join(''));

                $container.find('input').change();//触发一下change事件，有的地方需要监听图片变化
            });
            
            uploader.bind('Error', function(up, error){
                if(error.code == -600){
                    common.alert('文件大小不能超过'+(parseInt(uploader.settings.max_file_size) / (1024 * 1024))+'M');
                    return false;
                }else if(error.code == -601){
                    common.alert('非法的文件类型');
                    return false;
                }else{
                    common.alert(error.message);
                }
            });
        });
        
        //移除缩略图事件
        $container.on('click', '.remove-' + clean_field + '-link', function(){
            $container.html('<input type="hidden" name="' + settings.field + '" value="0" />');
        });

        //引入fancybox，不需要调用
        common.loadFancybox();
    },
    /**
     * 上传多个文件，可用于附件，画廊等需求
     * 此函数依赖于common.js中的dragsortList方法提供拖拽及删除效果
     * 可选参数：
     * options.browse_button: 上传按钮id。默认为upload-file-link
     * options.container: 上传控件外层div id。默认为upload-file-container
     * options.max_file_size: 文件大小限制。默认为2
     * options.cat: 上传文件所属分类。默认为other
     * options.input_name: 用于记录文件id的输入框名称（会随着其他内容一起提交给服务端）。默认为files
     * options.image_only: 若为true，则仅允许上传图片。默认为false
     * options.file_info: 文件附加信息。description, title, link, validity可选，默认为description。
     * options.description_name: 用于记录文件描述的文本域名称（会随着其他内容一起提交给服务端）。默认为description
     * options.title_name: 用于记录文件标题的输入框名称（会随着其他内容一起提交给服务端）。默认为titles
     * options.link_name: 用于记录文件链接地址的输入框名称（会随着其他内容一起提交给服务端）。默认为links
     * options.valid_date: 是否显示有效期。默认为false
     */
    'files': function(options){
        options = options || {};
        var settings = {
            'browse_button': 'upload-file-link',
            'container': 'upload-file-container',
            'drop_element': null,
            'max_file_size': common.max_upload_file_size,
            'cat': 'other',
            'input_name': 'files',
            'image_only': false,
            'file_info': ['description'],
            'description_name': 'description',
            'title_name': 'titles',
            'link_name': 'links',
            'starttime_name': 'start_time',
            'endtime_name': 'end_time'
        };
        $.each(options, function(i, n){
            settings[i] = n;
        });
        
        system.getScript(system.assets('js/plupload.full.js'), function(){
            var url, filters;
            if(settings.image_only){
                url = system.url('cms/admin/file/img-upload', {'cat': settings.cat});
                filters = [
                    {title: 'Image files', extensions: 'jpg,gif,png,jpeg'}
                ];
            }else{
                url = system.url('cms/admin/file/upload', {'cat': settings.cat});
                filters = [];
            }
            
            var uploader = new plupload.Uploader({
                'runtimes': 'html5,html4,flash,gears,silverlight',
                'flash_swf_url': system.url()+'flash/plupload.flash.swf',
                'silverlight_xap_url': system.url()+'js/plupload.silverlight.xap',
                'browse_button': settings.browse_button,
                'container': settings.container,
                'drop_element': settings.drop_element,
                'max_file_size': settings.max_file_size,
                'url': url,
                'filters': filters
            });
            
            uploader.init();
            
            uploader.bind('FilesAdded', function(up, files){
                uploader.start();
                var html = '';
                $.each(files, function(i, data){
                    html += [
                        '<div class="dragsort-item" id="file-', data.id, '">',
                            '<a class="dragsort-item-selector"></a>',
                            '<a class="dragsort-rm" href="javascript:;"></a>',
                            '<div class="dragsort-item-container">',
                                '<span class="fl">',
                                    '<img src="', system.assets('images/loading.gif'), '" />',
                                '</span>',
                                '<div class="ml120">',
                                    (function(){
                                        var html = [];
                                        if(system.inArray('description', settings.file_info)){
                                            html.push('<textarea class="form-control file-desc autosize">', data.name, '</textarea>');
                                        }
                                        if(system.inArray('title', settings.file_info)){
                                            html.push('<input type="text" class="file-title mb5 form-control" placeholder="标题" value="', data.name, '" />');
                                        }
                                        if(system.inArray('link', settings.file_info)){
                                            html.push('<input type="text" class="file-link mb5 form-control" placeholder="链接地址" />');
                                        }
                                        if(system.inArray('validity', settings.file_info)){
                                            html.push('<input type="text" class="file-starttime datetimepicker mb5 form-control wp49 fl" placeholder="生效时间" autocomplete="off" />');
                                            html.push('<input type="text" class="file-endtime datetimepicker mb5 form-control wp49 fr" placeholder="过期时间" autocomplete="off" />');
                                        }
                                        return html.join('');
                                    }()),
                                '</div>',
                                '<div class="clear"></div>',
                                '<div class="progress-bar">',
                                    '<span class="progress-bar-percent"></span>',
                                '</div>',
                            '</div>',
                        '</div>'
                    ].join('');
                });
                $('.file-list').prepend(html);
            });
            
            uploader.bind('UploadProgress', function(up, file){
                $('#file-' + file.id + ' .progress-bar-percent').css({'width': file.percent+'%'});
            });
            
            uploader.bind('FileUploaded', function(up, file, response){
                var resp = $.parseJSON(response.response);
                $file = $('#file-'+file.id);
                if(typeof resp.data === 'object' && 'raw_name' in resp.data){
                    autosize($file.find('.file-desc').attr('name', settings.description_name+'['+resp.data.id+']'));
                    $file.find('.file-title').attr('name', settings.title_name+'['+resp.data.id+']');
                    $file.find('.file-link').attr('name', settings.link_name+'['+resp.data.id+']');
                    $file.find('.file-starttime').attr('name', settings.starttime_name+'['+resp.data.id+']');
                    $file.find('.file-endtime').attr('name', settings.endtime_name+'['+resp.data.id+']');
                    
                    $file.append('<input type="hidden" name="'+settings.input_name+'[]" value="'+resp.data.id+'" />');
                    $file.prepend('<a class="file-rm" href="javascript:;"></a>');
                    
                    if(resp.data.is_image){
                        //是图片，用fancybox弹窗
                        $file.find('.fl').html([
                            '<a href="', resp.data.url, '" data-fancybox="images" data-caption="', resp.data.client_name, '" class="mask ib">',
                                '<img src="', resp.data.thumbnail, '" />',
                            '</a>'
                        ].join(''));
                    }else{
                        //非图片，直接新窗口打开
                        $file.find('.fl').html([
                            '<a href="', resp.data.url, '" target="_blank">',
                                '<img src="', resp.data.thumbnail, '" />',
                            '</a>'
                        ].join(''));
                    }
                    
                    if(system.inArray('validity', settings.file_info)){
                        //如果存在日起选择，则重新绑定日期选择插件
                        common.datepicker();
                    }
                }else{
                    //非json数据，上传出错
                    $file.remove();
                    common.alert(resp.message);
                }
            });
            
            uploader.bind('Error', function(up, error){
                if(error.code == -600){
                    common.alert('文件大小不能超过'+(parseInt(uploader.settings.max_file_size) / (1024 * 1024))+'M');
                    return false;
                }else if(error.code == -601){
                    common.alert('非法的文件类型');
                    return false;
                }else{
                    common.alert(error.message);
                }
            });
        });
        
        //引入fancybox，不需要调用
        common.loadFancybox();
    }
};