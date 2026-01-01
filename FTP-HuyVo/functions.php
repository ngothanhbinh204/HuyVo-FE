<?php

/**
 * huyvo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package huyvo
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function huyvo_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on huyvo, use a find and replace
		* to change 'huyvo' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('huyvo', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	// register_nav_menus(
	// 	array(
	// 		'menu-1' => esc_html__('Primary', 'huyvo'),
	// 	)
	// );

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'huyvo_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'huyvo_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function huyvo_content_width()
{
	$GLOBALS['content_width'] = apply_filters('huyvo_content_width', 640);
}
add_action('after_setup_theme', 'huyvo_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function huyvo_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'huyvo'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'huyvo'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'huyvo_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function huyvo_scripts()
{
	wp_enqueue_style('huyvo-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('huyvo-style', 'rtl', 'replace');

	wp_enqueue_script('huyvo-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
// add_action('wp_enqueue_scripts', 'huyvo_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}


/*
 * Enqueue scripts and styles.
 */
function quatatheme_scripts()
{

	/**
	 * Script
	 */
	wp_enqueue_script('front-end-global', get_template_directory_uri() . '/scripts/core.min.js', '', '', true);
	wp_enqueue_script('front-end-main', get_template_directory_uri() . '/scripts/main.min.js', '', '', true);
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/scripts/custom.js', '', '', true);

	$text_checkout = array(
		'select_province' => __('Chọn Tỉnh / Thành', 'huyvo'),
		'select_district' => __('Chọn Quận / Huyện', 'huyvo'),
		'select_ward' => __('Chọn Phường / Xã', 'huyvo'),
	);
	wp_localize_script('custom-script', 'text_checkout', $text_checkout);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_enqueue_script('front-end-blockui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js', '', '', true);
}
add_action('wp_enqueue_scripts', 'quatatheme_scripts', 1);
function quatatheme_styles()
{
	// wp_enqueue_style('quatatheme-style', get_stylesheet_uri(), array(), _S_VERSION);
	// wp_style_add_data('quatatheme-style', 'rtl', 'replace');

	// wp_enqueue_script('quatatheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
	/**
	 * Styles
	 */

	wp_enqueue_style('frontend-style-global', get_template_directory_uri() . '/styles/core.min.css', array(), _S_VERSION);
	wp_enqueue_style('frontend-style-main', get_template_directory_uri() . '/styles/main.min.css', array(), _S_VERSION);
	wp_enqueue_style('frontend-style-custom', get_template_directory_uri() . '/styles/custom.css', array(), _S_VERSION);
	wp_enqueue_style('frontend-style-woo', get_template_directory_uri() . '/styles/woo-styles/woo-style.css', array(), _S_VERSION);
}
add_action('wp_enqueue_scripts', 'quatatheme_styles');

/**
 * Function setup.
 */

require get_template_directory() . '/inc/function-setup.php';
/**
 * Function Custom.
 */

require get_template_directory() . '/inc/function-custom.php';

/**
 * Function Woo.
 */

require get_template_directory() . '/inc/function-woo.php';


/**
 * Function Custom Menu.
 */

require get_template_directory() . '/inc/function-custom-menu.php';

/**
 * Function Widget.
 */

/**
 * Function ACF.
 */

require get_template_directory() . '/inc/acf-example-field-type-main/select-attribute/init.php';

/**
 * Function Cities.
 */

require get_template_directory() . '/cities/tinh_thanhpho.php';
require get_template_directory() . '/cities/quan_huyen.php';
require get_template_directory() . '/cities/xa_phuong_thitran.php';
