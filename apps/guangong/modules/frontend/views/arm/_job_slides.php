<div class="swiper-slide" id="arm-12">
    <div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
    <div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
    <div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t5.png')?>"></div>
    <div class="layer description">
        <p class="center">有价值有深度的关公文化网络体验之旅</p>
        <p class="center">为实战体验做战争准备</p>
    </div>
</div>
<div class="swiper-slide jobs-slide" id="arm-13">
    <div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
    <div class="layer subtitle">履军职</div>
    <div class="layer job-title"><img src="<?php echo $this->appAssets('images/arm/junzhi-title.png')?>"></div>
    <div class="layer jobs">
        <ul>
            <li class="job-1">
                <a href="javascript:" id="attendance-dialog-link" class="task-link" data-task-id="1"><img src="<?php echo $this->appAssets('images/arm/junzhi-1.png')?>"></a>
            </li>
            <li class="job-2">
                <a href="javascript:" class="show-weixin-share-link"><img src="<?php echo $this->appAssets('images/arm/junzhi-2.png')?>"></a>
            </li>
            <li class="job-3">
                <a href="#post-dialog" id="post-dialog-link" data-id="<?php echo empty($next_post) ? 0 : $next_post?>"><img src="<?php echo $this->appAssets('images/arm/junzhi-3.png')?>"></a>
            </li>
            <li class="job-4">
                <a href="<?php echo $this->url()?>" class=""><img src="<?php echo $this->appAssets('images/arm/junzhi-4.png')?>"></a>
            </li>
        </ul>
    </div>
    <div class="layer description">
        <p>按要求先行完成军职者，不论出身，部分贵贱，封<a href="#jiangjunxian-dialog" class="fancybox-inline">“将军衔”</a>，授<a href="#yunchangjian-dialog" class="fancybox-inline">“云长剑”</a>（关羽军团最高荣誉）。</p>
        <p>千兵有头，万兵有将。百名将军军衔等着授封，记住，只有百名！</p>
    </div>
</div>
<div class="hide">
    <div id="jiangjunxian-dialog" class="dialog">
        <div class="dialog-content">
            <h4>关羽军团最高军职</h4>
            <h5>履职有功者 授封将军衔</h5>
            <img src="<?php echo $this->appAssets('images/arm/jiangjunxian.png')?>">
            <div class="desc">
                <p>
                    <label>权属</label>
                    <span>建安24年，刘备进位汉中王，拜关羽为前将军、假节钺，可代主行使最高权力，全权董督荆州事。</span>
                </p>
                <p>
                    <label>规格</label>
                    <span>长7厘米 宽5.5厘米 厚0.3厘米</span>
                </p>
                <p>
                    <label>品质</label>
                    <span>纯银做旧</span>
                </p>
                <p>
                    <label>个性</label>
                    <span>专属刻名</span>
                </p>
                <p>
                    <label>送达</label>
                    <span>快递公司指定送达</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="hide">
    <div id="yunchangjian-dialog" class="dialog">
        <div class="dialog-content">
            <h4>关羽军团最高荣誉</h4>
            <h5>领将军衔 佩云长剑</h5>
            <img src="<?php echo $this->appAssets('images/arm/yunchangjian.jpg')?>">
            <div class="desc">
                <p>总长度 108cm  总重量 2000g  剑刃重 1100g</p>
                <p>
                    <label>剑鞘用料</label>
                    <span>黑檀木实木</span>
                </p>
                <p>
                    <label>装具用料</label>
                    <span>加厚纯铜，双层雕刻，手工打磨做旧（防锈）</span>
                </p>
                <p>
                    <label>锻打工艺</label>
                    <span>八面剑刃，11段折叠锻打百炼花纹钢（锻纹密度4000层），13道全手工超细研磨（镜面效果），古法覆土烧刃（硬度61－62）</span>
                </p>
                <p>
                    <label>标配礼盒</label>
                    <span>精装木框缎面锦盒，专用剑架，绸布剑袋，养护刀油，擦刀布，使用手册，防伪标签。</span>
                </p>
                <p>
                    <label>宝剑品牌</label>
                    <span>四百年古法铸剑历史之嵩山宝剑</span>
                </p>
                <p>
                    <label>独有个性</label>
                    <span>专属定制，持有刻名</span>
                </p>
                <p>
                    <label>快递送达</label>
                    <span>快递公司安全送达</span>
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config(<?php echo $js_sdk_config?>);
wx.ready(function(){
    wx.onMenuShareTimeline({
        title: '天下招募令', // 分享标题
        link: '<?php echo $this->url('recruit')?>', // 分享链接
        imgUrl: '<?php echo $this->appAssets('images/arm/guanyin.png')?>', // 分享图标
        success: function(){
            // 用户确认分享后执行的回调函数
            $('body').block();
            $.ajax({
                'type': 'POST',
                'url': system.url('api/task/do'),
                'data': {'task_id': 2},
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    $('body').unblock();
                    if(resp.status){
                        common.toast('分享朋友圈任务完成', 'success');
                    }
                }
            });
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    });
});

$('#post-dialog').css({'width': document.documentElement.clientWidth * 0.7});
$('#yunchangjian-dialog').css({
    'width': document.documentElement.clientWidth * 0.75,
    'height': document.documentElement.clientHeight * 0.8
});
$('#jiangjunxian-dialog').css({
    'width': document.documentElement.clientWidth * 0.75
});

$(function(){
    var audio = new Audio("<?php echo $this->appAssets('music/gusheng2.mp3')?>");
    audio.addEventListener('timeupdate', function(){
        if(audio.currentTime == audio.duration){
            system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function() {
                system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function () {
                    $.fancybox($('#attendance-dialog').parent().html(), {
                        'padding': 0,
                        'centerOnScroll': true,
                        'width': '90%'
                    });
                });
            });
        }
    });
    $('#attendance-dialog-link').on('click', function(){
        audio.play();
    });
    
    $('.task-link').on('click', function(){
        $('body').block();
        //记录任务
        $.ajax({
            'type': 'POST',
            'url': system.url('api/task/do'),
            'data': {'task_id': $(this).attr('data-task-id')},
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                $('body').unblock();
            }
        });
    });

    system.getCss(system.assets('css/jquery.fancybox-1.3.4.css'), function(){
        system.getScript(system.assets('js/jquery.fancybox-1.3.4.pack.js'), function(){
            $('#post-dialog-link').fancybox({
                'padding': 0,
                'type': 'inline',
                'width': 300,
                'height': 500,
                'onStart': function(o){
                    if($(o).attr('data-id') == '0'){
                        common.toast('恭喜您已经完成所有资料阅读');
                        return false;
                    }else{
                        $('body').block();
                        $.ajax({
                            'type': 'GET',
                            'url': system.url('api/post/item'),
                            'data': {'id': $(o).attr('data-id')},
                            'dataType': 'json',
                            'cache': false,
                            'success': function(resp){
                                $('body').unblock();
                                if(resp.status){
                                    var $postDialog = $('#post-dialog');
                                    $postDialog.find('.post-title').text(resp.data.post.title);
                                    $postDialog.find('.post-content').html(resp.data.post.content);
                                }else{
                                    common.toast('恭喜您已经完成所有资料阅读');
                                }
                            }
                        });
                    }
                }
            });
        });
    });
});
</script>