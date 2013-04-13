<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package ElggTinyMCE
 */

elgg_register_event_handler('init', 'system', 'tinymce_init');

function tinymce_init() {
	elgg_extend_view('css/elgg', 'tinymce/css');
	elgg_extend_view('css/admin', 'tinymce/css');

	elgg_register_js('tinymce', 'mod/tinymce/vendor/tinymce/js/tinymce/tinymce.min.js');
	elgg_register_js('elgg.tinymce', elgg_get_simplecache_url('js', 'tinymce'));
	elgg_register_simplecache_view('js/tinymce');
	
	elgg_extend_view('input/longtext', 'tinymce/init');
	
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
}

function tinymce_get_site_language() {

	if ($site_language = elgg_get_config('language')) {
		$path = elgg_get_plugins_path() . "tinymce/vendor/tinymce/js/tinymce/langs";
		if (file_exists("$path/$site_language.js")) {
			return $site_language;
		}
	}

	return 'en';
}
