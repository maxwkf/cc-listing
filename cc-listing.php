<?php
/**
 * Plugin Name:     CC Listing
 * Plugin URI:      https://cambridgecreative.co.uk
 * Description:     This plugin is going to copy files necessary for cc's listing module.  If those files already exist, it will be skipped to avoid overwriting on reactivation.
 * Author:          Max Wong
 * Author URI:      https://cambridgecreative.co.uk
 * Text Domain:     cc-listing
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Cc_Listing
 */

// Your code starts here.
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!defined('CC_LISTING_PLUGIN_FILE')) {
	define('CC_LISTING_PLUGIN_FILE', __FILE__);
}

if (!defined('CC_LISTING_PLUGIN_DIR')) {
	define('CC_LISTING_PLUGIN_DIR', plugin_dir_path(CC_LISTING_PLUGIN_FILE));
}

if (!defined('CC_LISTING_PLUGIN_URL')) {
	define('CC_LISTING_PLUGIN_URL', plugin_dir_url(CC_LISTING_PLUGIN_FILE));
}

if (!defined('CC_THEME_ROOT')) {
	define('CC_THEME_ROOT', get_theme_root() . '/cc_theme');
}

require CC_LISTING_PLUGIN_DIR . 'includes/CcListing.php';

(new CcListing());
