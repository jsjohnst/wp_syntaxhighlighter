<?php
/*
Plugin Name: Syntax Highlighter
Plugin URI: http://github.com/jsjohnst/wp_syntaxhighlighter
Description: Syntax highlighting plugin for Wordpress
Version: 0.1-2.0.320
Author: Jeremy Johnstone
Author URI: http://www.jeremyjohnstone.com
*/

function wp_syntaxhighlighter_options() {
	if(isset($_POST["wpsh_form_submit"]) && $_POST["wpsh_form_submit"]) {
		update_option("wpsh_legacy_mode", $_POST["wpsh_legacy_mode"]);
		update_option("wpsh_brushes", $_POST["wpsh_brushes"]);
		update_option("wpsh_theme", $_POST["wpsh_theme"]);
		echo '<div class="updated"><p><strong>Options Saved.</strong></p></div>';
	}
	echo '<div class="wrap">';
	echo '<h2>Syntax Highlighter Options:</h2>';
	echo '<p>For more information on the highlighter options, please visit: <a href="http://alexgorbatchev.com/wiki/SyntaxHighlighter">http://alexgorbatchev.com/wiki/SyntaxHighlighter</a>.';
	echo '<form action="" method="POST"><input type="hidden" name="wpsh_form_submit" value="1" />';
	wp_nonce_field('update-options');
	echo '<table class="form-table">';
	echo '<tr valign="top"><th scope="row">Enable Legacy Mode:</th><td><input type="checkbox" name="wpsh_legacy_mode" value="1" ' . (get_option('wpsh_legacy_mode') == 1 ? "checked" : "") . '></td></tr>';
	$brushes = get_option('wpsh_brushes');
	if(!strlen($brushes)) $brushes = "all";
	echo '<tr valign="top"><th scope="row">Enabled Brushes:</th><td><input type="text" size="20" name="wpsh_brushes" value="' . $brushes . '" /></td></tr>';
	$theme = get_option('wpsh_theme');
	if(!strlen($theme)) $theme = "Midnight";
	echo '<tr valign="top"><th scope="row">Theme:</th><td><input type="text" size="20" name="wpsh_theme" value="' . $theme . '" /></td></tr>';
	echo '</table><input type="hidden" name="action" value="update" /><input type="hidden" name="page_options" value="wpsh_legacy_mode,wpsh_brushes,wpsh_theme" />';
	echo '<p class="submit"><input type="submit" name="Submit" value="Update Options" /></p>';
	echo '</form></div>';
}

function wp_syntaxhighlighter_menu() {
	add_options_page('Syntax Highlighter Settings', 'Syntax Highlighter', 'manage_options', __FILE__, 'wp_syntaxhighlighter_options');
}

add_action('admin_menu', 'wp_syntaxhighlighter_menu');

function wp_syntaxhighlighter_head() {
	$path = dirname(dirname(get_bloginfo("template_url"))) . '/plugins/' . basename(dirname(__FILE__));
	echo '<link type="text/css" rel="Stylesheet" href="' . $path . '/styles/shCore.css"/>';
	
	$theme = get_option('wpsh_theme');
	if(!strlen($theme)) $theme = "Midnight";
	echo '<link type="text/css" rel="Stylesheet" href="' . $path . '/styles/shTheme' . basename($theme) . '.css"/>';
	
	$params = array();
	$legacy = get_option('wpsh_legacy');
	if($legacy == 1) {
		$params["legacy"] = 1;
	}
	$brushes = get_option('wpsh_brushes');
	if(strlen($brushes) && $brushes != "all") {
		$params["brushes"] = $brushes;
	}
	$query_params = "";
	if(count($params)) {
		$query_params = "?";
		foreach($params as $key => $value) {
			$query_params .= $key . "=" . urlencode($value) . "&";
		}
	}
	echo '<script type="text/javascript" src="' . $path . '/script_combiner.php' . $query_params . '"></script>';
	
	echo '<script type="text/javascript">';
	echo 'SyntaxHighlighter.config.clipboardSwf = "' . $path . '/scripts/clipboard.swf";'
	echo 'SyntaxHighlighter.all();';
	echo '</script>';
}

add_action('wp_head', 'wp_syntaxhighlighter_head');

