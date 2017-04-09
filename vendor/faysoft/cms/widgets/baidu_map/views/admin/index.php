<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">百度地图密钥（AK）</label>
            <?php echo F::form('widget')->inputText('ak', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">使用百度地图前，需要先去百度地图官网申请一个密钥。</p>
            <p class="fc-grey">请登录<a href="http://developer.baidu.com/map/" target="_blank">百度地图开放平台</a>获取。</p>
        </div>
        <div class="form-field">
            <label class="title bold">地图中心经纬度</label>
            <?php echo F::form('widget')->inputText('point', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">经纬度用逗号隔开，例如<code>120.29229,30.312906</code></p>
            <p class="fc-grey">可用<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度地图坐标拾取工具</a>获取。</p>
        </div>
        <div class="form-field">
            <label class="title bold">地图宽度</label>
            <?php echo F::form('widget')->inputText('width', array(
                'class'=>'form-control mw100',
            ))?>
            <p class="fc-grey">地图在网页上显示的宽度（单位：像素）</p>
            <p class="fc-grey">也可以留空，在css中设置</p>
        </div>
        <div class="form-field">
            <label class="title bold">地图高度</label>
            <?php echo F::form('widget')->inputText('height', array(
                'class'=>'form-control mw100',
            ), 200)?>
            <p class="fc-grey">地图在网页上显示的高度（单位：像素）</p>
            <p class="fc-grey">也可以留空，在css中设置。但若均未设置的话，高度为0，地图将无法显示。</p>
        </div>
        <div class="form-field">
            <label class="title bold">地图级别</label>
            <?php
                $zoom_nums = array();
                for($i = 1; $i <= 19; $i++){
                    $zoom_nums[$i] = $i;
                }
                echo F::form('widget')->select('zoom_num', $zoom_nums, array(
                    'class'=>'form-control mw100',
                ), 13);
            ?>
            <p class="fc-grey">可选1-19级，一般设置在11-15之间效果比较好</p>
        </div>
        <div class="form-field">
            <label class="title bold">是否启用滚轮缩放</label>
            <?php echo F::form('widget')->inputRadio('enable_scroll_wheel_zoom', 1, array(
                'label'=>'是',
            ), true)?>
            <?php echo F::form('widget')->inputRadio('enable_scroll_wheel_zoom', 0, array(
                'label'=>'否',
            ))?>
            <p class="fc-grey">通过鼠标滚轮缩放地图</p>
        </div>
        <div class="form-field">
            <label class="title bold">是否显示平移缩放控件</label>
            <?php echo F::form('widget')->inputRadio('navigation_control', 1, array(
                'label'=>'是',
            ), true)?>
            <?php echo F::form('widget')->inputRadio('navigation_control', 0, array(
                'label'=>'否',
            ))?>
            <p class="fc-grey">缩放控件在地图左上角</p>
        </div>
        <div class="form-field">
            <label class="title bold">是否显示比例尺</label>
            <?php echo F::form('widget')->inputRadio('scale_control', 1, array(
                'label'=>'是',
            ), true)?>
            <?php echo F::form('widget')->inputRadio('scale_control', 0, array(
                'label'=>'否',
            ))?>
            <p class="fc-grey">比例尺在地图的左下角</p>
        </div>
        <div class="form-field">
            <label class="title bold">标注点经纬度</label>
            <?php echo F::form('widget')->inputText('marker_point', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">同地图中心经纬度格式，用逗号隔开，例如<code>120.29229,30.312906</code></p>
            <p class="fc-grey">若留空，则不显示标注点。</p>
            <p class="fc-grey">可用<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度地图坐标拾取工具</a>获取。</p>
        </div>
        <div class="form-field">
            <label class="title bold">标注点弹窗信息</label>
            <?php echo F::form('widget')->inputText('marker_info', array(
                'class'=>'form-control mw400',
            ))?>
            <p class="fc-grey">点击标注点弹出描述窗。若留空，点击无效果。</p>
        </div>
    </div>
</div>