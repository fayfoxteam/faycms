<div class="box" id="box-sample" data-name="sample">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>示例值</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->textarea('sample', array(
            'class'=>'form-control h90 autosize',
            'id'=>'model-sample'
        ))?>
        <div class="cf mt5">
            <?php echo \fay\helpers\HtmlHelper::link('格式化JSON', '#model-sample', array(
                'class'=>'btn btn-grey format-json',
            ))?>
        </div>
    </div>
</div>