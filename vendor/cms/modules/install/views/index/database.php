<p>正在进行安装，<span class="fc-red">请勿关闭或刷新页面</span>...</p>
<ol id="install-panel"></ol>
<script>
var install = {
	'createTables':function(){
		$("#install-panel").append('<li>创建数据表<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/create-tables', {'_token':'<?php echo F::app()->getToken()?>'}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCities(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCities':function(token){
		$("#install-panel").append('<li>导入城市数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-cities', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setRegions(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setRegions':function(token){
		$("#install-panel").append('<li>导入地区数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-regions', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCats(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCats':function(token){
		$("#install-panel").append('<li>导入基础分类数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-cats', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setActions(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setActions':function(token){
		$("#install-panel").append('<li>导入权限数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-actions', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setMenus(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setMenus':function(token){
		$("#install-panel").append('<li>导入后台菜单<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-menus', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setSystem(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setSystem':function(token){
		$("#install-panel").append('<li>导入系统数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-system', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCustom(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCustom':function(token){
		$("#install-panel").append('<li>导入用户数据<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-custom', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.indexCats(resp._token);
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'indexCats':function(token){
		$("#install-panel").append('<li>索引表<span class="throbber"><img src="'+system.assets('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/index-cats', {
				'_token':token
			}),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					window.location.href = system.url('install/index/settings', {
						'_token':resp._token
					});
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	}
};
$(function(){
	install.createTables();
});
</script>