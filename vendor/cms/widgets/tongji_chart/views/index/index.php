<?php
use fay\helpers\Html;
?>
<div class="box" id="box-tongji-chart" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>访问统计（详细）</h4>
	</div>
	<div class="box-content">
		<div class="mb5">
			<?php echo Html::inputRadio('chart_by', 'pv', true, array(
				'label'=>'浏览量(PV)',
			))?>
			<?php echo Html::inputRadio('chart_by', 'uv', false, array(
				'label'=>'访客数(UV)',
			))?>
			<?php echo Html::inputRadio('chart_by', 'ip', false, array(
				'label'=>'IP数',
			))?>
			<?php echo Html::inputRadio('chart_by', 'new_visitors', false, array(
				'label'=>'新访客',
			))?>
		</div>
		<div class="h200" style="height:300px;" id="visit-chart"></div>
		<div class="clear"></div>
	</div>
</div>

<script src="<?php echo $this->url()?>js/highcharts.js"></script>
<script>
var tongji_chart = {
	'obj':null,
	'create':function(today, yesterday, today_total, yesterday_total){
		$('#visit-chart').highcharts({
			'chart': {
				'type': 'line',
				'marginTop': 40
			},
			'colors': ['#D12610', '#37B7F3'],
			'title': {
				'text': null
			},
			'xAxis': {
				'labels': {
					'step': 2
				},
				'categories': (function(){
					var categories = [];
					for(var i = 0; i < 24; i++){
						categories.push(i);
					}
					return categories;
				})()
			},
			'yAxis': {
				'title': {
					'text': null
				},
				'min': 0,
				'opposite': true
			},
			'plotOptions': {
				'series': {
					'cursor': 'pointer',
					'marker': {
						'lineWidth': 1
					}
				}
			},
			'legend': {
				'align': 'center',
				'verticalAlign': 'top',
				'y': 0,
				'floating': true,
				'borderWidth': 0
			},
			'tooltip': {
				'shared': true,
				'crosshairs': true
			},
			'series': [{
				'name': '昨天',
				'lineWidth': 1,
				'data': yesterday
			}, {
				'name': '今天',
				'lineWidth': 1,
				'data': today
			}, {
				'type': 'pie',
				'name': '共计',
				'data': [{
					'name': '昨天',
					'y': yesterday_total
				}, {
					'name': '今天',
					'y': today_total
				}],
				'center': [50, 40],
				'size': 80,
				'showInLegend':false,
				'dataLabels': {
					'enabled': true,
					'distance': -19,
					'style': {
						'fontSize': '10px',
						'lineHeight': '10px',
						'color':'#FFFFFF'
					},
					'format': '<b>{point.options.y}</b>'
				},
				'allowPointSelect': true
			}],
			'credits': {
				'enabled':false
			}
		});

		tongji_chart.obj = $('#visit-chart').highcharts();
	},
	'update':function(today, yesterday){
		tongji_chart.obj.series[0].setData(today);
		tongji_chart.obj.series[1].setData(yesterday);
	},
	'events':function(){
		$("#box-tongji-chart").on('click', "[name='chart_by']", function(){
			$("#box-tongji-chart").block();
			$.ajax({
				type: "GET",
				url: system.url("admin/widget/render"),
				data: {
					'name':'cms/tongji_chart',
					'action':'get-data',
					't':$(this).val()
				},
				dataType: "json",
				cache: false,
				success: function(resp){
					$("#box-tongji-chart").unblock();
					if(resp.status){
						tongji_chart.create(resp.today, resp.yesterday, resp.today_total, resp.yesterday_total);
					}
				}
			});
		});
	}
};
$(function(){
	tongji_chart.create(<?php echo json_encode($today), ',', json_encode($yesterday), ',', $today_total, ',', $yesterday_total?>);
	tongji_chart.events();
});
</script>