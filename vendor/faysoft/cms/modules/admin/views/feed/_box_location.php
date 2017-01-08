<div class="box" id="box-seo" data-name="location">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>地理位置信息</h4>
	</div>
	<div class="box-content">
		<div class="form-field">
			<label for="seo-title" class="title">经度</label>
			<?php echo F::form()->inputText('longitude', array(
				'id'=>'seo-title',
				'class'=>'form-control',
			))?>
		</div>
		<div class="form-field">
			<label for="seo-keyword" class="title">纬度</label>
			<?php echo F::form()->inputText('latitude', array(
				'id'=>'seo-keywords',
				'class'=>'form-control',
			))?>
		</div>
		<div class="form-field">
			<label for="seo-description" class="title">地址</label>
			<?php echo F::form()->textarea('address', array(
				'id'=>'seo-description',
				'class'=>'form-control h60 autosize',
			))?>
		</div>
		<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度地图坐标拾取工具</a>
	</div>
</div>