<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 15/7/6
 * Time: 下午10:25
 */
?>

<link rel="stylesheet" href="<?= $this->staticFile('css/bootstrap.css') ?>"/>


<div class="container">
    <div class="row">
        <div class="panel panel-default" id="box-chart">
            <div class="panel-heading tab" data-status="on">使用情况(日)</div>
            <div class="panel-body">
                <div class="row">
                    <?= F::form('search')->open(null, 'get', ['class' => 'form-inline']) ?>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="start_time" placeholder="开始时间" onClick="WdatePicker()" value="<?= F::session()->get('start_time') ? F::session()->get('start_time') : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="end_time" placeholder="结束时间" onClick="WdatePicker()" value="<?= F::session()->get('end_time') ? F::session()->get('end_time') : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <?php
                         echo F::form('search')->select('biao_id', $biao_arr, ['class' => 'form-control']);
                        ?>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" value="查询"/>
                    </div>
                    <?= F::form()->close(); ?>
                </div>
                <div class="row">
                    <div id="charts-day"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var chart_img = {
        'create': function(time, data, text, date, type) {
            $('#charts-' + time).highcharts({
                title: {
                    text: text,
                    x: -20 //center
                },
                subtitle: {
                    text: '',
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
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
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
    };

    $(function(){
        chart_img.create('day', <?= json_encode($data_day) ?>, '<?= $biao_info['biao_name'] ?>', <?= json_encode($date_day) ?>, <?= $biao_info['type'] ?>);
    });
</script>







<script src="<?= $this->staticFile('highcharts/js/highcharts.js') ?>"></script>
<script src="<?= $this->url('js/My97DatePicker/WdatePicker.js') ?>"></script>