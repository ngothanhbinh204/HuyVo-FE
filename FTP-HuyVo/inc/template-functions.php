<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package huyvo
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function huyvo_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	if (is_page_template('templates/template_about.php')) {
		$classes[] = 'page-black page-about';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'huyvo_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function huyvo_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'huyvo_pingback_header');
