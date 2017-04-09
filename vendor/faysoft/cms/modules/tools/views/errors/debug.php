<?php
use fay\services\file\FileService;
use fay\helpers\HtmlHelper;
use fay\core\Uri;

$_backtrace = $exception->getTrace();

//抛出异常的位置
array_unshift($_backtrace, array(
    'file'=>$exception->getFile(),
    'line'=>$exception->getLine(),
    'function'=>'throw',
    'class'=>'',
    'type'=>'',
    'args'=>array(),
));

if(method_exists($exception, 'getLevel')){
    $level = $exception->getLevel();
}else{
    $level = 'Error';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $level?></title>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prettify.js')?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/debug.css')?>" />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
</head>
<body>
<div class="header">
    <h1><?php echo $level, ' - ', $exception->getMessage()?></h1>
    <?php if(method_exists($exception, 'getDescription')){
        $description = $exception->getDescription();
        if($description){
            echo '<p>', $description, '</p>';
        }
    }?>
    <i class="icon"></i>
</div>
<div class="backtrace">
    <div id="backtrace-container">
    <?php
        $code_count = 0;
        foreach($_backtrace as $k => $b){
            $code = '';
            if(isset($b['file']) && $source = FileService::getFileLine($b['file'], $b['line'], 10)){
                $code = HtmlHelper::tag('pre', array(
                    'class'=>'prettyprint linenums:'.($b['line'] - 10 < 1 ? 1 : $b['line'] - 10),
                    'data-line'=>$b['line'],
                    'wrapper'=>array(
                        'tag'=>'div',
                        'class'=>'code-wrap',
                        'style'=>++$code_count == 1 ? 'display:block' : false,
                    ),
                ), HtmlHelper::encode(str_replace("\t", '    ', $source)));
            }
    ?>
        <div class="<?php if($code_count == 1)echo 'act'?> <?php if($code){echo 'with-code';}else{echo 'no-code';}?>">
            <div class="element-wrap">
                <p class="function"><span class="index"><?php echo $k+1?>.</span><?php
                    if(isset($b['class'])){
                        echo "{$b['class']}{$b['type']}{$b['function']}()";
                    }else{
                        echo "{$b['function']}()";
                    }
                    if($code){
                        echo '<i class="icon-file"></i>';
                    }
                ?></p>
                <p class="file"><?php if(isset($b['file'])){
                    echo $b['file'], ':(', $b['line'], ')';
                }?></p>
            </div>
            <?php echo $code?>
        </div>
    <?php }?>
    </div>
</div>
<div class="system-data">
    <h3>Config</h3>
    <table class="data-table">
        <tr>
            <th>base_url</th>
            <td><?php echo F::config()->get('base_url')?></td>
        </tr>
        <tr>
            <th>assets_url</th>
            <td><?php echo F::config()->get('assets_url')?></td>
        </tr>
        <tr>
            <th>app_assets_url</th>
            <td><?php echo F::config()->get('app_assets_url')?></td>
        </tr>
        <tr>
            <th>url_suffix</th>
            <td><?php echo F::config()->get('url_suffix')?></td>
        </tr>
        <tr>
            <th>default_router</th>
            <td><?php echo implode('/', F::config()->get('default_router'))?></td>
        </tr>
        <tr>
            <th>db.host</th>
            <td><?php echo F::config()->get('db.host')?></td>
        </tr>
        <tr>
            <th>db.port</th>
            <td><?php echo F::config()->get('db.port')?></td>
        </tr>
        <tr>
            <th>db.dbname</th>
            <td><?php echo F::config()->get('db.dbname')?></td>
        </tr>
    </table>
    <h3>System Data</h3>
    <table class="data-table">
        <tr>
            <th>Error File</th>
            <td><?php echo __FILE__?></td>
        </tr>
        <tr>
            <th>APPLICATION</th>
            <td><?php echo APPLICATION?></td>
        </tr>
        <tr>
            <th>Router</th>
            <td><?php echo Uri::getInstance()->router?></td>
        </tr>
        <tr>
            <th>BASEPATH</th>
            <td><?php echo BASEPATH?></td>
        </tr>
        <tr>
            <th>SYSTEM_PATH</th>
            <td><?php echo SYSTEM_PATH?></td>
        </tr>
        <tr>
            <th>PHP_VERSION</th>
            <td><?php echo PHP_VERSION?></td>
        </tr>
    </table>
    <h3>SERVER</h3>
    <table class="data-table">
    <?php foreach($_SERVER as $k => $v){?>
        <tr>
            <th><?php echo $k?></th>
            <td><?php echo $v?></td>
        </tr>
    <?php }?>
    </table>
    <h3>Cookies</h3>
    <table class="data-table">
    <?php foreach($_COOKIE as $k => $v){?>
        <tr>
            <th><?php echo $k?></th>
            <td><?php print_r($v)?></td>
        </tr>
    <?php }?>
    </table>
</div>
<script>
$(function(){
    prettyPrint();

    function highlightCurrentLine(){
        $('.prettyprinted').each(function(){
            var firstLine = $(this).find('li:first').attr('value');
            var currentLine = $(this).attr('data-line');
            var offset = parseInt(currentLine) - parseInt(firstLine);
            $(this).find('li:eq('+offset+')').addClass('crt');
        });
    }
    highlightCurrentLine();
    
    $('.backtrace').on('click', '.with-code .element-wrap', function(){
        var $parent = $(this).parent();
        if($parent.hasClass('act')){
            $parent.removeClass('act').find('.code-wrap').slideUp();
            return false;
        }
        //$('.backtrace').find('.code-wrap').slideUp();
        //$('#backtrace-container > div').removeClass('act');
        $parent.addClass('act').find('.code-wrap').slideDown();
    });
})
</script>
</body>
</html>