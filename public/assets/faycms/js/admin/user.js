var user = {
    'user_id':0,
    'avatar':function(){
        system.getScript(system.assets('js/plupload.full.js'), function() {
            var uploader = new plupload.Uploader({
                runtimes: 'html5,html4,flash,gears,silverlight,browserplus',
                browse_button: 'upload-avatar',
                container: 'avatar-container',
                max_file_size: '2mb',
                url: system.url('cms/admin/file/upload', {'cat': 'avatar'}),
                flash_swf_url: system.url() + 'flash/plupload.flash.swf',
                silverlight_xap_url: system.url() + 'js/plupload.silverlight.xap',
                filters: [
                    {title: 'Image files', extensions: 'jpg,gif,png,jpeg'}
                ]
            });

            uploader.init();
            uploader.bind('FilesAdded', function () {
                $('#avatar-img, #avatar-img-circle').attr('src', system.assets('images/loading.gif'));
                uploader.start();
            });

            uploader.bind('FileUploaded', function (up, file, response) {
                var resp = $.parseJSON(response.response);

                $('#avatar-id').val(resp.data.id);
                $('#avatar-img').attr('src', system.url('cms/admin/file/pic', {
                    'f': resp.data.id,
                    't': 4,
                    'dw': 178,
                    'dh': 178
                }))
                    .parent().attr('href', resp.data.src);
                $('#avatar-img-circle').attr('src', resp.data.thumbnail)
                    .parent().attr('href', resp.data.src);

            });

            uploader.bind('Error', function (up, error) {
                if (error.code == -600) {
                    common.alert('文件大小不能超过' + (parseInt(uploader.settings.max_file_size) / (1024 * 1024)) + 'M');
                    return false;
                } else if (error.code == -601) {
                    common.alert('非法的文件类型');
                    return false;
                } else {
                    common.alert(error.message);
                }
            });
        });
    },
    'prop': function(){
        $(document).on('change', '[name="roles[]"]', function(){
            $('#prop-panel').block();
            var value = [];
            $('.user-roles:checked').each(function(){ 
                value.push($(this).val()); 
            });
            $.ajax({
                'type': 'GET',
                'url': system.url('cms/admin/user/get-prop-panel'),
                'data': {
                    'role_ids[]':value,
                    'user_id':user.user_id ? user.user_id : 0
                },
                'cache': false,
                'success': function(resp){
                    var $propPanel = $('#prop-panel');
                    $propPanel.unblock();
                    $propPanel.find('input,select,textarea').each(function(){
                        $(this).poshytip('hide');
                    });
                    $propPanel.html(resp);
                }
            });
        });
    },
    'init':function(){
        this.avatar();
        this.prop();
    }
};