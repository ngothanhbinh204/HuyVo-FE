<?php
// Allow SVG
function quatatheme_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'quatatheme_mime_types');


function register_menu_locations()
{
    register_nav_menu('primary', __('Primary Menu', 'huyvo'));
    register_nav_menu('primary-mobile', __('Primary Menu Mobile', 'huyvo'));
    register_nav_menu('footer-menu-1', __('Footer Menu 1', 'huyvo'));
    register_nav_menu('footer-menu-2', __('Footer Menu 2', 'huyvo'));
    register_nav_menu('menu-policy', __('Policy Menu', 'huyvo'));
}
add_action('after_setup_theme', 'register_menu_locations');
