<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<script>
    system.base_url = '<?php echo $this->url()?>';
    system.assets_url = '<?php echo \F::config()->get('assets_url')?>';
</script>
<title>可视化编辑 | <?php echo \cms\services\OptionService::get('site:sitename')?>后台</title>
<style>
*{border:0 none;margin:0;outline:0 none;padding:0}
html,body{overflow:hidden;height:100%}
#customize-iframe{width:100%;height:100%}
</style>
</head>
<body>
<iframe src="<?php echo $this->url('', array(
    '_editing'=>1
))?>" id="customize-iframe" name="customize-iframe"></iframe>
<script src="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assets('js/fancybox-3.0/dist/jquery.fancybox.min.css')?>">
<script>
var customize = {
    'initIframe': function(){
        $('#customize-iframe').on('load', function(){
            $(window.frames['customize-iframe'].document).find('body').append([
                '<style>',
                '.edit-widget-container{position:relative;transition:all 0.2s linear 0s}',
                '.edit-widget-container .edit-widget-link{position:absolute;left:-30px;top:2px;color:#fff;width:30px;height:30px;font-size:18px;z-index:5;background-color:#0085ba;border-radius:50%;text-align:center;padding:3px;border:2px solid #fff;text-shadow:0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;box-shadow:0 2px 1px rgba(46,68,83,.15);box-sizing:border-box}',
                '.edit-widget-container .edit-widget-link svg{fill:#fff;width:20px;height:20px;margin:auto}',
                '</style>'
            ].join(''));
            $(window.frames['customize-iframe'].document).on('click', '.edit-widget-link', function(){
                parent.$.fancybox.open({
                    'src': $(this).attr('data-src'),
                    'type': 'iframe'
                }, {
                    'iframe': {
                        'css': {
                            'max-width': '1000px',
                            'max-height': '88%'
                        },
                        'scrolling': 'yes'
                    }
                });
            });
        });
    },
    /**
     * 刷新一个小工具
     * @var $alias
     */
    'refreshWidget': function(alias){
        $.ajax({
            'type': 'GET',
            'url': system.url('cms/api/widget/load'),
            'data': {'alias': alias},
            'cache': false,
            'success': function(resp){
                $(window.frames['customize-iframe'].document).find('#edit-widget-content-' + alias).replaceWith(resp);
            }
        });
    },
    /**
     * 阻塞a标签跳转，往链接后面加参数后再跳转
     */
    'blockLink': function(){
        $(window.frames['customize-iframe'].document).on('click', 'a', function(){
            var href = $(this).attr('href');
            if(href.indexOf('?') > 0){
                href += '&_editing=1';
            }else{
                href += '?_editing=1';
            }
            $('#customize-iframe').attr('src', href);
            return false;
        });
    },
    'init': function(){
        this.initIframe();
        this.blockLink();
    }
};
$(function(){
    customize.init();
});
</script>
</body>
</html>