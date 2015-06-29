/**
 * Created by Administrator on 2014/10/16.
 */

$(function(){
    var nian = 2014;
    $('.sale-style1 li').click(function(){
        $(this).addClass('cur').siblings().removeClass('cur');
        $(this).closest('.sale-style1').addClass('sale-style2');
        $('img',this).attr('src','images/8.png').css({'height':'19px','width':'34px'});
        $(this).siblings().find('img').attr('src','images/6.png').css({'height':'40px','width':'21px'});
        var _title = $('.title',this);
        _title.text( nian + _title.attr('data'));
       $(this).siblings().find('.title').each(function(){
           $(this).text( $(this).attr('data'));
       });

    });

    $('#chart1').highcharts({
        chart: {
            type: 'spline',
            width:290,
            height:160

        },
        'title': {
            'text': null
        },

        xAxis: {
            'title':null,
            'labels': {
                'step': 3
            },
            'categories': (function(){
                var categories = [];
                for(var i = 1; i <=12; i++){
                    categories.push(i + '月');
                }
                return categories;
            })()


        },

        yAxis:{
            'title':null,
            opposite:true,
            labels: {
                formatter: function() {
                    return this.value  +'亿';
                }
            }
        },
        legend:{
            enabled:false
        },
        series: [{
            name: ' 成交量',
            data: [123.5,119.34,133.67,147.64,160.42,166.69,188.79,247.84,278.26]

        }],
        credits:{
            enabled:false
        },
        tooltip:{
            formatter:function(){
                return''+this.series.name+''+
                    this.x+': '+Highcharts.numberFormat(this.y,2,'.')+' 亿';
            }
        }
    });


    $('#chart2').highcharts({
        chart: {
            type: 'spline',
            width:290,
            height:160

        },
        'title': {
            'text': null
        },

        xAxis: {
            'title':null,
            'labels': {
                'step': 3
            },
            'categories': (function(){
                var categories = [];
                for(var i = 1; i <=12; i++){
                    categories.push(i + '月');
                }
                return categories;
            })()


        },

        yAxis:{
            'title':null,
            opposite:true,
            labels: {
                formatter: function() {
                    return this.value  +'%';
                }
            }
        },
        legend:{
            enabled:false
        },
        series: [{
            name: '年平均利率',
            data: [19.72,23.5,21.16,20.82,17.96,18.74,16.97,17.64,17.82]

        }],
        credits:{
            enabled:false
        },
        tooltip:{
            formatter:function(){
                return''+this.series.name+''+
                    this.x+': '+Highcharts.numberFormat(this.y,2,'.')+' %';
            }
        }
    });


    $('#chart3').highcharts({
        chart: {
            type: 'spline',
            width:290,
            height:160

        },
        'title': {
            'text': null
        },

        xAxis: {
            'title':null,
            'labels': {
                'step': 3
            },
            'categories': (function(){
                var categories = [];
                for(var i = 1; i <=12; i++){
                    categories.push(i + '月');
                }
                return categories;
            })()


        },

        yAxis:{
            'title':null,
            opposite:true,
            labels: {
                formatter: function() {
                    return this.value  +'万人';
                }
            }
        },
        legend:{
            enabled:false
        },
        series: [{
            name: ' 借款人数',
            data: [3.2,3.57,5.81,5.9,6.77,7.12,9.07,9.76,11.04]

        }],
        credits:{
            enabled:false
        },
        tooltip:{
            formatter:function(){
                return''+this.series.name+''+
                    this.x+': '+Highcharts.numberFormat(this.y,2,'.')+' 万人';
            }
        }
    });


    $('#chart4').highcharts({
        chart: {
            type: 'spline',
            width:290,
            height:160

        },
        'title': {
            'text': null
        },

        xAxis: {
            'title':null,
            'labels': {
                'step': 3
            },
            'categories': (function(){
                var categories = [];
                for(var i = 1; i <=12; i++){
                    categories.push(i + '月');
                }
                return categories;
            })()


        },

        yAxis:{
            'title':null,
            opposite:true,
            labels: {
                formatter: function() {
                    return this.value  +'万人';
                }
            }
        },
        legend:{
            enabled:false
        },
        series: [{
            name: ' 投资人数',
            data: [13.85,18.84,25.62,26.85,28.73,34.95,40.83,52.89,66.48]

        }],
        credits:{
            enabled:false
        },
        tooltip:{
            formatter:function(){
                return''+this.series.name+''+
                    this.x+': '+Highcharts.numberFormat(this.y,2,'.')+' 万人';
            }
        }
    });







});