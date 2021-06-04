<?php

/**
 * -----------------------------------------------------------------------------
 * Name: Shortcodes Everywhere
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2021, John Alarcon (Code Potent)
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\ShortcodesEverywhere;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

class Settings {

	/**
	 * Plugin settings.
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Constructor.
	 *
	 * Set options to object and move on.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		// Set options.
		$this->options = get_option(PLUGIN_PREFIX.'_settings');

		// Setup all the things.
		$this->init();

	}

	/**
	 * Plugin initialization
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

		// Register settings.
		add_action('admin_init', [$this, 'register_settings']);

		// Register admin menu.
		add_action('admin_menu', [$this, 'register_admin_menu']);

	}

	/**
	 * Register admin menu.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_admin_menu() {

		// Add page as a submenu of the core Settings menu.
		add_options_page(
			PLUGIN_NAME,
			PLUGIN_MENU_TEXT,
			'manage_options',
			PLUGIN_SLUG,
			[$this, 'render_settings_page']
		);

	}

	/**
	 * Register settings sections/options.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_settings() {

		// Register settings variable.
		register_setting(PLUGIN_PREFIX.'_settings', PLUGIN_PREFIX.'_settings');

		// Register a settings section.
		add_settings_section(
			PLUGIN_PREFIX.'_enabled_contexts',
			null,
			null,
			PLUGIN_PREFIX.'_settings'
			);

		// Get contexts in which shortcodes can be enabled/disabled.
		$contexts = get_shortcode_contexts();

		// Add a labelled field for each context.
		foreach ($contexts as $context=>$text) {
			add_settings_field(
				'enable_'.$context,
				'<label for="'.PLUGIN_PREFIX.'_'.$context.'_enabled">'.$text.'</label>',
				[$this, 'render_input_pair'],
				PLUGIN_PREFIX.'_settings',
				PLUGIN_PREFIX.'_enabled_contexts',
				['context'=>$context]
			);
		}

	}

	/**
	 * Render input pair
	 *
	 * This method renders the enable/disable input pair for any given shortcode
	 * context; radio buttons, populated.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Array of arbitrary arguments passed in.
	 *
	 * @return void
	 */
	public function render_input_pair($args) {

		// No context? Or context not whitelisted? Bail.
		if (empty($args['context']) || !array_key_exists($args['context'], get_shortcode_contexts())) {
			return;
		}

		// Container.
		echo '<p>';

		// Enable.
		$checked = !empty($this->options[$args['context']]) ? checked(1, 1, 0) : '';
		echo '<label>';
		echo '<input type="radio" id="'.PLUGIN_PREFIX.'_'.$args['context'].'_enabled" name="'.PLUGIN_PREFIX.'_settings['.$args['context'].']" value="1" '.$checked.'>';
		echo esc_html__('Enable', 'codepotent-shortcodes-everywhere');
		echo '</label>';

		// Disable.
		$checked = !isset($this->options[$args['context']]) || empty($this->options[$args['context']]) ? checked(0, 0, 0) : '';
		echo '<label>';
		echo '<input type="radio" id="'.PLUGIN_PREFIX.'_'.$args['context'].'_disabled" name="'.PLUGIN_PREFIX.'_settings['.$args['context'].']" value="0" '.$checked.'>';
		echo esc_html__('Disable', 'codepotent-shortcodes-everywhere');
		echo '</label>';

		// Container.
		echo '</p>';

		// Description; is escaped.
		echo '<p class="description">'.get_shortcode_context_description($args['context']).'</p>';

	}

	/**
	 * Render settings page.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_settings_page() {

		// Container.
		echo '<div id="'.PLUGIN_SLUG.'" class="wrap">';

		// Primary heading.
		echo '<h1>'.esc_html__('Shortcode Settings', 'codepotent-shortcodes-everywhere').'</h1>';

		// Options form open.
		echo '<form action="options.php" method="post">';

		// Print settings fields.
		settings_fields(PLUGIN_PREFIX.'_settings');

		// Print sections.
		do_settings_sections(PLUGIN_PREFIX.'_settings');

		// Save button.
		submit_button();

		// Close the form.
		echo '</form>';

		// Container.
		echo '</div><!-- #'.PLUGIN_SLUG.'.wrap -->';

	}

}

// Go!
new Settings;