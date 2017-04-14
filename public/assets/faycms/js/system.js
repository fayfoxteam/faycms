var system = {
    'user_id': 0,
    'base_url': null,
    'assets_url': null,
    'loadingScripts' : {},
    'loadingCss' : {},
    'url' : function(router, params){
        if(router){
            if(typeof(params) == 'undefined'){
                params = {};
            }
            var url = this.base_url + router;
            var param_arr = [];
            $.each(params, function(i, e){
                param_arr.push(i + '=' + encodeURIComponent(e));
            });
            if(param_arr.length){
                url += '?';
                url += param_arr.join('&');
            }
            return url;
        }else{
            return this.base_url;
        }
    },
    'assets':function(uri){
        this.assets_url = this.assets_url || this.base_url + 'assets/';
        return this.assets_url + uri;
    },
    'date' : function(timestamp, only_date){
        if(timestamp == 0)return '';
        var date = new Date(parseInt(timestamp) * 1000);
        var month = date.getMonth() + 1;
        if(month < 10) month = '0' + month;
        var day = date.getDate();
        if(day < 10) day = '0' + day;
        if(only_date)return date.getFullYear() + '-' + month + '-' + day;
        var hour = date.getHours();
        if(hour < 10) hour = '0' + hour;
        var minute = date.getMinutes();
        if(minute < 10) minute = '0' + minute;
        var second = date.getSeconds();
        if(second < 10) second = '0' + second;
        return date.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
    },
    //美化时间输出，传入精确到秒的时间戳
    'shortDate':function(timestamp){
        var now = new Date();
        var now_timestamp = parseInt(now.getTime() / 1000);
        var dv = now_timestamp - timestamp;
        var date = new Date(parseInt(timestamp) * 1000);
        var today = new Date();
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        var today_timestamp = parseInt(today.getTime() / 1000);
        
        if(dv < 3){
            return '刚刚';
        }else if(dv < 60){
            //一分钟内
            return dv+'秒前';
        }else if(dv < 3600){
            //一小时内
            return Math.floor(dv / 60)+'分钟前';
        }else if(dv < 86400 && date.getDate() == now.getDate()){
            //今天内
            return Math.floor(dv / 3600)+'小时前';
        }else if(dv < ((now_timestamp - today_timestamp) + 6 * 86400)){
            //7天内
            return Math.ceil((dv - (now_timestamp - today_timestamp)) / 86400)+'天前';
        }else if(date.getFullYear() == now.getFullYear()){
            var month = date.getMonth() + 1;
            return month+'月'+date.getDate()+'日';
        }else{
            var month = date.getMonth() + 1;
            return date.getFullYear().toString().substring(2)+'年'+month+'月'+date.getDate()+'日';
        }
    },
    'encode' : function(str){
        var s = "";
        if (str == undefined || str.length == 0) return "";
        s = str.replace(/&/g, "&amp;");
        s = s.replace(/</g, "&lt;");
        s = s.replace(/>/g, "&gt;");
        s = s.replace(/ /g,"&nbsp;");
        s = s.replace(/\'/g, "&#39;");
        s = s.replace(/\"/g, "&quot;");
        s = s.replace(/\n/g, "<br>");
        return s;
    },
    'changeTwoDecimal' : function(x){
        var f_x = parseFloat(x);
        if (isNaN(f_x)){
            return false;
        }
        var f_x = Math.round(x*100)/100;
        var s_x = f_x.toString();
        var pos_decimal = s_x.indexOf('.');
        if (pos_decimal < 0){
            pos_decimal = s_x.length;
            s_x += '.';
        }
        while (s_x.length <= pos_decimal + 2){
            s_x += '0';
        }
        return s_x;
    },
    /**
     * 相对于jquery的inArray只能作用于数组，且类型必须匹配
     * 这个inArray可以作用于对象，且可以选择是否强制类型匹配
     */
    'inArray':function(needle, haystack, argStrict){
        var key = '',
        strict = !! argStrict;
    
        if(strict){
            for(key in haystack){
                if(haystack[key] === needle){
                    return true;
                }
            }
        }else{
            for(key in haystack) {
                if(haystack[key] == needle) {
                    return true;
                }
            }
        }
        return false;
    },
    'arrayUnique':function(arr){
        var key = '',
        tmp_arr2 = {},
        val = '';

        var __array_search = function(needle, haystack){
            var fkey = '';
            for(fkey in haystack){
                if(haystack.hasOwnProperty(fkey)){
                    if((haystack[fkey] + '') === (needle + '')){
                        return fkey;
                    }
                }
            }
            return false;
        };

        for(key in inputArr){
            if(inputArr.hasOwnProperty(key)){
                val = inputArr[key];
                if (false === __array_search(val, tmp_arr2)){
                    tmp_arr2[key] = val;
                }
            }
        }

        return tmp_arr2;
    },
    'log':function(input){
        if(!($.browser.msie && $.browser.version < 9)){
            console.log(input);
        }
    },
    /**
    * 带缓存引入js文件，并防止重复引入
    * 连续执行时，会将回调函数放入队列，待js文件加载完成后，一起执行
    */
    'getScript':function(url, callback){//引入js文件，有缓存
        if(typeof(system.loadingScripts[url]) == 'undefined' && !$("script[src='"+url+"']").length){
            //首次加载
            //system.log('首次加载'+url);
            system.loadingScripts[url] = [callback];
            $.ajax({
                type: "GET",
                url: url,
                dataType: "script",
                cache: true,
                success: function(){
                    //首次加载完成
                    //system.log('加载'+url+'完成，执行如下函数队列');
                    //system.log(system.loadingScripts[url]);
                    $.each(system.loadingScripts[url], function(i, func){
                        if(typeof(func) == 'function'){
                            func();
                        }
                    });
                    system.loadingScripts[url] = [];
                }
            });
        }else{
            if(system.loadingScripts[url] && system.loadingScripts[url].length){
                //非首次加载，但文件加载未完成，回调函数放入队列
                //system.log('非首次'+url+'加载，但文件加载未完成，回调函数放入队列');
                system.loadingScripts[url].push(callback);
            }else{
                //非首次加载，且文件已被成功加载，直接执行回调
                //system.log('非首次加载'+url+'，且文件已被成功加载，直接执行回调');
                //system.log(callback);
                if(typeof(callback) == 'function'){
                    callback();
                }
            }
        }
    },
    /**
    * 动态引入一个css文件，不会重复引入
    */
    'getCss':function(url, callback){
        if(typeof(system.loadingCss[url]) == 'undefined' && !$("link[href='"+url+"']").length){
            //首次加载
            //system.log('首次加载'+url);
            system.loadingCss[url] = [callback];
            $("<link>").attr({
                "rel":"stylesheet",
                "type":"text/css",
                "href":url
            })
            .load(function(){
                //system.log('加载'+url+'完成，执行如下函数队列');
                //system.log(system.loadingCss[url]);
                $.each(system.loadingCss[url], function(i, func){
                    if(typeof(func) == 'function'){
                        func();
                    }
                });
                system.loadingCss[url] = [];
            })
            .appendTo("head");
        }else{
            if(system.loadingCss[url] && system.loadingCss[url].length){
                //非首次加载，但文件加载未完成，回调函数放入队列
                //system.log('非首次'+url+'加载，但文件加载未完成，回调函数放入队列');
                system.loadingCss[url].push(callback);
            }else{
                //非首次加载，且文件已被成功加载，直接执行回调
                //system.log('非首次加载'+url+'，且文件已被成功加载，直接执行回调');
                //system.log(callback);
                if(typeof(callback) == 'function'){
                    callback();
                }
            }
        }
    }
};