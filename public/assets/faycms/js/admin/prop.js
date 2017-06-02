var prop = {
    /**
     * 编辑属性名称
     */
    'editLabel': function(){
        $(document).on('click', '.prop-title', function(){
            $(this).hide();
            $(this).next('.prop-title-editor').removeClass('hide').focus();
        }).on('blur', '.prop-title-editor', function(){
            var value = $(this).val();
            if(value == ''){
                value = $(this).attr('data-title');
                $(this).val(value);
            }
            $(this).prev('.prop-title').find('span').text(value);
            
            $(this).addClass('hide');
            $(this).prev('.prop-title').show();
        }).on('keyup', '.prop-title-editor', function(event){
            if(event.keyCode == 13 || event.keyCode == 108) {//回车
                $(this).blur();
            }
        });
    },
    'init': function(){
        this.editLabel();
    }
};