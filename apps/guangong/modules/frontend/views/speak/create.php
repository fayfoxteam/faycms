<?php
/**
 * @var $this \fay\core\View
 * @var $access_token string
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php if(!empty($canonical)){?>
        <link rel="canonical" href="<?php echo $canonical?>" />
    <?php }?>
    <title><?php if(!empty($title)){
            echo $title;
        }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo $this->appAssets('js/common.js')?>"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.user_id = '<?php echo \F::app()->current_user?>';
    </script>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->assets('faycms/css/frontend.css')?>" >
    <?php echo $this->getCss()?>
    <style>
        html,body,h1,ul,li,fieldset{padding:0;margin:0;border:0 none;font-size:12px}
        body{background-color:#F6F6F5;}
        a{text-decoration:none}
        .btn{color:#fff;padding:7px 10px;display:inline-block;text-align:center;border-radius:3px;font-size:12px;text-decoration:none;letter-spacing:2px}
        .btn-red{background-color:#E50012}
        .wrapper{padding-top:20px}
        .top-title{font-size:12px;text-align:center}
        .top-title-img{background-color:#E50012;padding:10px 20px;margin:0 auto;margin-top:6px;width:80%;text-align:center;box-sizing:border-box}
        .top-title-img img{width:100%}
        .top-title-3{margin-top:26px;text-align:center}
        .top-title-3 img{width:84%}
        .form{margin-top:10px}
        .form fieldset{text-align:center;width:80%;margin:0 auto 20px}
        .form fieldset input{padding:5px 10px;font-size:12px;}
        .form fieldset #photo-preview{width:86px;height:104px;border:1px solid #888889}
        .form fieldset #upload-photo-link{font-size:12px;margin-top:7px;display:block}
        .form fieldset textarea{width:100%;height:60px;padding:5px}
        .form fieldset label{display:block;text-align:left;margin-bottom:6px;}
        .form fieldset .desc{color:#888889;text-align:center;margin-top:8px}
    </style>
</head>
<body>
<div class="wrapper">
    <h1 class="top-title">关公点兵—关公文化体验旅游主题产品</h1>
    <div class="top-title-img"><img src="<?php echo $this->appAssets('images/speak/c-t1.png')?>"></div>
    <div class="top-title-3"><img src="<?php echo $this->appAssets('images/speak/c-t2.png')?>"></div>
    <div class="form">
        <form id="form" method="post">
            <fieldset><input type="text" name="name" id="name" placeholder="填写您的名字"></fieldset>
            <fieldset>
                <div id="avatar-container">
                    <input type="hidden" name="photo_server_id" id="photo-server-id">
                    <img src="" id="photo-preview">
                </div>
                <a href="javascript:" id="upload-photo-link">点击+上传一张您的帅气英雄照</a>
            </fieldset>
            <fieldset>
                <label>我的代言口号</label>
                <textarea name="words" id="words" placeholder="一句话，由心生，正能量，短有力。（15字内）"></textarea>
            </fieldset>
            <fieldset>
                <a href="javascript:" class="btn btn-red" id="submit-link">立现我的代言海报</a>
                <div class="desc">代言后即履行承诺加入关羽军团</div>
            </fieldset>
        </form>
    </div>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        wx.config(<?php echo $js_sdk_config?>);
        $(function(){
            //文本域自适应
            system.getScript(system.assets('js/autosize.min.js'), function(){
                autosize($('textarea.autosize'));
            });

            $('#form').on('submit', function(){
                if(!$('#name').val()){
                    common.toast('请填写您的名字', 'error');
                    return false;
                }
                if(!$('#words').val()){
                    common.toast('请填写代言口号', 'error');
                    return false;
                }
                if(!$('#photo-server-id').val()){
                    common.toast('请上传照片', 'error');
                    return false;
                }
            });

            //表单提交
            $('#submit-link').on('click', function(){
                $('#form').submit();
            });

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
    </script>
</div>
</body>
</html>