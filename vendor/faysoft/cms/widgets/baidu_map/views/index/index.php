<?php
/**
 * @var cms\widgets\baidu_map\controllers\IndexController $widget
 */
?>
<?php if($widget->config['ak'] && $widget->config['point']){//若未设置百度密钥或地图中心点，则不显示地图?>
<div id="baidu-map-<?php echo $widget->alias?>" class="baidu-map" style="height:<?php echo empty($widget->config['height']) ? 200 : $widget->config['height']?>px;<?php echo empty($widget->config['width']) ? '' : "width:{$widget->config['width']}px"?>"></div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $widget->config['ak']?>"></script>
<script>
(function(){
    // 百度地图API功能
    var map = new BMap.Map('baidu-map-<?php echo $widget->alias?>');//创建Map实例
    map.centerAndZoom(new BMap.Point(<?php echo $widget->config['point']?>), <?php echo empty($widget->config['zoom_num']) ? 13 : $widget->config['zoom_num']?>);//初始化地图,设置中心点坐标和地图级别
    <?php if(!empty($widget->config['navigation_control'])){?>
        map.addControl(new BMap.NavigationControl());//添加平移缩放控件
    <?php }?>
    <?php if(!empty($widget->config['scale_control'])){?>
        map.addControl(new BMap.ScaleControl());//添加比例尺控件
    <?php }?>
    <?php if(empty($widget->config['enable_scroll_wheel_zoom'])){?>
        map.disableScrollWheelZoom();//禁用滚轮缩放
    <?php }else{?>
        map.enableScrollWheelZoom();//启用滚轮缩放
    <?php }?>
    
    <?php if(!empty($widget->config['marker_point'])){?>
        var marker = new BMap.Marker(new BMap.Point(<?php echo $widget->config['marker_point']?>));//创建标注
        map.addOverlay(marker);// 将标注添加到地图中
    
        <?php if(!empty($widget->config['marker_info'])){?>
            //创建信息窗口
            var infoWindow = new BMap.InfoWindow('<?php echo $widget->config['marker_info']?>');
            marker.addEventListener('click', function(){
                this.openInfoWindow(infoWindow);
            });
        <?php }?>
    <?php }?>
}());
</script>
<?php }?>