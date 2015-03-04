<div class="col-1">
	<div class="drag_drop_area" id="drag_drop_area">
		<div class="drag_drop_inside">
			<p class="drag_drop_info">将文件拖拽至此</p>
			<p>或</p>
			<p class="drag_drop_buttons">
				<a class="plupload_browse_button btn-2" id="plupload_browse_button">选择文件</a>
			</p>
		</div>
	</div>
	<form class="edit_form">
		<div class="media_list"></div>
	</form>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript">
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,gears,silverlight,browserplus',
	browse_button : 'plupload_browse_button',
	container: 'drag_drop_area',
	drop_element: "drag_drop_area",
	max_file_size : '100mb',
	url : system.url("admin/file/upload"),
	flash_swf_url : system.url()+'flash/plupload.flash.swf',
	silverlight_xap_url : system.url()+'js/plupload.silverlight.xap'
});

uploader.bind('Init', function(up, params) {

});

uploader.init();

uploader.bind('FilesAdded', function(up, files) {
	uploader.start();
});

uploader.bind('UploadProgress', function(up, file) {
	//$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
});

uploader.bind('FileUploaded', function(up, file, response) {
	var rps = $.parseJSON(response.response);
	html = '';
	html += '<div class="media_item">';
	html += '	<img src="'+rps.thumbnail+'" style="float:left;margin:14px 10px 0 0;" />'
	html += '	<table width="620" style="padding:4px;">';
	html += '		<tr>';
	html += '			<td>文件名</td>';
	html += '			<td><input type="text" class="full-width" name="media['+rps.id+'][url]" value="'+rps.client_name+'" readonly="readonly" /></td>';
	html += '		</tr>';
	html += '		<tr>';
	html += '			<td>类型</td>';
	html += '			<td><input type="text" class="full-width" name="media['+rps.id+'][file_type]" value="'+rps.file_type+'" readonly="readonly" /></td>';
	html += '		</tr>';
	html += '		<tr>';
	html += '			<td>大小</td>';
	html += '			<td><input type="text" class="full-width" name="media['+rps.id+'][url]" value="'+rps.image_width+" X "+rps.image_height+'" readonly="readonly" /></td>';
	html += '		</tr>';
	html += '		<tr>';
	html += '			<td>URL</td>';
	html += '			<td><input type="text" class="full-width" name="media['+rps.id+'][url]" value="'+rps.url+'" readonly="readonly" /></td>';
	html += '		</tr>';
	html += '	</table>';
	html += '	<a href="javascript:;" class="delete_file" fid="'+rps.id+'">永久删除</a>';
	html += '	<div class="clear"></div>';
	html += '</div>';
	$(".media_list").append(html);
});

$(".delete_file").on("click", function(){
	var o = this;
	$.ajax({
		type: "GET",
		url: system.url("admin/file/remove"),
		data: "id="+$(this).attr("fid"),
		dataType: 'json',
		success: function(data){
			if(data.status == 1){
				$(o).parent().fadeOut("slow");
			}
		}
	})
})
</script>