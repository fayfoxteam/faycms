<div class="box">
    <div class="box-title">
        <h4>总分</h4>
    </div>
    <div class="box-content">
        <span id="total-score"><?php
            if($score = \F::form()->getData('score')){
                echo $score;
            }else{
                echo '0.00';
            }
        ?></span>
    </div>
</div>