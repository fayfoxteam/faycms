<?php
use fay\models\Option;
?>
<footer class="g-bottom">
	<div class="footer-row">
		<div class="g-mn clearfix">
			<div class="flogo">
				<a href="<?php echo $this->url()?>"><img src="<?php echo $this->appStatic('images/flogo.png')?>" /></a>
			</div>
			<div class="flocation">
				<h3>我们的位置</h3>
				<div id="location-map"></div>
			</div>
			<div class="fcontact">
				<h3>联系我们</h3>
				<div class="fcontacts">
					<table>
						<?php F::app()->widget->load('contacts')?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright-row">
		<div class="g-mn">
			<p class="fl"><?php echo Option::get('site:copyright')?></p>
			<p class="fr"><?php echo Option::get('site:beian')?></p>
			<br class="clear" />
		</div>
	</div>
</footer>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=10b033765ad00c668fcdd20902dab530"></script>
<script type="text/javascript">

// 百度地图API功能
var map = new BMap.Map("location-map");// 创建Map实例
map.centerAndZoom(new BMap.Point(120.202605, 30.212051), 13);// 初始化地图,设置中心点坐标和地图级别
map.addControl(new BMap.ScaleControl());// 添加比例尺控件
map.enableScrollWheelZoom();//启用滚轮放大缩小

var marker1 = new BMap.Marker(new BMap.Point(120.202605, 30.212051));// 创建标注
map.addOverlay(marker1);// 将标注添加到地图中

//创建信息窗口
var infoWindow1 = new BMap.InfoWindow("<?php echo Option::get('site:sitename')?>");
marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
</script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>