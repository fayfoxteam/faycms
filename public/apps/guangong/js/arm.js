var arm = {
    /**
     * 正在发送ajax时，不再发起其他ajax，防止摇一摇功能重复发送ajax
     */
    'ajaxing': false,

    /**
     * 摇一摇音效实例
     */
    'shakeAudio': null,
    
    /**
     * 动画效果
     */
    'animate': function(){
        var $swiper = $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')');

        //履军职标题
        if($swiper.find('.job-title').length){
            $swiper.find('.job-title').show().addClass('rubberBand animated');
        }else{
            $('.job-title').hide().removeClass('rubberBand animated');
        }

        //防区说明顶部文字
        if($swiper.find('.defence-text').length){
            $swiper.find('.defence-text').show().addClass('fadeInDown animated');
        }else{
            $('.defence-text').hide().removeClass('fadeInDown animated');
        }

        //录军籍卷轴
        if($swiper.find('.juanzhou-he').length){
            $swiper.find('.juanzhou-he').show().addClass('fadeInLeft animated');
        }else{
            $('.juanzhou-he').hide().removeClass('fadeInDown animated');
        }

        //履军职任务列表
        if($swiper.find('.jobs').length){
            setTimeout(function(){
                $swiper.find('.jobs li:eq(0)').css({'visibility': 'visible'}).addClass('fadeIn animated');
            }, 300);
            setTimeout(function(){
                $swiper.find('.jobs li:eq(1)').css({'visibility': 'visible'}).addClass('fadeIn animated');
            }, 600);
            setTimeout(function(){
                $swiper.find('.jobs li:eq(2)').css({'visibility': 'visible'}).addClass('fadeIn animated');
            }, 900);
            setTimeout(function(){
                $swiper.find('.jobs li:eq(3)').css({'visibility': 'visible'}).addClass('fadeIn animated');
            }, 1200);
        }else{
            $('.jobs li').css({'visibility': 'hidden'}).removeClass('fadeIn animated');
        }
    },
    /**
     * 循环动画，执行一次就好了，不用重复执行
     */
    'interval': function(){
        //兵种文字
        if($('.arm-names').length){
            setInterval(function(){
                var $armNames = $('.arm-names');
                $armNames.find('.layer').removeClass('tada animated');
                //随机出一个0-5的整数
                var num = Math.floor(Math.random()*6);
                $armNames.find('.layer:eq('+num+')').addClass('tada animated');
            }, 1000);
        }

        //摇一摇标识
        if($('.shake').length){
            setInterval(function(){
                var $shake = $('.shake');
                if($shake.hasClass('animated')){
                    $shake.removeClass('tada animated');
                }else{
                    $shake.addClass('tada animated')
                }
            }, 1000);
        }
    },
    /**
     * 绑定摇一摇事件
     */
    'shake': function(){
        system.getScript(system.assets('faycms/js/faycms.shake.js'), function(){
            $.shake(function(){
                var $activeSlide = $('.swiper-wrapper').find('.swiper-slide:eq('+common.swiper.activeIndex+')');
                $activeSlide.find('.shake').click();
            });
        });
    },
    /**
     * 点击摇一摇按钮（效果等同于手机摇一摇）
     */
    'clickShake': function(){
        $(document).on('click', '.shake', function(){
            var $activeSlide = $(this).parent();
            if($activeSlide.hasClass('set-arm-slide')){
                arm.setArm();
            }else if($activeSlide.hasClass('set-defence-slide')){
                arm.setDefenceArea();
            }else if($activeSlide.hasClass('set-hour-slide')){
                arm.setHour();
            }else if($activeSlide.hasClass('show-info')){
                arm.showInfo();
            }
        });
    },
    /**
     * 播放摇一摇音效
     */
    'shakeMusic': function(){
        arm.shakeAudio.currentTime = 0;
        arm.shakeAudio.play();
    },
    /**
     * 初始化摇一摇音效
     */
    'initShakeMusic': function(){
        this.shakeAudio = new Audio(system.url('apps/guangong/music/5018.wav'));
        common.swiper.on('SlideChangeStart', function(){
            arm.shakeAudio.play();
            arm.shakeAudio.pause();
        });
    },
    'setArm': function(){
        if(this.ajaxing){
            return false;
        }
        this.ajaxing = true;
        this.shakeMusic();
        $.ajax({
            'type': 'GET',
            'url': system.url('api/arm/set'),
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                arm.ajaxing = false;
                if(resp.status){
                    var $setArmSlide = $('#arm-6');
                    $setArmSlide.find('.arms,.shake,.arm-names,.langan,.description').hide();
                    $setArmSlide.append('<a class="layer result fancybox-inline" href="#arm-dialog"><img src="'+resp.data.picture.url+'"></a>');
                    
                    //弹窗
                    $('#arm-dialog').find('.arm-description img').attr('src', resp.data.description_picture.url);
                    common.fancybox();
                    setTimeout(function(){
                        $('#arm-6').find('a.result').click();
                    }, 800);
                    
                    //移除class。摇一摇就失效了
                    $setArmSlide.removeClass('set-arm-slide');

                    //插入分享按钮和确定按钮
                    $setArmSlide.append(['<div class="layer operations">',
                        '<a href="javascript:" class="btn-1 confirm-to-next-link">兵种确定</a> ',
                        '<a href="javascript:" class="btn-1 reset-arm-link">重摇一次</a> ',
                        '</div>'].join(''));
                    
                    common.toast(resp.message, 'success');
                }else{
                    common.toast(resp.message, 'error');
                }
            }
        });
    },
    'setHour': function(){
        if(this.ajaxing){
            return false;
        }
        this.ajaxing = true;
        this.shakeMusic();
        $.ajax({
            'type': 'GET',
            'url': system.url('api/hour/set'),
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                arm.ajaxing = false;
                if(resp.status){
                    var $arm8 = $('#arm-8');
                    $arm8.find('.qiantong,.description').hide();
                    $arm8.append('<a class="layer result fancybox-inline" href="#hour-dialog"><span class="hour">'+resp.data.name+'</span></a>');

                    //弹窗
                    var $hourDialog = $('#hour-dialog');
                    $hourDialog.find('#hour-name').text(resp.data.name);
                    $hourDialog.find('#hour-time').text(resp.data.start_hour + '时至' + resp.data.end_hour + '时');
                    $hourDialog.find('#hour-description').text(resp.data.description + resp.data.zodiac);
                    common.fancybox();
                    setTimeout(function(){
                        $arm8.find('a.result').click();
                    }, 800);
                    
                    //移除class。摇一摇就失效了
                    $('.set-hour-slide').removeClass('set-hour-slide');
                    
                    //插入分享按钮和确定按钮
                    $arm8.append(['<div class="layer operations">',
                        '<a href="javascript:" class="btn-1 confirm-to-next-link">勤务确定</a> ',
                        '<a href="javascript:" class="btn-1 reset-hour-link">重摇一次</a> ',
                        '</div>'].join(''));
                    
                    common.toast(resp.message, 'success');
                }else{
                    common.toast(resp.message, 'error');
                }
            }
        });
    },
    'setDefenceArea': function(){
        if(this.ajaxing){
            return false;
        }

        var $arm4 = $('#arm-4');
        if($arm4.find('.shake').hasClass('go-to-sign')){
            $.fancybox.open('<div id="go-to-sign-dialog">身份未识别<br>请至<a href="'+system.url('recruit#9')+'" style="color:#df0011">天下招募令</a>注册</div>');
        }else{
            this.ajaxing = true;
            this.shakeMusic();
            $.ajax({
                'type': 'GET',
                'url': system.url('api/defence-area/set'),
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    arm.ajaxing = false;
                    if(resp.status){
                        $arm4.find('.shake').hide();
                        $arm4.find('.defence-text').hide();
                        $arm4.find('.description').hide();
                        $arm4.find('.map').html('<a class="result" data-fancybox href="#defence-dialog"><img src="' + resp.data.picture.url + '"></a>');
                        $('#defence-dialog').find('img').attr('src', resp.data.text_picture.url);
                        common.toast(resp.message, 'success');
                        setTimeout(function(){
                            $('#arm-4').find('a.result').click();
                        }, 800);
                        
                        //插入分享按钮和确定按钮
                        $arm4.append(['<div class="layer operations">',
                            '<a href="javascript:" class="btn-1 confirm-to-next-link">防区确定</a> ',
                            '<a href="javascript:" class="btn-1 reset-defence-link">再选一次</a> ',
                            '</div>'].join(''));
                        
                        //移除class。摇一摇就失效了
                        $('.set-defence-slide').removeClass('set-defence-slide');
                    }else{
                        common.toast(resp.message, 'error');
                    }
                }
            });
        }
    },
    /**
     * 显示军籍
     */
    'showInfo': function(){
        var $arm10 = $('#arm-10');
        $arm10.find('.juanzhou-he').addClass('fadeOut animated');
        $arm10.find('.juanzhou-he').removeClass('fadeInLeft');
        $arm10.find('.shake').remove();

        $.ajax({
            'type': 'GET',
            'url': system.url('api/user/info'),
            'data': {'user_id': system.user_id},
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                if(resp.status){
                    $arm10.find('.description').hide();
                    $('#info-avatar img').attr('src', resp.data.user.avatar.thumbnail);
                    $('#info-mobile').text(resp.data.user.mobile);
                    $('#info-birthday').text(resp.data.extra.birthday);
                    $('#info-region').text(resp.data.extra.state_name + ' ' + resp.data.extra.city_name + ' ' + resp.data.extra.district_name);
                    if(resp.data.extra.sign_up_time != 0){
                        $('#info-sign-up-time').text(system.date(resp.data.extra.sign_up_time, true));
                        $('#info-army-time').text(system.date(parseInt(resp.data.extra.sign_up_time) + 86400 * 365, true));
                    }
                    $('#info-defence-area').text(resp.data.extra.defence_area_name);
                    $('#info-arm').text(resp.data.extra.arm_name);
                    $('#info-rank').text(resp.data.extra.rank_name ? resp.data.extra.rank_name : '士兵');
                    $('#info-rank').attr('data-id', resp.data.extra.rank_id);
                    $('#info-id').text(resp.data.code)
                }else{
                    common.toast(resp.message, 'error');
                }
            }
        });
        
        setTimeout(function(){
            $arm10.find('.juanzhou-kai').show().addClass('zoomInUp animated');
            $arm10.find('.juanzhou-he').remove();
        }, 600);
    },
    'init': function(){
        this.animate();
        this.interval();
        this.shake();
        this.initShakeMusic();
        this.clickShake();
        common.swiper.on('SlideChangeStart', this.animate);
        
        //点击后允许继续下一页
        $(document).on('click', '.confirm-to-next-link', function(){
            //允许继续向后滑
            $('.swiper-wrapper .swiper-slide:eq('+common.swiper.activeIndex+')').removeClass('stop-to-next');
            $('.u-arrow-right').show();
            common.swiper.params.allowSwipeToNext = true;
            common.swiper.slideNext();
        });
        
        $(document).on('click', '.reset-defence-link', function(){
            var $arm4 = $('#arm-4');
            $arm4.find('.shake').show();
            $arm4.find('.defence-text').show();
            $arm4.find('.description').show();
            $arm4.find('.map').html('<img src="' + system.url('apps/guangong/images/arm/map.png') + '">');
            $arm4.find('.operations').remove();
            $arm4.addClass('set-defence-slide');
        }).on('click', '.reset-hour-link', function(){
            var $arm8 = $('#arm-8');
            $arm8.find('.qiantong,.description').show();
            $arm8.find('.result').remove();
            $arm8.find('.operations').remove();
            $arm8.addClass('set-hour-slide');
        }).on('click', '.reset-arm-link', function(){
            $setArmSlide = $('#arm-6');
            $setArmSlide.find('.arms,.shake,.arm-names,.langan,.description').show();
            $setArmSlide.find('.result').remove();
            $setArmSlide.find('.operations').remove();
            $setArmSlide.addClass('set-arm-slide');
        })
    }
};