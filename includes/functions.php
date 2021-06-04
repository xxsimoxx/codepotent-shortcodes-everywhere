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

/**
 * Get shortcode contexts.
 *
 * An array of all contexts in which shortcodes can be used and their labels.
 *
 * @author John Alarcon
 *
 * @since 1.0.0
 *
 * @return array Text values for contexts, keyed by context.
 */
function get_shortcode_contexts() {

	return [
		'page'     => esc_html__('Pages', 'codepotent-shortcodes-everywhere'),
		'post'     => esc_html__('Posts', 'codepotent-shortcodes-everywhere'),
		'excerpt'  => esc_html__('Excerpts', 'codepotent-shortcodes-everywhere'),
		'widget'   => esc_html__('Widgets', 'codepotent-shortcodes-everywhere'),
		'term'     => esc_html__('Taxonomies', 'codepotent-shortcodes-everywhere'),
		'comment'  => esc_html__('Comments', 'codepotent-shortcodes-everywhere'),
	];

}

/**
 * Get contextual hook names
 *
 * Each context is linked to a particular filter; this method maps them out.
 *
 * @author John Alarcon
 *
 * @since 1.0.0
 *
 * @param boolean $custom_only Whether to return only custom contexts, or all.
 *
 * @return array The requested contexts.
 */
function get_shortcode_context_hook_names($custom_only=false) {

	// Core contexts where shortcodes can be processed.
	$core_contexts = [
		'page'     => 'the_content',
		'post'     => 'the_content',
	];

	// Custom contexts where shortcodes can be processed.
	$custom_contexts = [
		'excerpt'  => 'the_excerpt',
		'widget'   => 'widget_text',
		'term'     => 'term_description',
		'comment'  => 'comment_text',
	];

	// If only the custom contexts are needed, return early.
	if ($custom_only) {
		return $custom_contexts;
	}

	// Return the array of all contexts.
	return $core_contexts + $custom_contexts;

}

/**
 * Get shortcode context description
 *
 * Retrieve the description of any given shortcode context.
 *
 * @author John Alarcon
 *
 * @since 1.0.0
 *
 * @param string $context Shortcode context.
 *
 * @return string Shortcode context description.
 */
function get_shortcode_context_description($context) {

	// Array of shortcode context descriptions.
	$descriptions = [
		'page'    => esc_html__('Process shortcodes in pages.', 'codepotent-shortcodes-everywhere'),
		'post'    => esc_html__('Process shortcodes in posts and custom post types.', 'codepotent-shortcodes-everywhere'),
		'excerpt' => esc_html__('Process shortcodes in post excerpts.', 'codepotent-shortcodes-everywhere'),
		'widget'  => esc_html__('Process shortcodes in widgets.', 'codepotent-shortcodes-everywhere'),
		'term'    => esc_html__('Process shortcodes in category and tag descriptions.', 'codepotent-shortcodes-everywhere'),
		'comment' => esc_html__('Process shortcodes in user comments.', 'codepotent-shortcodes-everywhere'),
	];

	// Initialization.
	$description = '';

	// Grab the description for the given context, if it exists.
	if (!empty($descriptions[$context])) {
		$description = $descriptions[$context];
	}

	// Return the description value.
	return $description;

}