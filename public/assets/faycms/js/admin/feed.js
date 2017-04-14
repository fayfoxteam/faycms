var feed = {
    'boxes':[],
    'roleCats':null,
    'files':function(){
        //附件
        system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
            uploader.files({
                'cat': 'feed',
                'image_only': true
            });
        });
    },
    'events':function(){
        $('#box-operation').on('click', '#edit-status-link', function(){
            $('#edit-status-container').show();
        }).on('click', '#set-status-editing', function(){
            var status = $('#edit-status-selector').val();
            var status_text = $('#edit-status-selector').find('[value="'+status+'"]').text();
            $('input[name="status"]').val(status);
            $('#crt-status').text(status_text);
            $('#edit-status-container').hide();
        }).on('click', '#cancel-status-editing', function(){
            $('#edit-status-container').hide();
        });
    },
    'autosize':function(){
        if(!$('textarea.autosize').length && system.inArray('files', feed.boxes)){
            //页面没有autosize输入框，且附件box存在，则引入autosize输入框插件
            system.getScript(system.assets('js/autosize.min.js'));
        }
    },
    'init':function(){
        if(system.inArray('files', feed.boxes)){
            this.files();
        }
        if(system.inArray('tags', feed.boxes)){
            system.getScript(system.assets('faycms/js/fayfox.textext.js'), function(){
                $('#tags').ftextext({
                    'url':system.url('cms/admin/tag/search'),
                    'width':'100%'
                });
            });
        }
        if(this.roleCats !== null){
            this.setRoleCats();
        }
        this.events();
        this.autosize();
    }
};