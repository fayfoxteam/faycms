<div class="box">
    <div class="box-title">
        <h4>分值</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputText('score', array(
            'class'=>'form-control',
        ), '10.00')?>
        <p class="fc-grey mt5">组卷的时候可以重新设定这个值</p>
    </div>
</div>