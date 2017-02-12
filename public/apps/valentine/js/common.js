var common = {
	'toast':function(message, type){
		type = type || 'success';
		system.getScript(system.assets('faycms/js/fayfox.toast.js'), function(){
			if(type == 'success'){
				//成功的提醒5秒后自动消失，不出现关闭按钮，点击则直接消失
				$.toast(message, type, {
					'closeButton': false,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else if(type == 'error'){
				//单页报错，在底部中间出现，红色背景，不显示关闭按钮，点击消失，延迟5秒消失
				$.toast(message, type, {
					'closeButton': false,
					'timeOut': 5000,
					'positionClass': 'toast-bottom-middle',
					'click': function(message){
						message.fadeOut();
					}
				});
			}else{
				//其它类型，点击关闭消失，不自动消失
				$.toast(message, type, {
					'timeOut': 0,
					'positionClass': 'toast-bottom-middle'
				});
			}
		});
	},
	'lightbox': function(){
		if($('[data-lightbox]').length){
			system.getCss(system.assets('css/lightbox/css/lightbox.css'), function(){
				system.getScript(system.assets('js/lightbox.min.js'), function(){
					lightbox.option({
						'albumLabel': '',
						'wrapAround': true
					});
				});
			});
		}
	},
	'init': function(){
		this.lightbox();
	}
};
