var goods = {
	'skus':{},

	'events':function(){
		$('.sku-quantity').live('blur', function(){
			goods.getTotalNum();
		});
	},
	'tabs':function(){//tab页
		$('.tabs .tab-content div.tab-item:gt(0)').hide();
		$('.tabs .tab-nav a').die().live('click', function(){
			$($(this).attr('href')).show().siblings().hide();
			$(this).addClass('sel').parent().siblings().find('a').removeClass('sel');
			
			if($($(this).attr('href')).find('.lazy-kindeditor').size() > 0){
				app['tab'+$(this).attr('href').substr(5,1)+'_editor'] = KindEditor.create($(this).attr('href')+' .lazy-kindeditor', {
					width: '100%',
					allowFileManager : true,
					filterMode : false,
					formatUploadUrl : false,
					uploadJson : system.url('admin/file/upload', {'cat':'goods'})
				});
				$($(this).attr('href')+' .lazy-kindeditor').removeClass('lazy-kindeditor');
			}
			
			return false;
		});
	},
	'thumbnail':function(){
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,html4,flash,silverlight,browserplus',
			browse_button : 'upload_thumbnail',
			container : 'container',
			max_file_size : '10mb',
			url : system.url('admin/file/upload', {'cat':'goods'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,bmp,jpeg'}
			]
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files) {
			uploader.start();
		});
		
		uploader.bind('Error', function(up, error) {
			alert(error.message);
		});

		uploader.bind('FileUploaded', function(up, file, response) {
			var rps = $.parseJSON(response.response);
			$('#thumbnail-preview').attr('src', rps.thumbnail).show();
			$('#thumbnail-id').val(rps.id);
		});
	},
	'gallery':function(){
		system.getScript(system.assets('js/jquery.dragsort-0.5.1.js'), function(){
			$('.photo-list').dragsort({
				itemSelector: 'div.photo-item',
				placeHolderTemplate: '<div class="photo-item holder"></div>'
			});
		});
		//文件上传
		var gallery_uploader = new plupload.Uploader({
			runtimes : 'html5,html4,flash,gears,silverlight,browserplus',
			browse_button : 'upload-photo-link',
			container: 'upload-photo-container',
			max_file_size : '2mb',
			url : system.url('admin/file/upload', {'cat':'goods'}),
			flash_swf_url : system.url()+'flash/plupload.flash.swf',
			silverlight_xap_url : system.url()+'js/plupload.silverlight.xap',
			filters : [
				{title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
			]
		});

		gallery_uploader.init();

		gallery_uploader.bind('FilesAdded', function(up, files) {
			gallery_uploader.start();
		});

		gallery_uploader.bind('UploadProgress', function(up, file) {
			//console.log(file.percent);
		});

		gallery_uploader.bind('FileUploaded', function(up, file, response) {
			var rps = $.parseJSON(response.response);
			var html = '';
			html += '<div class="photo-item">';
			html += '	<input type="hidden" name="photos[]" value="'+rps.id+'" />';
			html += '	<a class="photo-rm" href="javascript:;" pid="'+rps.id+'"></a>';
			html += '	<span class="photo-thumb">';
			html += '		<a href="'+rps.url+'" class="photo-thumb-link">';
			html += '			<img src="'+rps.thumbnail+'">';
			html += '		</a>';
			html += '	</span>';
			html += '	<div class="photo-desc-container">';
			html += '		<textarea class="photo-desc" name="desc['+rps.id+']" placeholder="照片描述" maxlength="240"></textarea>';
			html += '	</div>';
			html += '	<div class="clear"></div>';
			html += '</div>';
			$(".photo-list").append(html);

			$(".photo-list").dragsort("destroy");
			$(".photo-list").dragsort({
				'itemSelector' : "div.photo-item",
				'placeHolderTemplate' : '<div class="photo-item holder"></div>'
			});
			$(".photo-thumb-link").fancybox({
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'elastic',
				'type' : 'image',
				'padding' : 0
			});
		});
		
		//删除图片
		$(".photo-rm").die().live("click", function(){
			$(this).parent().fadeOut("slow", function(){
				$(this).remove();
			});
		});
	},
	'sku':function(){
		//销售属性选中
		$(".sku-item [type='checkbox']").click(function(){
			if($(this).attr("checked")){
				$(this).next("label").hide();
				$(this).siblings("input[type='text']").show();
			}else{
				$(this).next("label").show();
				$(this).siblings("input[type='text']").hide();
			}
			goods.showSkuTable();
			goods.getTotalNum();
		});
	},
	'props':function(){
		//普通属性选中
		//复选框
		$(".goods-prop-list [type='checkbox']").click(function(){
			if($(this).attr("checked")){
				$(this).next("label").hide();
				$(this).siblings("input[type='text']").show();
			}else{
				$(this).next("label").show();
				$(this).siblings("input[type='text']").hide();
			}
		});

		//单选框
		$(".goods-prop-list [type='radio']").click(function(){
			var name = $(this).attr("name");
			$(".goods-prop-list [name='"+name+"']").each(function(){
				if($(this).attr("checked")){
					$(this).next("label").hide();
					$(this).siblings("input[type='text']").show();
				}else{
					$(this).next("label").show();
					$(this).siblings("input[type='text']").hide();
				}
			});
		});
	},
	'getTotalNum':function(){
		if($(".sku-quantity").size()){
			//计算宝贝总数
			var total_num = 0;
			$(".sku-quantity").each(function(){
				total_num += $(this).val() ? parseInt($(this).val()) : 0;
			});
			$("#sku-total-num").val(total_num);
			$("#sku-total-num").attr("readonly", "readonly");
		}else{
			$("#sku-total-num").removeAttr("readonly");
		}
	},
	'showSkuTable':function(){
		$("#sku-table-container").html("");
		var cats = [];
		var flag = false;
		//获取所有被选中的销售属性值
		$(".sku-group").each(function(){
			var pid = $(this).attr("data-pid");
			var name = $(this).attr("data-name");
			var props = [];
			if(!$(this).find("input[type='checkbox'][name^='cp_sale']:checked").length){
				flag = true;
			}
			$(this).find("input[type='checkbox'][name^='cp_sale']:checked").each(function(){
				props.push({
					"value":$(this).val(),
					"alias":$(this).siblings("input[type='text']").val()
				});
			});
			cats.push({
				"pid" : pid,
				"name" : name,
				"props" : props
			});
		});
		if(flag)return false;
		//console.log(cats);

		var table_rows = 1;//表格行数
		var table = [];
		var counter = [];//计数器，记录当前执行到的属性和需要跳过的行数
		var html = '<table class="sku-edit-table">';
		html += '<tr>';
		$.each(cats, function(i, n){
			//构建表头
			html += '	<th>'+n.name+'</th>';

			//计算表格行数
			table_rows *= n.props.length;

			//计算每个属性的rowspan数
			var rowspan = 1;
			for(var j = i + 1; j < cats.length; j++){
				rowspan = rowspan * cats[j].props.length;
			}
			n.rowspan = rowspan;

			//初始化skips值
			counter.push({
				'current':0,
				'skip':0
			});
		});
		html += '	<th>价格</th>';
		html += '	<th>数量</th>';
		html += '	<th>商家编码</th>';
		html += '</tr>';
		//console.log(counter);
		//console.log(cats);
		//获取表格数组
		for(j = 0; j < table_rows; j++){
			table[j] = {
				'path':[],
				'tds':[]
			};
			for(i = 0; i < cats.length; i++){
				if(counter[i].skip > 0){
					//还处在rowspan合并列中
					table[j]['path'][i] = cats[i].pid + ':' + cats[i].props[counter[i].current - 1].value;
					counter[i].skip--;
					continue;
				}else{
					//当同辈兄元素发生改变，所以弟元素角标重置为0
					for(k = i + 1; k < counter.length; k++){
						counter[k].current = 0;
					}
				}
				counter[i].skip = cats[i].rowspan - 1;
				//table[j].push(cats[i].props[counter[i].current]);
				table[j]['path'][i] = cats[i].pid + ':' + cats[i].props[counter[i].current].value;
				table[j]['tds'].push({
					'value':cats[i].props[counter[i].current].value,
					'alias':cats[i].props[counter[i].current].alias,
					'rowspan':cats[i].rowspan ? cats[i].rowspan : 1
				});
				counter[i].current++;
			}
		}
		//console.log(table);
		//生成表格
		$.each(table, function(i, data){
			html += '<tr>';
			$.each(data.tds, function(j, data2){
				html += '	<td rowspan="'+data2.rowspan+'">'+data2.alias+'</td>';
			});

			var path = data.path.join(';');
			var price = (goods.skus[path]) ? goods.skus[path].price : $("#sku-price").val();
			var quantity = (goods.skus[path]) ? goods.skus[path].quantity : 1;
			var tsces = (goods.skus[path]) ? goods.skus[path].tsces : '';
			
			html += '	<td><input type="text" name="prices['+path+']" value="'+price+'" class="text-short" data-rule="float" data-params="{\'langth\':8,decimal:2}" data-label="sku价格" /></td>';
			html += '	<td><input type="text" name="quantities['+path+']" value="'+quantity+'" class="text-short sku-quantity" data-rule="int" data-label="sku数量" /></td>';
			html += '	<td><input type="text" name="tsces['+path+']" value="'+tsces+'" class="text-short" /></td>';			
			html += '</tr>';
		});

		html += '</table>';
		$("#sku-table-container").html(html);
	},
	'init':function(){
		this.tabs();
		this.events();
		this.thumbnail();
		this.gallery();
		this.props();
		this.sku();
		this.showSkuTable();
		this.getTotalNum();
	}
};