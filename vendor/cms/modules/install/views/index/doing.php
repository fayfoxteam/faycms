<p>正在进行安装，<span class="fc-red">请勿关闭或刷新页面</span>...</p>
<ol id="install-panel"></ol>
<script>
var install = {
	'createTables':function(){
		$("#install-panel").append('<li>创建数据表<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/create-tables'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCities();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCities':function(){
		$("#install-panel").append('<li>导入城市数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-cities'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setRegions();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setRegions':function(){
		$("#install-panel").append('<li>导入地区数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-regions'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCats();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCats':function(){
		$("#install-panel").append('<li>导入基础分类数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-cats'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setActions();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setActions':function(){
		$("#install-panel").append('<li>导入权限数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-actions'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setSystem();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setActions':function(){
		$("#install-panel").append('<li>导入后台菜单<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-menus'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setSystem();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setSystem':function(){
		$("#install-panel").append('<li>导入系统数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-system'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.setCustom();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'setCustom':function(){
		$("#install-panel").append('<li>导入用户数据<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/set-custom'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					install.indexCats();
				}else{
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-red">[失败：'+resp.message+']</span>')
				}
			}
		});
	},
	'indexCats':function(){
		$("#install-panel").append('<li>索引表<span class="throbber"><img src="'+system.url('images/throbber.gif')+'" /></span></li>');
		$.ajax({
			type: "GET",
			url: system.url('install/db/index-cats'),
			dataType: "json",
			cache: false,
			success: function(resp){
				if(resp.status){
					$("#install-panel li:last .throbber").replaceWith('<span class="fc-green">[完成]</span>')
					window.location.href = system.url('install/index/settings');
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