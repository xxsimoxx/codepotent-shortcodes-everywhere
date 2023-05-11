<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Shortcodes Everywhere
 * Description: Enable shortcodes in widgets, excerpts, taxonomy and archive descriptions, and comments.
 * Version: 1.1.2
 * Requires CP: 1.0
 * Requires PHP: 5.6
 * Author: Simone Fioravanti
 * Author URI: https://software.gieffeedizioni.it
 * Plugin URI: https://software.gieffeedizioni.it
 * Text Domain: codepotent-shortcodes-everywhere
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2021, John Alarcon (Code Potent)
 * -----------------------------------------------------------------------------
 * Adopted by Simone Fioravanti, 06/01/2021
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\ShortcodesEverywhere;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

class ShortcodeEnabler {

	/**
	 * Constructor.
	 *
	 * No properties to set; move straight to initialization.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		// Setup all the things.
		$this->init();

	}

	/**
	 * Plugin initialization.
	 *
	 * Register actions and filters to hook the plugin into the system.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {

		// Load constants.
		require_once plugin_dir_path(__FILE__).'includes/constants.php';

		// Load functions.
		require_once (PATH_INCLUDES.'/functions.php');

		// Load update client.
		require_once(PATH_CLASSES.'/UpdateClient.class.php');

		// Load plugin settings class.
		require_once(PATH_CLASSES.'/Settings.class.php');

		// Enable/disable shortcodes, by context.
		add_action('pre_get_posts', [$this, 'process_shortcode_states']);

		// Add a "Settings" link to core's plugin admin row.
		add_filter('plugin_action_links_'.PLUGIN_IDENTIFIER, [$this, 'register_action_links']);

		// Enqueue backend scripts.
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

		// Replace footer text with plugin name and version info.
		add_filter('admin_footer_text', [$this, 'filter_footer_text'], PHP_INT_MAX);

		// Register hooks for plugin activation and deactivation; use $this.
		register_activation_hook(__FILE__, [$this, 'activate_plugin']);
		register_deactivation_hook(__FILE__, [$this, 'deactivate_plugin']);

		// Register hook for plugin deletion; use __CLASS__.
		register_uninstall_hook(__FILE__, [__CLASS__, 'uninstall_plugin']);

	}

	/**
	 * Process shortcode states
	 *
	 * This method enables or disables shortcodes from running.
	 *
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function process_shortcode_states() {

		// Set options.
		$options = get_option(PLUGIN_PREFIX.'_settings', []);

		// Post and page contexts need an extra check; do these first.
		if ((empty($options['page']) && is_page()) ||
			(empty($options['post']) && is_single())) {
				add_filter('the_content', 'strip_shortcodes');
		}

		// Either add shortcode filter or strip shortcodes, for each context.
		foreach (get_shortcode_context_hook_names(true) as $context=>$hook) {
			if (!empty($options[$context])) {
				add_filter($hook, 'shortcode_unautop');
				add_filter($hook, 'do_shortcode', 11);
			} else {
				add_filter($hook, 'strip_shortcodes');
			}
		}

	}

	/**
	 * Add a direct link to the plugin's settings.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Administration links for the plugin.
	 *
	 * @return array $links Updated administration links.
	 */
	public function register_action_links($links) {

		// A link to view the plugin settings.
		$settings_link = '<a href="'.admin_url('options-general.php?page='.PLUGIN_SLUG).'">'.esc_html__('Settings', 'codepotent-shortcodes-everywhere').'</a>';

		// Prepend the above link to the action links.
		array_unshift($links, $settings_link);

		// Return the updated $links array.
		return $links;

	}

	/**
	 * Enqueue CSS
	 *
	 * This method enqueues the style sheet used in the admin interface.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function enqueue_admin_assets() {

		// Enqueue the style sheet on just the plugin's own settings screen.
		if (strpos(get_current_screen()->base, PLUGIN_SLUG)) {
			wp_enqueue_style(PLUGIN_SLUG.'-admin', URL_STYLES.'/admin-settings.css');
		}

	}

	/**
	 * Filter footer text.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param string $text The original footer text.
	 *
	 * @return string Branded footer text if in this plugin's admin.
	 */
	public function filter_footer_text($text) {

		// Update footer text if on this plugin's own screen.
		if (strpos(get_current_screen()->base, PLUGIN_SLUG)) {
			$text = '<span id="footer-thankyou" style="vertical-align:text-bottom;"><a href="'.PLUGIN_AUTHOR_URL.'/" title="'.PLUGIN_DESCRIPTION.'">'.PLUGIN_NAME.'</a> '.PLUGIN_VERSION.' &#8211; by <a href="'.PLUGIN_AUTHOR_URL.'" title="'.VENDOR_TAGLINE.'">'.PLUGIN_AUTHOR.'</a></span>';
		}

		// Return the string.
		return $text;

	}

	/**
	 * Plugin activation.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function activate_plugin() {

		// No permission to activate plugins? Bail.
		if (!current_user_can('activate_plugins')) {
			return;
		}

		// Get any existing settings; preserve.
		$settings = get_option(PLUGIN_PREFIX.'_settings', []);

		// No settings found? Cook up the defaults.
		if (empty($settings)) {
			foreach (array_keys(get_shortcode_contexts()) as $context) {
				$settings[$context] = 1;
			}
		}

		/**
		 * Users of versions prior to 1.0.0 were able to disable shortcodes from
		 * various contexts via filters. This block checks if any filters are in
		 * use and captures those values as counterpart settings for storage.
		 */
		foreach (['widget', 'excerpt', 'term', 'comment'] as $filter) {
			if (has_filter(PLUGIN_PREFIX.'_'.$filter.'s')) {
				$settings[$filter] = 0;
			}
		}

		/**
		 * Since we may have retrieved previously-stored settings, let's just do
		 * one last cleanse to ensure no unexpected values.
		 */
		foreach ($settings as $context=>$boolean) {
			if ($boolean !== 1) {
				$settings[$context] = 0;
			}
		}

		// Store the settings.
		update_option(PLUGIN_PREFIX.'_settings', $settings);

	}

	/**
	 * Plugin deactivation.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function deactivate_plugin() {

		// No permission to activate plugins? None to deactivate either. Bail.
		if (!current_user_can('activate_plugins')) {
			return;
		}

		// Not that there was anything to do here anyway. :)

	}

	/**
	 * Plugin deletion.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function uninstall_plugin() {

		// No permission to delete plugins? Bail.
		if (!current_user_can('delete_plugins')) {
			return;
		}

		// Delete options related to the plugin.
		delete_option(PLUGIN_PREFIX.'_settings');

	}

}

//
new ShortcodeEnabler;
