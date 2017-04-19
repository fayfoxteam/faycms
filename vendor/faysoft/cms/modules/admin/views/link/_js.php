<script>
var link = {
    'uploadLogo':function(){
        system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
            uploader.image({
                'cat': 'link',
                'browse_button': 'upload-logo-link',
                'container': 'upload-logo-container',
                'input_name': 'logo',
                'preview_container': '#upload-logo-preview',
                'remove_link_text': '移除Logo',
                'preview_image_params': {
                    't': 1
                }
            });
        });
    },
    'init':function(){
        this.uploadLogo();
    }
};
$(function(){
    link.init();
});
</script>