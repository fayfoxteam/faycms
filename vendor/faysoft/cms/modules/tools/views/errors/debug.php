<?php
use fay\core\ErrorHandler;
use fay\core\Uri;

/**
 * @var $exception Exception
 */
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo ErrorHandler::getErrorLevel($exception->getCode())?></title>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/prettify.js')?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/debug.css')?>" />
<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
</head>
<body>
<?php echo $this->renderPartial('errors/_exception', array(
    'exception'=>$exception,
))?>
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
            <th>FAYSOFT_PATH</th>
            <td><?php echo FAYSOFT_PATH?></td>
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