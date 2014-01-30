elgg.provide('elgg.tinymce');

elgg.tinymce.toggleEditor = function(event) {
	event.preventDefault();
	
	var target = $(this).attr('href');
    var id = $(target).attr('id');
    var $link = $(this);
    
    tinyMCE.execCommand('mceToggleEditor', false, id);
    if ($link.html() == elgg.echo('tinymce:remove')) {
        $link.html(elgg.echo('tinymce:add'));
    } else {
        $link.html(elgg.echo('tinymce:remove'));
    }
	
}

/**
 * TinyMCE initialization script
 *
 * You can find configuration information here:
 * http://tinymce.moxiecode.com/wiki.php/Configuration
 */
elgg.tinymce.init = function() {
	
	$('.tinymce-toggle-editor').live('click', elgg.tinymce.toggleEditor);
	$('.elgg-input-longtext').parents('form').submit(function() {
		tinyMCE.triggerSave();
	});

	tinyMCE.init({
		mode : "specific_textareas",
		selector : ".elgg-input-longtext",
		theme : "modern",
		language : "<?php echo tinymce_get_site_language(); ?>",
		plugins : "advlist anchor autolink charmap code fullscreen hr image media link lists paste preview searchreplace table visualblocks visualchars",
		image_advtab: true,
		paste_data_images: false,
		relative_urls : false,
		remove_script_host : false,
		convert_urls : false,
		document_base_url : elgg.config.wwwroot,
		statusbar : false,
		menubar : false,
		toolbar1 : "bold italic underline strikethrough | styleselect removeformat | bullist numlist | table | link image media anchor hr | charmap | undo redo | searchreplace visualblocks visualchars code",
		width : "99%",
		//extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		setup : function(ed) {
                    ed.on('Init', function() {
                        var edDoc = ed.getDoc();
                        if ("addEventListener" in edDoc) {
                            edDoc.addEventListener("drop", function(e) {
                                if (e.dataTransfer.files.length > 0) {
                                    e.preventDefault();
                                }
                            }, false);
                        }
                    });
                },
		content_css: elgg.config.wwwroot + 'mod/tinymce/css/elgg_tinymce.css'
	});

	// work around for IE/TinyMCE bug where TinyMCE loses insert carot
	if ($.browser.msie) {
		$(".embed-control").live('hover', function() {
			var classes = $(this).attr('class');
			var embedClass = classes.split(/[, ]+/).pop();
			var textAreaId = embedClass.substr(embedClass.indexOf('embed-control-') + "embed-control-".length);

			if (window.tinyMCE) {
				var editor = window.tinyMCE.get(textAreaId);
				if (elgg.tinymce.bookmark == null) {
					elgg.tinymce.bookmark = editor.selection.getBookmark(2);
				}
			}
		});
	}
}

elgg.register_hook_handler('init', 'system', elgg.tinymce.init);
