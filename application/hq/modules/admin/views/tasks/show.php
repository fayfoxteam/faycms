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
        { id:1, pId:0, name:"1幢寝室楼电表", t:"1幢寝室楼电表", open:true, click: false },
        { id:1001, pId:1, name:"1001", t:"1幢寝室楼"},
        { id:12, pId:1, name:"1002", t:"1幢寝室楼"},
        { id:13, pId:1, name:"1003", t:"1幢寝室楼"},
        { id:2, pId:0, name:"2幢寝室楼电表", t:"2幢寝室楼电表", open:true, click: false },
        { id:21, pId:2, name:"2001", t:"2幢寝室楼" },
        { id:22, pId:2, name:"2002", t:"2幢寝室楼" },
        { id:23, pId:2, name:"2003", t:"2幢寝室楼" },
        { id:3, pId:0, name:"3幢寝室楼电表", t:"3幢寝室楼电表", open:true, click: false },
        { id:31, pId:3, name:"3001", t:"3幢寝室楼"},
        { id:32, pId:3, name:"3002", t:"3幢寝室楼"},
        { id:33, pId:3, name:"3003", t:"3幢寝室楼"}
    ];

    var wNodes = [
        { id: 1 ,pId: 0, name: "水表", t: "水表", open: true, click: false },
        { id: 1046, pId: 1, name: "学院总表", t: "学院总表"},
        { id: 1047, pId: 1, name: "消防总表", t: "消防总表"},
        { id: 1048, pId: 1, name: "经管1", t: "经管1"},
        { id: 1049, pId: 1, name: "经管2", t: "经管2"},
        { id: 1050, pId: 1, name: "活动中心", t: "活动中心"},
        { id: 1051, pId: 1, name: "人文外语", t: "人文外语"},
        { id: 1052, pId: 1, name: "服装理工", t: "服装理工"},
        { id: 1053, pId: 1, name: "公寓", t: "公寓"},
        { id: 1054, pId: 1, name: "教工1（西）", t: "教工1（西）"},
        { id: 1055, pId: 1, name: "教工1（东）", t: "教工1（东）"},
        { id: 1056, pId: 1, name: "教工2（西）", t: "教工2（西）"},
        { id: 1057, pId: 1, name: "教工2（东）", t: "教工2（东）"},
        { id: 1058, pId: 1, name: "食堂", t: "食堂"}
    ];

    var log, className = "dark";

    function beforeClick(treeId, treeNode, clickFlag) {
    }

    function onClick(event, treeId, treeNode, clickFlag) {
         console.log(treeNode);
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
                type: 1,
                treeId: treeNode.id,
                name: treeNode.name,
                text: treeNode.t
            },
            success: function(data)
            {
//                console.log(data);
                if (data.code == 0)
                {
                    tongji_charts.create(data.data, data.text, data.name, data.date);
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
        'create': function(data, text, name, date)
        {
            $('#charts').highcharts({
                title: {
                    text: text + name + '表',
                    x: -20 //center
                },
                subtitle: {
                    text: '来源:人工输入',
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
                        text: '电量 (度)'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: '度'
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
        tongji_charts.create(<?= json_encode($data) ?>, '1幢寝室楼', '1001', <?= json_encode($date) ?>);
        $.fn.zTree.init($("#treeElectric"), setting, eNodes);
        $.fn.zTree.init($("#treeWater"), setting, wNodes);
    });
</script>

<script src="<?= $this->staticFile('highcharts/js/highcharts.js') ?>"></script>