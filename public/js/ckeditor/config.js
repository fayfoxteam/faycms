/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.extraPlugins = 'code'; //新建插件
	
	config.toolbar = [
		['Source', 'Preview'],
		['Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
		['Link', 'Unlink'],
		['Image', 'Table', 'Smiley', 'Flash', 'Code'],
		['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'Blockquote'],
		'/',
		['Format', 'Styles', 'FontSize'],
		['TextColor', 'BGColor'],
		['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
		['Maximize', 'ShowBlocks'],
		['Find', 'Replace']
	];
	
	//config.uiColor = '#ffffff';
	config.contentsCss = system.url('js/ckeditor/custom.css');
	
	config.allowedContent = true;//不让它自动过滤
	
	config.language = 'zh-cn';
	
	config.skin = 'bootstrapck';
};
