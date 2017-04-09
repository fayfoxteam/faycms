<div class="box" id="box-views" data-name="views">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>阅读数</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputText('views', array(
            'class'=>'form-control',
        ), 0)?>
        <div class="fc-grey">设定初始值，后续会按实际PV自动增加。</div>
    </div>
</div>