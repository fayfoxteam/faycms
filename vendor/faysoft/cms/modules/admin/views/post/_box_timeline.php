<div class="box" id="box-timeline" data-name="timeline">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>时间轴</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputText('sort', array('class'=>'form-control timepicker'))?>
        <p class="fc-grey mt5">用于排序，默认为创建时间。<br>提示：将时间轴设为将来时间可以在指定时间段内起到置顶的效果</p>
    </div>
</div>