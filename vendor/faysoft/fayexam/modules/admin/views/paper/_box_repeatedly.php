<div class="box">
    <div class="box-title">
        <h4>是否允许多次参考</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputRadio('repeatedly', 1, array('label'=>'是'), true)?>
        <?php echo F::form()->inputRadio('repeatedly', 0, array('label'=>'否'))?>
    </div>
</div>