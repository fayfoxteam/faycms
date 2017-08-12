<?php
use fay\core\ErrorHandler;
use fay\helpers\HtmlHelper;
use fay\helpers\LocalFileHelper;

/**
 * @var $exception Exception
 */
$_backtrace = $exception->getTrace();
$_backtrace_string = explode("\n", $exception->getTraceAsString());

//抛出异常的位置
array_unshift($_backtrace, array(
    'file'=>$exception->getFile(),
    'line'=>$exception->getLine(),
    'function'=>'throw',
    'class'=>'',
    'type'=>'',
    'args'=>array(),
));
?>
<div class="header">
    <h1><?php echo ErrorHandler::getErrorLevel($exception->getCode()), ' - ', $exception->getMessage()?></h1>
    <?php if(method_exists($exception, 'getDescription')){
        $description = $exception->getDescription();
        if($description){
            echo '<p>', nl2br($description), '</p>';
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
            if(isset($b['file']) && $source = LocalFileHelper::getFileLine($b['file'], $b['line'], 10)){
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
                        if(!$k){
                            $info = array(
                                $exception->getFile() . ' (' . $exception->getLine() . ')',
                                'throw',
                            );
                        }else{
                            $info = explode(': ', $_backtrace_string[$k - 1]);
                        }
                        if(isset($info[1])){
                            echo $info[1];
                        }
                        if($code){
                            echo '<i class="icon-file"></i>';
                        }
                        ?></p>
                    <p class="file"><?php if(isset($b['file'])){
                            echo ltrim($info[0], '# 0123456789');
                        }?></p>
                </div>
                <?php echo $code?>
            </div>
        <?php }?>
    </div>
</div>
<?php if($e = $exception->getPrevious()){
    echo $this->renderPartial('errors/_exception', array(
        'exception'=>$e,
    ));
}?>