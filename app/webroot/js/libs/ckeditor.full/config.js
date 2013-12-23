/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	config.skin = 'flat';
	
	config.toolbar = [
		['Bold','Italic','Underline','-','Outdent','Indent'], 
		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight'],
		['Link','Source']
	];
	
	// Removes all formatting from pasted text (Works in IE, Safari, Firefox, etc.)
	config.forcePasteAsPlainText = true;
	
	// Prevent filler nodes in all empty blocks. (ie, prevent <p> &nbsp; </p>)
	config.fillEmptyBlocks = false;	
	
	// Allow all HTML/HTML5 tags
	config.allowedContent = true;
	
	// Default height
	config.height = 100;
	
	// Add Autogrow plugin
	config.extraPlugins = 'autogrow';
	config.autoGrow_minHeight = 100;
	config.autoGrow_maxHeight = 600;
	config.autoGrow_onStartup = true;
		
};
