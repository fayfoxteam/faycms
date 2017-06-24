<?php
use cms\services\OptionService;
use fay\helpers\HtmlHelper;

/**
 * @var $subtitle string
 * @var $content string
 * @var $this \fay\core\View
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />

<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/style-responsive.css')?>" />
<?php echo $this->getCss()?>

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-3.2.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/fayfox.block.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/common.min.js')?>"></script>
<script>
    system.base_url = '<?php echo $this->url()?>';
    system.assets_url = '<?php echo \F::config()->get('assets_url')?>';
    system.user_id = <?php echo \F::app()->current_user?>;
    common.max_upload_file_size = '<?php echo \F::config()->get('upload.max_size')?>b';
</script>
<title><?php echo $subtitle?> | <?php echo OptionService::get('site:sitename')?>后台</title>
</head>
<body id="faycms" class="mini">
<div class="wrapper">
    <div class="page-title">
        <div class="title-env">
            <h1 class="title"><?php
                echo isset($subtitle) ? HtmlHelper::encode($subtitle) : '无标题';
            ?></h1>
        </div>
        <div class="operate-env">
            <div class="screen-meta-links"><?php
                //帮助面板
                if(isset($_help_panel)){
                    echo HtmlHelper::link('', 'javascript:', array(
                        'class'=>'fa fa-question-circle fa-2x faycms-help-link',
                        'title'=>'帮助',
                        'data-fancybox'=>null,
                        'data-src'=>'#faycms-help-content',
                        'data-caption'=>'',
                    ));
                    echo HtmlHelper::tag('div', array(
                        'id'=>'faycms-help-content',
                        'class'=>'dialog-content',
                        'wrapper'=>array(
                            'tag'=>'div',
                            'class'=>'dialog hide',
                        ),
                        'prepend'=>'<h4>帮助</h4>',
                    ), $this->renderPartial($_help_panel, $this->getViewData(), -1, true));
                }
                //帮助文本，用于插件等不方便直接利用view文件构建帮助弹出的场景
                if(isset($_help_content)){
                    echo HtmlHelper::link('', 'javascript:', array(
                        'class'=>'fa fa-question-circle fa-2x faycms-help-link',
                        'title'=>'帮助',
                        'data-fancybox'=>null,
                        'data-src'=>'#faycms-help-content',
                        'data-caption'=>'',
                    ));
                    echo HtmlHelper::tag('div', array(
                        'id'=>'faycms-help-content',
                        'class'=>'dialog-content',
                        'wrapper'=>array(
                            'tag'=>'div',
                            'class'=>'dialog hide',
                        ),
                        'prepend'=>'<h4>帮助</h4>',
                    ), $_help_content);
                }
                //页面设置
                if(isset($_setting_panel)){
                    echo HtmlHelper::link('', 'javascript:', array(
                        'class'=>'fa fa-cog fa-2x faycms-setting-link',
                        'title'=>'设置',
                        'data-fancybox'=>null,
                        'data-src'=>'#faycms-setting-content',
                        'data-caption'=>'',
                    ));
                    echo HtmlHelper::tag('div', array(
                        'id'=>'faycms-setting-content',
                        'class'=>'dialog-content',
                        'wrapper'=>array(
                            'tag'=>'div',
                            'class'=>'dialog hide',
                        ),
                        'prepend'=>'<h4>设置</h4>',
                    ), $this->renderPartial($_setting_panel, $this->getViewData(), -1, true));
                }
                echo HtmlHelper::link('', 'javascript:parent.$.fancybox.close()', array(
                    'class'=>'fa fa-close fa-2x',
                    'title'=>'关闭',
                ));
                ?></div>
        </div>
    </div>
    <?php echo $content?>
</div>
<script>
$(function(){
    <?php
    $forms = F::forms();
    foreach($forms as $k=>$f){?>
    common.validformParams.forms['<?php echo $k?>'] = {
        'rules':<?php echo json_encode($f->getJsRules())?>,
        'labels':<?php echo json_encode($f->getLabels())?>,
        'model':'<?php echo $f->getJsModel()?>',
        'scene':'<?php echo $f->getScene()?>'
    };
    <?php }?>
    common.init();
    
    $('.fa-close').hover(
        function(){
            $(this).addClass('fa-rotate-270');
        },
        function(){
            $(this).removeClass('fa-rotate-270');
        }
    );
});
</script>
<img src="<?php echo $this->assets('images/throbber.gif')?>" class="hide" />
<img src="<?php echo $this->assets('images/ajax-loading.gif')?>" class="hide" />
<img src="<?php echo $this->assets('images/loading.gif')?>" class="hide" />
</body>
</html>