<?php
use fay\helpers\RequestHelper;
?>
<form method="post" id="form">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-content">
                    <p><strong>Current IP</strong>: <em><?php echo F::app()->ip, ' - ', $iplocation->getCountryAndArea(F::app()->ip)?></em></p>
                    <p><strong>$_SERVER['HTTP_CLIENT_IP']</strong>: <em><?php echo isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : 'NULL'?></em></p>
                    <p><strong>$_SERVER['HTTP_X_FORWARDED_FOR']</strong>: <em><?php echo isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'NULL'?></em></p>
                    <p><strong>$_SERVER['REMOTE_ADDR']</strong>: <em><?php echo isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'NULL'?></em></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="box">
                <div class="box-title"><h3>int2ip</h3></div>
                <div class="box-content">
                    <?php echo F::form()->textarea('ip_ints', array(
                        'class'=>'form-control h200 autosize',
                    ));?>
                    <a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="box">
                <div class="box-title"><h3>Result</h3></div>
                <div class="box-content">
                    <div style="min-height:239px"><?php if(F::app()->input->post('ip_ints')){
                        $ip_ints = explode("\r\n", F::app()->input->post('ip_ints'));
                        foreach($ip_ints as $i){
                            echo $ip = long2ip(intval($i)), ' - ', $iplocation->getCountryAndArea($ip), '<br>';
                        }
                    }?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="box">
                <div class="box-title"><h3>ip2int</h3></div>
                <div class="box-content">
                    <?php echo F::form()->textarea('ips', array(
                        'class'=>'form-control h200 autosize',
                    ));?>
                    <a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
                    <span class="fc-grey">It will alway return int on 32-bit platforms</span>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="box">
                <div class="box-title"><h3>Result</h3></div>
                <div class="box-content">
                    <div style="min-height:239px"><?php if(F::app()->input->post('ips')){
                        $ips = explode("\r\n", F::app()->input->post('ips'));
                        foreach($ips as $i){
                            echo RequestHelper::ip2int($i), ' - ', $iplocation->getCountryAndArea($i), '<br>';
                        }
                    }?></div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
$("[name='ip_ints'],[name='ips']").keydown(function(event){
    if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
        $("#form").submit();
        return false;
    }
});
</script>