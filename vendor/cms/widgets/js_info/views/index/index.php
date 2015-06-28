<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>JS Analyst</h4>
	</div>
	<div class="box-content">
		<table class="form-table">
			<tr>
				<th>document.referrer</th>
				<td><span id="document-referrer"></span></td>
			</tr>
			<tr>
				<th>window.screen.width</th>
				<td><span id="window-screen-width"></span></td>
			</tr>
			<tr>
				<th>window.screen.height</th>
				<td><span id="window-screen-height"></span></td>
			</tr>
			<tr>
				<th>navigator.userAgent</th>
				<td><span id="navigator-userAgent"></span></td>
			</tr>
			<tr>
				<th>navigator.platform</th>
				<td><span id="navigator-platform"></span></td>
			</tr>
			<tr>
				<th>navigator.appVersion</th>
				<td><span id="navigator-appVersion"></span></td>
			</tr>
			<tr>
				<th>window.location.href</th>
				<td><span id="window-location-href"></span></td>
			</tr>
			<tr>
				<th>document.documentElement.clientWidth</th>
				<td><span id="document-documentElement-clientWidth"></span></td>
			</tr>
			<tr>
				<th>document.documentElement.clientHeight</th>
				<td><span id="document-documentElement-clientHeight"></span></td>
			</tr>
			<tr>
				<th>browser</th>
				<td><span id="fa-browser"></span></td>
			</tr>
			<tr>
				<th>os</th>
				<td><span id="fa-os"></span></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>faycms/js/analyst.min.js"></script>
<script>
$("#document-referrer").text(document.referrer);
$("#window-screen-width").text(window.screen.width);
$("#window-screen-height").text(window.screen.height);
$("#navigator-userAgent").text(navigator.userAgent);
$("#navigator-platform").text(navigator.platform);
$("#navigator-appVersion").text(navigator.appVersion);
$("#window-location-href").text(window.location.href);
$("#document-documentElement-clientWidth").text(document.documentElement.clientWidth);
$("#document-documentElement-clientHeight").text(document.documentElement.clientHeight);
$(function(){
	var browser = _fa.getBrowser();
	$("#fa-browser").text(browser[0] + '/' + browser[1]);
	$("#fa-os").text(_fa.getOS());
});
</script>