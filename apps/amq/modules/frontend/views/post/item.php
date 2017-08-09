<!--中间部分start-->
<div class="container-fluid amc-center clearfix">
    
    <!--左边内容start-->
    <div class="amc-main">
        <?php F::widget()->load('post-item')?>
        <!-- 评论 Start -->

        <link rel="stylesheet" type="text/css" href="<?php echo $this->appAssets('css/discuss.css')?>">
        <script src="<?php echo $this->appAssets('js/jsviews.min')?>"></script>
        <div class="discuss">
            <div class="discuss-box clearfix">
                <p class="tit"><strong>用户评论</strong></p>
                <form id="discuss-control" class="discuss-control">
                    <textarea class="discuss-text" name="text" id="discuss-text" rows="3" placeholder="我来说两句..."></textarea>
                    <p class="total-d">还可以输入<span id="count-d">140</span>字</p>
                    <button class="discuss-send pull-right" type="button">发表评论</button>
                </form>
            </div>
            <div class="discuss-list clearfix">
                <div class="discuss-total"><strong>全部评论<span>（123）</span></strong></div>

                <!-- 全部评论Start -->
                <div id="list">
                    <div class="box clearfix">
                        <img class="avatar-64 pull-left" src="<?php echo $this->appAssets('images/photo_b.jpg')?>" alt=""/>
                        <div class="content">
                            <div class="main">
                                <p><strong class="comment-user">东边的太阳</strong><span class="comment-time">2017-01-01 14:36</span></p>
                                <p class="txt">
                                    在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。
                                </p>
                            </div>
                            <p class="info">
                                <a class="praise" total="4" my="0" href="javascript:;">赞（4）</a>
                                <a class="reply" href="javascript:;">回复</a>
                            </p>
                            <div class="text-box">
                                <button class="btn0 btn-off">回 复</button>
                                <textarea class="comment" autocomplete="off" placeholder="来来来，说你的看法..."></textarea>
                                <span class="word"><span class="length">0</span>/140</span>
                            </div>
                            <div class="comment-list">
                                <div class="comment-box clearfix">
                                    <img class="avatar-48 pull-left" src="<?php echo $this->appAssets('images/photo_s.jpg')?>" alt=""/>
                                    <div class="comment-content">
                                        <p><strong class="comment-user">我</strong><span class="comment-time">15小时前</span></p>
                                        <p class="txt">在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。</p>
                                        <p class="info">
                                            <a class="comment-praise" href="javascript:;" total0="1" my0="0">赞（1）</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box clearfix">
                        <img class="avatar-64 pull-left" src="<?php echo $this->appAssets('images/photo_b.jpg')?>" alt=""/>
                        <div class="content">
                            <div class="main">
                                <p><strong class="comment-user">西边的雨</strong><span class="comment-time">2017-01-01 14:36</span></p>
                                <p class="txt">
                                    在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。
                                </p>
                            </div>
                            <p class="info">
                                <a class="praise" total="4" my="0" href="javascript:;">赞（4）</a>
                                <a class="reply" href="javascript:;">回复</a>
                            </p>
                            <div class="text-box">
                                <button class="btn0 btn-off">回 复</button>
                                <textarea class="comment" autocomplete="off" placeholder="来来来，说你的看法"></textarea>
                                <span class="word"><span class="length">0</span>/140</span>
                            </div>
                            <div class="comment-list">
                                <div class="comment-box clearfix">
                                    <img class="avatar-48 pull-left" src="<?php echo $this->appAssets('images/photo_s.jpg')?>" alt=""/>
                                    <div class="comment-content">
                                        <p><strong class="comment-user">老鹰</strong><span class="comment-time">15小时前</span></p>
                                        <p class="txt">在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。</p>
                                        <p class="info">
                                            <a class="comment-praise" href="javascript:;" total0="3" my0="0">赞（3）</a>
                                        </p>
                                    </div>
                                </div>
                                <!-- 第一条回复 -->
                                <div class="comment-box clearfix">
                                    <img class="avatar-48 pull-left" src="<?php echo $this->appAssets('images/photo_s.jpg')?>" alt=""/>
                                    <div class="comment-content">
                                        <p><strong class="comment-user">老鹰</strong><span class="comment-time">15小时前</span></p>
                                        <p class="txt">在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。</p>
                                        <p class="info">
                                            <a class="comment-praise" href="javascript:;" total0="3" my0="0">赞（3）</a>
                                        </p>
                                    </div>
                                </div>
                                <!-- 第二条回复 -->
                            </div>
                        </div>
                    </div>

                    <div class="box clearfix">
                        <img class="avatar-64 pull-left" src="<?php echo $this->appAssets('images/photo_b.jpg')?>" alt=""/>
                        <div class="content">
                            <div class="main">
                                <p><strong class="comment-user">东边的太阳</strong><span class="comment-time">2017-01-01 14:36</span></p>
                                <p class="txt">
                                    在中国你抛弃了微信，等于抛弃了你的社交圈。就算年轻人平常不用微信，但你的同学，接人，同事等等总有人用。所以你应该。。。
                                </p>
                            </div>
                            <div class="info">
                                <a class="praise" total="0" my="0" href="javascript:;">赞</a>
                                <a class="reply" href="javascript:;">回复</a>
                            </div>
                            <div class="text-box">
                                <button class="btn0 btn-off">回 复</button>
                                <textarea class="comment" autocomplete="off" placeholder="来来来，说你的看法"></textarea>
                                <span class="word"><span class="length">0</span>/140</span>
                            </div>
                            <div class="comment-list"></div>
                        </div>
                    </div>
                    <!-- 第一条评论 End -->
                </div>
                <!-- 全部评论 End -->
            </div>

            <!-- page Start -->
            <div class="sc-page">
                <p class="D_pager" id="D_pager">
                    <a class="no">首页</a>
                    <a class="no">上一页</a>
                    <a class="page-no now">1</a>
                    <a class="page-no">2</a>
                    <a class="page-no">2</a>
                    <span class="page-no">&hellip;</span>
                    <a class="page-no">25</a>
                    <a class="no">下一页</a>
                    <a class="no">尾页</a>
                </p>
            </div>
        </div>
        
<!-- 评论js -->
<script src="<?php echo $this->appAssets('js/discuss.js')?>"></script>
<script>
$(function(){
    $(".reply").click(function(){
        var t=$(this);
        t.parent().next().show();
    });
});
//评论框字数
$(function(){
    $("#discuss-text").keyup(function(){
        var len = $(this).val().length;
        if(len > 139){
            $(this).val($(this).val().substring(0,140));
        }
        var num = 140 - len;
        $("#count-d").text(num);
    });
});
//回复框字数
$(function(){
    $(".comment").keyup(function(){
        var len = $(this).val().length;
        if(len > 139){
            $(this).val($(this).val().substring(0,140));
        }
        $(".length").text(len);
    });
});
</script>
<!-- ajax提交表单 -->
<script>
$(function(){
    $('#discuss-text').click(function(){
        $.ajax({
            type:"GET",
            url:"http://baike.22.fayfox.com/apidoc/frontend/api/item?api_id=1005",
            success : function (data) {
                console.log(data); 
            }
        });
    });
});
</script>
        <!-- Ajax 获取评论列表及分页 -->
<script src="js/jsviews.min"></script>
<script type="text/javascript">
    $(function(){
            discussion = {},
            params = { 
                pageIndex: 1,
                pageCount: 8,
                act: 'List',
                item: 1
            };
        
        init();
        
        $('#D_pager').on('click', 'a[data-index]', function(){
            params["pageIndex"] = $(this).data('index');
            getDiscussion();
        });
        
        function init(){
            var discussTmpl = $.templates('#J_discussion_tmpl');
            agentTmpl.link('#J_discuss_list', discussion);
            getDiscussion();
        }
        
        function getDiscussion() {
            $.ajax({
                url: 'http://baike.22.fayfox.com/apidoc/frontend/api/item?api_id=1005',
                data: params,
                method: 'post',
                dataType: 'json',
                beforeSend: function(){},
                complete: function(){},
                success: function(result){
                    if(result.status_code != 0) {
                        MWIN.alert(result.message + ' 获取评论失败！');
                    } else {
                        var data =  result.data ? result.data : [];
                        $.observable(discussion).setProperty({
                            data: data,
                            query: true
                        });
                        pager(result.pager);
                    }
                },
                fail: function(){
                    MWIN.alert('系统错误，获取评论失败！');
                }
            });
        }
        
        function resetPageIndex() {
            params['pageIndex'] = 1;
        }
        
        function pager(pager) {
            if(pager) {
                PageBar2.Html({
                    Id: 'D_pager',
                    PageIndex: pager.pageIndex,
                    PageCount: pager.pageCount,
                    Total: pager.total
                });
            } else {
                $('#D_pager').empty();
            }
        }
    });
</script>

        <!-- 评论End -->
    </div>
    <!--左边内容over-->
    
    <!--右边内容start-->
    <div class="amc-aside hidden-xs" id="startBottom">
        <!--搜索框start-->
        <?php echo $this->renderPartial('common/search_form')?>
        <!--搜索框over-->

        <div class="amc-fix fix-bottom">
            <?php F::widget()->area('item-sidebar-fixed')?>
        </div>
        <?php F::widget()->area('item-sidebar')?>
    </div>
    <!--右边内容over-->
    
    <!--侧边栏start-->
    <ul class="amc-bside">
        <li class="amc-wx"><img src="<?php echo $this->appAssets('images/ewm.png')?>" alt="" class="amc-ewm"></li>
        <li class="retop"></li>
    </ul>
    <!--侧边栏over-->
</div>
<!--中间部分over-->

<!--中间部分移动端start-->
<div class="container-fluid m-amc-center visible-xs-block">
    <?php F::widget()->area('mobile-item-sidebar')?>
    移动端评论部分
</div>
<!--中间部分移动端over-->

