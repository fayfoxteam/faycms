<div class="box" id="box-sample-response" data-name="sample_response">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>响应示例</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->textarea('sample_response', array(
            'class'=>'form-control h90 autosize',
            'id'=>'sample-response',
        ))?>
        <div class="cf mt5">
            <?php echo \fay\helpers\HtmlHelper::link('格式化JSON', '#sample-response', array(
                'class'=>'btn btn-grey format-json',
            ))?>
        </div>
    </div>
</div>