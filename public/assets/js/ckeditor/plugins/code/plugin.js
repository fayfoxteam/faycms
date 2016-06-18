CKEDITOR.plugins.add('code',
	{		
		requires : ['dialog'],
		init : function (editor)
		{
			var pluginName = 'code';
			
			//加载自定义窗口，就是dialogs前面的那个/让我纠结了很长时间
			CKEDITOR.dialog.add('Code', this.path + '/dialogs/code.js');
			
			//给自定义插件注册一个调用命令
			editor.addCommand( pluginName, new CKEDITOR.dialogCommand('Code'));
			
			//注册一个按钮，来调用自定义插件
			editor.ui.addButton('Code', {
				label : '插入程序代码',
				command : pluginName,
				icon: this.path + 'images/code.png'
			});
		}
	}
);