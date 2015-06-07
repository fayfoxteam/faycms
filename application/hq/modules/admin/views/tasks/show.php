<?php


?>
<link rel="stylesheet" href="<?= $this->staticFile('zTree/css/zTreeStyle/zTreeStyle.css') ?>"/>
<script src="<?= $this->staticFile('zTree/js/jquery.ztree.core-3.5.js') ?>"></script>

<script>
    var setting = {
        data: {
            key: {
                title:"t"
            },
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeClick: beforeClick,
            onClick: onClick
        }
    };

    var eNodes =[
        { id:1, pId:0, name:"经管楼配电房", t:"经管楼配电房", open:true, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1001, pId:1, name:"经管楼配电房1#表", t:"经管楼配电房1#表", icon: system.url('static/hq/images/8.png')},
        { id:1002, pId:1, name:"经管楼配电房2#表", t:"经管楼配电房2#表", icon: system.url('static/hq/images/8.png')},
        { id:1003, pId:1, name:"经管楼配电房3#表", t:"经管楼配电房3#表", icon: system.url('static/hq/images/8.png')},
        { id:1004, pId:1, name:"经管楼配电房4#表", t:"经管楼配电房4#表", icon: system.url('static/hq/images/8.png')},
        { id:2, pId:0, name:"人文、外语楼", t:"人文、外语楼", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1005, pId:2, name:"人文、外语楼1#表", t:"人文、外语楼1#表", icon: system.url('static/hq/images/8.png') },
        { id:1006, pId:2, name:"人文、外语楼2#表", t:"人文、外语楼2#表", icon: system.url('static/hq/images/8.png') },
        { id:1007, pId:2, name:"人文、外语楼3#表", t:"人文、外语楼3#表", icon: system.url('static/hq/images/8.png') },
        { id:1008, pId:2, name:"人文、外语楼4#表", t:"人文、外语楼4#表", icon: system.url('static/hq/images/8.png') },
        { id:3, pId:0, name:"服装、理工楼", t:"服装、理工楼", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1009, pId:3, name:"服装、理工楼1#表", t:"服装、理工楼1#表", icon: system.url('static/hq/images/8.png') },
        { id:1010, pId:3, name:"服装、理工楼2#表", t:"服装、理工楼2#表", icon: system.url('static/hq/images/8.png') },
        { id:1011, pId:3, name:"服装、理工楼3#表", t:"服装、理工楼3#表", icon: system.url('static/hq/images/8.png') },
        { id:1012, pId:3, name:"服装、理工楼4#表", t:"服装、理工楼4#表", icon: system.url('static/hq/images/8.png') },
        { id:4, pId:0, name:"1号学生公寓", t:"1号学生公寓", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1013, pId:4, name:"1号学生公寓1#表", t:"1号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1014, pId:4, name:"1号学生公寓2#表", t:"1号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:5, pId:0, name:"2号学生公寓", t:"2号学生公寓", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1015, pId:5, name:"2号学生公寓1#表", t:"2号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1016, pId:5, name:"2号学生公寓2#表", t:"2号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:6, pId:0, name:"3号学生公寓", t:"3号学生公寓", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1017, pId:6, name:"3号学生公寓1#表", t:"3号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1018, pId:6, name:"3号学生公寓2#表", t:"3号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:7, pId:0, name:"4号学生公寓", t:"4号学生公寓", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1019, pId:7, name:"4号学生公寓1#表", t:"4号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1020, pId:7, name:"4号学生公寓2#表", t:"4号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:8, pId:0, name:"5号学生公寓", t:"5号学生公寓", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1021, pId:8, name:"5号学生公寓1#表", t:"5号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1022, pId:8, name:"5号学生公寓2#表", t:"5号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:9, pId:0, name:"6号学生公寓", t:"6号学生公寓", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1023, pId:9, name:"6号学生公寓1#表", t:"6号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1024, pId:9, name:"6号学生公寓2#表", t:"6号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:10, pId:0, name:"7号学生公寓", t:"7号学生公寓", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1025, pId:10, name:"7号学生公寓1#表", t:"7号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1026, pId:10, name:"7号学生公寓2#表", t:"7号学生公寓2#表"},
        { id:11, pId:0, name:"8号学生公寓", t:"8号学生公寓", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1027, pId:11, name:"8号学生公寓1#表", t:"8号学生公寓1#表", icon: system.url('static/hq/images/8.png')},
        { id:1028, pId:11, name:"8号学生公寓2#表", t:"8号学生公寓2#表", icon: system.url('static/hq/images/8.png')},
        { id:12, pId:0, name:"活动中心", t:"活动中心", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1029, pId:12, name:"活动中心1#表", t:"活动中心1#表", icon: system.url('static/hq/images/8.png')},
        { id:1030, pId:12, name:"活动中心2#表", t:"活动中心2#表", icon: system.url('static/hq/images/8.png')},
        { id:13, pId:0, name:"1号青年教工1#表", t:"1号青年教工1#表", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1031, pId:13, name:"1号青年教工1#表", t:"1号青年教工1#表", icon: system.url('static/hq/images/8.png')},
        { id:1032, pId:13, name:"1号青年教工2#表", t:"1号青年教工2#表", icon: system.url('static/hq/images/8.png')},
        { id:14, pId:0, name:"南区地下室", t:"南区地下室", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1033, pId:14, name:"南区地下室1#表", t:"南区地下室1#表", icon: system.url('static/hq/images/8.png')},
        { id:1034, pId:14, name:"南区地下室2#表", t:"南区地下室2#表", icon: system.url('static/hq/images/8.png')},
        { id:1035, pId:14, name:"南区地下室3#表", t:"南区地下室3#表", icon: system.url('static/hq/images/8.png')},
        { id:1036, pId:14, name:"南区地下室4#表", t:"南区地下室4#表", icon: system.url('static/hq/images/8.png')},
        { id:15, pId:0, name:"米饭流水线", t:"米饭流水线", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1037, pId:15, name:"米饭流水线1#表", t:"米饭流水线1#表", icon: system.url('static/hq/images/8.png')},
        { id:16, pId:0, name:"南区一楼食堂", t:"南区一楼食堂", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1038, pId:16, name:"南区一楼食堂1#表", t:"南区一楼食堂1#表", icon: system.url('static/hq/images/8.png')},
        { id:1039, pId:16, name:"南区一楼食堂2#表", t:"南区一楼食堂2#表", icon: system.url('static/hq/images/8.png')},
        { id:1040, pId:16, name:"南区一楼食堂3#表", t:"南区一楼食堂3#表", icon: system.url('static/hq/images/8.png')},
        { id:17, pId:0, name:"南区二楼食堂", t:"南区二楼食堂", open:false, click: false , iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png')},
        { id:1041, pId:17, name:"南区二楼食堂1#表", t:"南区二楼食堂1#表", icon: system.url('static/hq/images/8.png')},
        { id:1042, pId:17, name:"南区二楼食堂2#表", t:"南区二楼食堂2#表", icon: system.url('static/hq/images/8.png')},
        { id:1043, pId:17, name:"南区二楼食堂3#表", t:"南区二楼食堂3#表", icon: system.url('static/hq/images/8.png')},
        { id:18, pId:0, name:"南区三楼食堂", t:"南区三楼食堂", open:false, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id:1044, pId:18, name:"南区三楼食堂1#表", t:"南区三楼食堂1#表", icon: system.url('static/hq/images/8.png')},
        { id:1045, pId:18, name:"南区三楼食堂2#表", t:"南区三楼食堂2#表", icon: system.url('static/hq/images/8.png')},
        { id:1046, pId:18, name:"南区三楼食堂3#表", t:"南区三楼食堂3#表", icon: system.url('static/hq/images/8.png')}
    ];

    var wNodes = [
        { id: 1 ,pId: 1, name: "水表", t: "水表", open: true, click: false, iconOpen: system.url('static/hq/images/open.png'), iconClose: system.url('static/hq/images/close.png') },
        { id: 1047, pId: 1, name: "学院总表", t: "学院总表", icon: system.url('static/hq/images/8.png')},
        { id: 1048, pId: 1, name: "消防总表", t: "消防总表", icon: system.url('static/hq/images/8.png')},
        { id: 1049, pId: 1, name: "经管1", t: "经管1", icon: system.url('static/hq/images/8.png')},
        { id: 1050, pId: 1, name: "经管2", t: "经管2", icon: system.url('static/hq/images/8.png')},
        { id: 1051, pId: 1, name: "活动中心", t: "活动中心", icon: system.url('static/hq/images/8.png')},
        { id: 1052, pId: 1, name: "人文外语", t: "人文外语", icon: system.url('static/hq/images/8.png')},
        { id: 1053, pId: 1, name: "服装理工", t: "服装理工", icon: system.url('static/hq/images/8.png')},
        { id: 1054, pId: 1, name: "公寓", t: "公寓", icon: system.url('static/hq/images/8.png')},
        { id: 1055, pId: 1, name: "教工1（西）", t: "教工1（西）", icon: system.url('static/hq/images/8.png')},
        { id: 1056, pId: 1, name: "教工1（东）", t: "教工1（东）", icon: system.url('static/hq/images/8.png')},
        { id: 1057, pId: 1, name: "教工2（西）", t: "教工2（西）", icon: system.url('static/hq/images/8.png')},
        { id: 1058, pId: 1, name: "教工2（东）", t: "教工2（东）", icon: system.url('static/hq/images/8.png')},
        { id: 1059, pId: 1, name: "食堂", t: "食堂", icon: system.url('static/hq/images/8.png')}
    ];

    var log, className = "dark";

    function beforeClick(treeId, treeNode, clickFlag) {
    }

    function onClick(event, treeId, treeNode, clickFlag) {
         console.log(treeNode);
        var type = treeId == 'treeElectric' ? 1 : 2;
        if (!treeNode.pId)
        {
//            alert('请选择' + treeNode.name + '节点下面的电表' );
            return;
        }
        $.ajax({
            url: system.url('admin/tasks/getData'),
            type: 'POST',
            dataType: 'json',
            data: {
                type: type,
                treeId: treeNode.id,
                name: treeNode.name,
                text: treeNode.t
            },
            success: function(data)
            {
//                console.log(data);
                if (data.code == 0)
                {
                    tongji_charts.create(data.data, data.text, data.name, data.date, data.type);
                }
                else
                {
                    alert(data.message);
                }
            }
        });

    }

    function getTime() {
        var now= new Date(),
            h=now.getHours(),
            m=now.getMinutes(),
            s=now.getSeconds();
        return (h+":"+m+":"+s);
    }

</script>


<div class="row">
    <div class="col-3">
        <div class="box" data-name="tasks/lists">
            <div class="box-title">
                <a class="tools toggle" title="点击以切换"></a>
                <h4 style="cursor: pointer;">电表分布</h4>
            </div>
            <div class="box-content">
                <div class="zTreeDemoBackground left">
                    <ul id="treeElectric" class="ztree"></ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="box" data-name="tasks/lists">
            <div class="box-title">
                <a class="tools toggle" title="点击以切换"></a>
                <h4 style="cursor: pointer;">水表分布</h4>
            </div>
            <div class="box-content">
                <div class="zTreeDemoBackground left">
                    <ul id="treeWater" class="ztree"></ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="box" data-name="tasks/charts">
            <div class="box-title">
                <a class="tools toggle" title="点击以切换"></a>
                <h4 style="cursor: pointer;">使用情况</h4>
            </div>
            <div class="box-content">
                <div id="charts"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>


<script>
    var tongji_charts = {
        'create': function(data, text, name, date, type)
        {
            $('#charts').highcharts({
                title: {
                    text: text,
                    x: -20 //center
                },
                subtitle: {
                    text: '地点:'+ name,
                    x: -20
                },
                credits: {
                    text: '绍兴文理学院元培学院'
                },
                xAxis: {
                    categories: date
                },
                yAxis: {
                    title: {
                        text: type == 1 ? '电量 (度)' : '水(吨)'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: type == 1 ? '度' : '吨'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                    name: '使用',
                    data: data
                }]
            });
        }
    }
    $(function () {
        tongji_charts.create(<?= json_encode($data) ?>, '经管楼配电房1#表', '经管楼配电房1#表', <?= json_encode($date) ?>, 1);
        $.fn.zTree.init($("#treeElectric"), setting, eNodes);
        $.fn.zTree.init($("#treeWater"), setting, wNodes);
    });
</script>

<script src="<?= $this->staticFile('highcharts/js/highcharts.js') ?>"></script>