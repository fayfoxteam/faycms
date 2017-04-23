<?php
/**
 * @var $this \fay\core\View
 * @var $user_extra array
 * @var $states array
 * @var $access_token string
 * @var $js_sdk_config array
 */
?>
<div class="swiper-slide" id="recruit-31">
    <div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/recruit/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/recruit/t3.png')?>"></div>
    <div class="layer description">
        <p>应征规则：</p>
        <p>加入关于军团须提交相关个人信息纳入网络军籍存档，请保证档案信息真实性和严肃性；同时分担个位数小额军费。本系统按要求确保个人信息安全。</p>
    </div>
</div>
<div class="swiper-slide" id="recruit-32">
    <div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
    <?php if($user_extra && $user_extra['military'] >= \cms\services\OptionService::get('guangong:junfei', 1100)){//已经缴纳军费?>
        
    <?php }else if(!empty($user['user']['mobile'])){//已经微信注册，且填写了注册信息，但未缴纳军费?>
        <div class="layer military"><a href="<?php
            echo $this->url('api/payment/military')
            ?>">缴纳军费</a></div>
    <?php }else if($user_extra){//已经微信登录过，但未填写注册信息?>
        <div class="layer register-form">
            <?php echo F::form()->open('api/user/sign-up')?>
            <?php echo F::form()->inputHidden('avatar', array(
                'id'=>'photo-server-id',
            ));?>
            <fieldset class="avatar-container">
                <a href="javascript:" id="upload-photo-link">
                    <img src="<?php echo $user['user']['avatar']['thumbnail']?>" id="photo-preview">
                </a>
            </fieldset>
            <fieldset>
                <label>识别号</label>
                <div class="field-container"><?php echo F::form()->inputText('mobile', array(
                    'class'=>'form-control',
                    'placeholder'=>'手机号为身份识别号',
                ))?></div>
            </fieldset>
            <fieldset>
                <label>姓名</label>
                <div class="field-container"><?php echo F::form()->inputText('realname', array(
                    'class'=>'form-control',
                ))?></div>
            </fieldset>
            <fieldset>
                <label>出生期</label>
                <div class="field-container"><?php echo F::form()->input('birthday', 'date', array(
                    'class'=>'form-control',
                ))?></div>
            </fieldset>
            <fieldset>
                <label>所在地</label>
                <div class="field-container"><?php
                    echo F::form()->select('state', array(''=>'-省-') + $states, array(
                        'class'=>'form-control ib',
                        'id'=>'reg-state',
                    )),
                    F::form()->select('city', array(''=>'-市-'), array(
                        'class'=>'form-control ib',
                        'id'=>'reg-city',
                    ));
                    ?></div>
            </fieldset>
            <fieldset>
                <label>报名期</label>
                <div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText('', date('Y年m月d日'), array(
                        'class'=>'form-control',
                        'readonly'=>'readonly',
                ))?></div>
            </fieldset>
            <fieldset>
                <label>服役期</label>
                <div class="field-container"><?php echo \fay\helpers\HtmlHelper::inputText('', '报名第二天至第365天', array(
                    'class'=>'form-control',
                    'readonly'=>'readonly',
                ))?></div>
            </fieldset>
            <?php echo F::form()->close()?>
        </div>
        <div class="layer submit-container"><?php echo F::form()->submitLink('提交注册', array(
            'class'=>'submit-link',
        ))?></div>
    <?php }else{//还没微信登录过，需要微信授权登录创建用户?>
        <div class="layer to-login">
            <fieldset>
                <?php echo \fay\helpers\HtmlHelper::link(
                    '微信登录',
                    array('api/oauth/weixin')
                )?>
            </fieldset>
        </div>
    <?php }?>
</div>
<?php $this->renderPartial('_js')?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
$(function(){
    $('#reg-state').on('change', function(){
        $.ajax({
            'type': 'GET',
            'url': system.url('cms/api/region/get-next-level'),
            'data': {'id': $(this).val()},
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                if(resp.status){
                    var regCity = $('#reg-city');
                    regCity.html('');
                    $.each(resp.data.regions, function(i, n){
                        regCity.append('<option value="'+n.id+'">'+n.name+'</option>');
                    });
                    regCity.change();
                }else{
                    common.toast(resp.mesage, 'error');
                }
            }
        });
    });
    common.form.afterAjaxSubmit = function(resp){
        if(resp.status){
            //若用户未注册（已经完成微信登录），提交注册信息后，直接跳转到微信支付
            window.location.href = "<?php echo $this->url('api/payment/military')?>";
        }else{
            common.toast(resp.message, 'error');
            common.changeCaptcha($('.captcha'));
        }
    };

    wx.config(<?php echo $js_sdk_config?>);
    $(function(){
        $('#upload-photo-link').on('click', function(){
            wx.chooseImage({
                'count': 1,
                'success': function(res){
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    $('#photo-preview').attr('src', localIds[0].toString());

                    wx.uploadImage({
                        localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
                        isShowProgressTips: 1, // 默认为1，显示进度提示
                        success: function(res){
                            var serverId = res.serverId; // 返回图片的服务器端ID
                            $('#photo-server-id').val(serverId.toString());
                            $('#photo-preview').attr('src', 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=<?php echo $access_token?>&media_id='+serverId.toString());
                        }
                    });
                }
            });
        });
    });
});
</script>