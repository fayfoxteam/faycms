<?php
/**
 * @var $payment array
 */
?>
<?php echo F::form()->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div id="payment-config-panel"><?php
                list($channel, $type) = explode(':', $payment['code']);
                $setting_file = FAYSOFT_PATH . "faypay/services/methods/{$channel}/views/_setting_{$type}.php";
                if(file_exists($setting_file)){
                    include $setting_file;
                }else{
                    throw new ErrorException('支付方式配置面板文件丢失');
                }
            ?></div>
        </div>
        <div class="postbox-container-1">
            <div class="box">
                <div class="box-title">
                    <h4>操作</h4>
                </div>
                <div class="box-content">
                    <div><?php
                        echo F::form()->submitLink('保存', array(
                            'class'=>'btn',
                        ));
                        ?></div>
                    <div class="misc-pub-section mt6">
                        <strong>是否启用？</strong>
                        <?php
                            echo F::form()->inputRadio('enabled', 1, array('label'=>'是'), true);
                            echo F::form()->inputRadio('enabled', 0, array('label'=>'否'));
                        ?>
                        <p class="fc-grey">停用后不再显示，但会保留设置</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-title">
                    <h4>支付信息</h4>
                </div>
                <div class="box-content">
                    <div class="form-field">
                        <label class="title bold">名称<em class="required">*</em></label>
                        <?php echo F::form()->inputText('name', array(
                            'class'=>'form-control',
                        ))?>
                        <p class="fc-grey mt5">显示给用户看的名称</p>
                    </div>
                    <div class="form-field">
                        <label class="title bold">描述</label>
                        <?php echo F::form()->textarea('description', array(
                            'class'=>'form-control autosize',
                        ))?>
                        <p class="fc-grey mt5">一般显示在支付名称下面，具体看前端实现</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo F::form()->close()?>
<script>
$(function(){
    $('#payment-code').on('change', function(){
        $('body').block();
        $.ajax({
            'type': 'GET',
            'url': system.url('faypay/admin/method/get-setting-panel'),
            'data': {'code': $(this).val()},
            'success': function(resp){
                $('body').unblock();
                $('#payment-config-panel').html(resp);
            }
        });
    });
});
</script>
