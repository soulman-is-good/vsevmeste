/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
config.font_names =
    'Arial/Arial, Helvetica, sans-serif;' +
    'Times New Roman/Times New Roman, Times, serif;' +
    'Verdana;' +
    'cambria, sans-serif;' +
    'edisson, sans-serif;' +
    'deutsch_gothic_2, serif;' + 
    'Philosopher, sans-serif;' +
    'wooden, sans-serif;' +
    'chibrush, sans-serif;' +
    'lombardina, sans-serif;' +
    'diavlo, sans-serif;';
config.extraPlugins = 'sfbrowser,youtube';
config.allowedContent = true;
config.toolbar = 'Custom';
config.toolbar_Custom =[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','youtube','SFbrowser']
	];
config.toolbar_Full =
[
//	{ name: 'document', items : [  ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar','Iframe' ] },
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks' , 'SFbrowser','Keywords','Source','Templates'] }
];
config.contentsCss = '/js/ckeditor/contents.css';
};
