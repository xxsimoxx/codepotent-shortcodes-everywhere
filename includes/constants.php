<?php

/**
 * -----------------------------------------------------------------------------
 * Purpose: Namespaced constants for ClassicPress plugins.
 * Author: Code Potent
 * Author URI: https://codepotent.com
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2020, Code Potent
 * -----------------------------------------------------------------------------
 *           ____          _      ____       _             _
 *          / ___|___   __| | ___|  _ \ ___ | |_ ___ _ __ | |_
 *         | |   / _ \ / _` |/ _ \ |_) / _ \| __/ _ \ '_ \| __|
 *         | |__| (_) | (_| |  __/  __/ (_) | ||  __/ | | | |_
 *          \____\___/ \__,_|\___|_|   \___/ \__\___|_| |_|\__|.com
 *
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\ShortcodesEverywhere;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

// Ensure needed functions are present.
require_once(ABSPATH.'wp-admin/includes/file.php');
require_once(ABSPATH.'wp-admin/includes/plugin.php');

// -----------------------------------------------------------------------------
// Plugin basics.
// -----------------------------------------------------------------------------

// Ex: codepotent
const VENDOR_PREFIX = 'codepotent';
// Ex: plugin-folder-name
const PLUGIN_SHORT_SLUG = 'shortcodes-everywhere';
// Ex: Admin Menu Text
define(__NAMESPACE__.'\PLUGIN_MENU_TEXT', esc_html__('Shortcodes', 'codepotent-php-error-log-viewer'));
// Ex: plugin_folder_name
define(__NAMESPACE__.'\PLUGIN_PREFIX', VENDOR_PREFIX.'_'.str_replace('-', '_', PLUGIN_SHORT_SLUG));
// Ex: codepotent-my-plugin-name
const PLUGIN_SLUG = VENDOR_PREFIX.'-'.PLUGIN_SHORT_SLUG;
// Ex: vendor-plugin-name
const PLUGIN_DIRNAME = PLUGIN_SLUG;
// Ex: vendor-plugin-name.php
const PLUGIN_FILENAME = PLUGIN_DIRNAME.'.php';
// Ex: vendor-plugin-name/vendor-plugin-name.php
const PLUGIN_IDENTIFIER = PLUGIN_DIRNAME.'/'.PLUGIN_FILENAME;
// Ex: /site/wp-content/plugins/vendor-plugin-name/vendor-plugin-name.php
const PLUGIN_FILEPATH = WP_PLUGIN_DIR.'/'.PLUGIN_IDENTIFIER;
// Ex: vendor_plugin_name_settings
const PLUGIN_SETTINGS_VAR = PLUGIN_PREFIX.'_settings';
// Get plugin data from header file.
$plugin = get_plugin_data(PLUGIN_FILEPATH, false, false);
// Ex: My Plugin Name
define(__NAMESPACE__.'\PLUGIN_NAME', $plugin['Name']);
// Ex: Some plugin description
define(__NAMESPACE__.'\PLUGIN_DESCRIPTION', $plugin['Description']);
// Ex: 1.2.3
define(__NAMESPACE__.'\PLUGIN_VERSION', $plugin['Version']);
// Ex: Code Potent
define(__NAMESPACE__.'\PLUGIN_AUTHOR', $plugin['AuthorName']);
// Ex: https://codepotent.com
define(__NAMESPACE__.'\PLUGIN_AUTHOR_URL', $plugin['AuthorURI']);
// Ex: https://codepotent.com/classicpress/plugins/
define(__NAMESPACE__.'\PLUGIN_URL', $plugin['PluginURI']);

// -----------------------------------------------------------------------------
// Plugin paths and URLs
// -----------------------------------------------------------------------------

// Ex: /home/user/mysite
define(__NAMESPACE__.'\PATH_HOME', untrailingslashit(get_home_path()));
// Ex: /home/user/mysite/wp-admin
const PATH_ADMIN = PATH_HOME.'/wp-admin';
// Ex: /home/user/mysite/wp-content/plugins
define(__NAMESPACE__.'\PATH_PLUGINS', untrailingslashit(plugin_dir_path(dirname(dirname(__FILE__)))));
// Ex: /home/user/mysite/wp-content/plugins/my-plugin-name
const PATH_SELF = PATH_PLUGINS.'/'.PLUGIN_SLUG;
// Ex: /home/user/mysite/wp-content/plugins/my-plugin-name/classes
const PATH_CLASSES = PATH_SELF.'/classes';
// Ex: /home/user/mysite/wp-content/plugins/my-plugin-name/includes
const PATH_INCLUDES = PATH_SELF.'/includes';
// Ex: https://mysite.com/wp-content/plugins
define(__NAMESPACE__.'\URL_PLUGINS', plugins_url());
// Ex: https://mysite.com/wp-content/plugins/my-plugin-name
const URL_SELF = URL_PLUGINS.'/'.PLUGIN_SLUG;
// Ex: https://mysite.com/wp-content/plugins/my-plugin-name/images
const URL_IMAGES = URL_SELF.'/images';
// Ex: https://mysite.com/wp-content/plugins/my-plugin-name/styles
const URL_STYLES = URL_SELF.'/styles';

// -----------------------------------------------------------------------------
// Branding
// -----------------------------------------------------------------------------

define(__NAMESPACE__.'\VENDOR_TAGLINE', esc_html__('Code Potent is a leading provider of trusted ClassicPress solutions.', 'codepotent-update-manager'));
const VENDOR_HOME_URL        = 'https://codepotent.com';
const VENDOR_PLUGIN_URL      = 'https://codepotent.com/classicpress/plugins/'.PLUGIN_SHORT_SLUG;
const VENDOR_DOCS_URL        = VENDOR_PLUGIN_URL.'/#docs';
const VENDOR_FORUM_URL       = 'https://forums.classicpress.net/c/plugins/plugin-support/67';
const VENDOR_REVIEWS_URL     = VENDOR_PLUGIN_URL.'/#comments';
const VENDOR_REPO_URL        = 'https://github.com/'.VENDOR_PREFIX.'/'.PLUGIN_SHORT_SLUG.'/';
const VENDOR_BLUE            = '337dc1';
const VENDOR_ORANGE          = 'ff6635';
const VENDOR_WORDMARK_URL    = URL_IMAGES.'/code-potent-logotype-wordmark.svg';
const VENDOR_WORDMARK_IMG    = '<img src="'.VENDOR_WORDMARK_URL.'" alt="'.VENDOR_TAGLINE.'" style="max-width:100%;">';
const VENDOR_WORDMARK_LINK   = '<a href="'.VENDOR_HOME_URL.'" title="'.VENDOR_TAGLINE.'">'.VENDOR_WORDMARK_IMG.'</a>';
const VENDOR_LETTERMARK_URL  = URL_IMAGES.'/codepotent-logotype-lettermark.svg';
const VENDOR_LETTERMARK_IMG  = '<img src="'.VENDOR_LETTERMARK_URL.'" alt="'.VENDOR_TAGLINE.'" style="height:1.2em;vertical-align:middle;">';
const VENDOR_LETTERMARK_LINK = '<a href="'.VENDOR_HOME_URL.'" title="'.VENDOR_TAGLINE.'">'.VENDOR_LETTERMARK_IMG.'</a>';
