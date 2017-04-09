<?php
/**
 * @var $this \fay\core\View
 * @var $group array
 */
$this->appendCss($this->appAssets('css/group.css'));
?>
<div class="swiper-container groups">
    <div class="swiper-wrapper">
        <div class="swiper-slide" id="group-31">
            <div class="layer brand"><img src="<?php echo $this->appAssets('images/group/brand.png')?>"></div>
            <div class="layer" id="step">
                <span class="number">第三式</span>
                <span class="title">盟誓</span>
            </div>
            <div class="layer guangong"><img src="<?php echo $this->appAssets('images/group/guangong.png')?>"></div>
        </div>
        <div class="swiper-slide" id="group-32">
            <div class="layer brand"><img src="<?php echo $this->appAssets('images/group/brand.png')?>"></div>
            <div class="layer subtitle">
                <span class="title">盟誓</span>
                <span>第三式</span>
            </div>
            <div class="layer left-bottom"><img src="<?php echo $this->appAssets('images/group/lb.png')?>"></div>
            <div class="layer group-name"><h1><?php echo $group['name']?></h1></div>
            <div class="layer form">
            <?php echo F::form()->open()?>
                <fieldset>
                    <label for="vows" id="label-vows">内定誓词</label>
                    <div>
                        <select id="vows" class="form-control"></select>
                        <a href="javascript:;" class="btn-2" id="select-vow">选定</a>
                    </div>
                </fieldset>
                <fieldset>
                    <label for="vow" id="label-vow">自创誓词</label>
                    <textarea name="vow" id="vow" class="form-control"></textarea>
                </fieldset>
                <fieldset>
                    <label for="words" id="label-words">我想对兄弟说</label>
                    <textarea name="words" id="words" class="form-control" placeholder="请留下对兄弟的心愿、祝福或心里话"></textarea>
                    <br class="cb">
                    <p class="description">限200字</p>
                </fieldset>
                <fieldset>
                    <label for="secrecy_period" id="label-secrecy-period">解密期</label>
                    <div>
                        <span class="btn-3">一年后</span>
                    </div>
                </fieldset>
            <?php echo F::form()->close()?>
            </div>
            <div class="layer actions"><?php echo F::form()->submitLink('盟誓', array(
                    'class'=>'btn btn-1',
                ))?></div>
        </div>
    </div>
</div>
<script>
$(function(){
    var step3 = {
        'group_id': <?php echo $group['id']?>,
        /**
         * 获取系统内置誓词
         */
        'getVows': function(){
            $.ajax({
                'type': 'GET',
                'url': system.url('api/vow/list'),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    if(resp.status){
                        $.each(resp.data, function(i, d){
                            $('#vows').append('<option>'+d+'</option>');
                        });
                    }else{
                        common.toast(resp.message, 'error');
                    }
                }
            });
        },
        /**
         * 选择系统誓词
         */
        'selectVow': function(){
            $('#select-vow').on('click', function(){
                $('#vow').val($('#vows').val());
            });
        },
        /**
         * 提交表单
         */
        'submit': function(){
            $('#form-submit').on('click', function(){
                var vow = $('#vow').val();
                var words = $('#words').val();
                if(vow == ''){
                    common.toast('请选择或自创誓词', 'error');
                    return false;
                }
                if(words == ''){
                    common.toast('请填写对兄弟们的祝福', 'error');
                    return false;
                }
                if(vow.length > 100){
                    common.toast('誓词不能超过100个字', 'error');
                    return false;
                }
                if(words.length > 200){
                    common.toast('“对兄弟们说”不能超过200个字', 'error');
                    return false;
                }
                
                $.ajax({
                    'type': 'POST',
                    'url': system.url('api/vow/set-vow'),
                    'data': {
                        'vow': vow,
                        'group_id': step3.group_id
                    },
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        if(resp.status){
                            $.ajax({
                                'type': 'POST',
                                'url': system.url('api/word/set'),
                                'data': {
                                    'words': words,
                                    'group_id': step3.group_id,
                                    'secrecy_period': 365
                                },
                                'dataType': 'json',
                                'cache': false,
                                'success': function(resp){
                                    if(resp.status){
                                        window.location.href = '<?php echo $this->url('group/step4', array(
                                            'group_id'=>$group['id']
                                        ))?>';
                                    }else{
                                        common.toast(resp.message, 'error');
                                    }
                                }
                            });
                        }else{
                            common.toast(resp.message, 'error');
                            return false;
                        }
                    }
                });
                
                return false;
            });
        },
        'init': function(){
            this.getVows();
            this.selectVow();
            this.submit();
        }
    };
    
    step3.init();
})
</script>