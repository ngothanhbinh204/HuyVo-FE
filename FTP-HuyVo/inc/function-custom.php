<?php
class Walker_Menu_Policy extends Walker_Nav_Menu
{

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        // Không cần ul con
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $class_names = implode(' ', $classes);

        $active = in_array('current-menu-item', $classes) ? ' current' : '';

        $output .= '<li class="menu-item' . esc_attr($active) . '">';

        $atts  = ' class="btn-hover text-only"';
        $atts .= ' href="' . esc_url($item->url) . '"';

        $title = esc_html($item->title);

        $output .= '<a' . $atts . '>';
        $output .= '<span class="btn-inner">';
        $output .= '<span class="text main-text">' . $title . '</span>';
        $output .= '<span class="text hover-text" aria-hidden="true" data-text="' . $title . '"></span>';
        $output .= '</span>';
        $output .= '</a>';

        $output .= '</li>';
    }
}



class Mobile_Menu_Walker extends Walker_Nav_Menu
{

    // UL
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $class = 'sub-menu';
        if ($depth === 1) {
            $class .= ' submenu-level2';
        }
        $output .= '<ul class="' . esc_attr($class) . '">';
    }

    // LI
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {

        $has_children = in_array('menu-item-has-children', $item->classes);

        $classes = ['menu-item'];
        if ($has_children) {
            $classes[] = 'menu-item-has-children';
        }

        // cấp 1
        if ($depth === 0 && $has_children) {
            $classes[] = 'is-category';
        }

        // cấp 2 có submenu
        if ($depth === 1 && $has_children) {
            $classes[] = 'has-submenu';
        }

        $output .= '<li class="' . esc_attr(implode(' ', $classes)) . '">';

        // ===== LEVEL 0 =====
        if ($depth === 0 && $has_children) {

            $output .= '<div class="menu-item-header">';
            $output .= '<a class="category-title" href="' . esc_url($item->url) . '">';
            $output .= esc_html($item->title);
            $output .= '</a>';
            $output .= '<button class="submenu-toggle" type="button" aria-label="Toggle submenu">';
            $output .= '<i class="fa-light fa-chevron-down"></i>';
            $output .= '</button>';
            $output .= '</div>';
        } else {

            // ===== LEVEL 1+ =====
            $output .= '<a href="' . esc_url($item->url) . '">';

            if ($has_children) {
                $output .= '<span>' . esc_html($item->title) . '</span>';
                $output .= '<i class="fa-light fa-chevron-right"></i>';
            } else {
                $output .= esc_html($item->title);
            }

            $output .= '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }
}


function huyvo_register_post_types()
{

    $labels = array(
        'name'               => _x('Collections', 'post type general name', 'huyvo'),
        'singular_name'      => _x('Collection', 'post type singular name', 'huyvo'),
        'menu_name'          => _x('Collections', 'admin menu', 'huyvo'),
        'name_admin_bar'     => _x('Collection', 'add new on admin bar', 'huyvo'),
        'add_new'            => _x('Add New', 'collection', 'huyvo'),
        'add_new_item'       => _x('Add New Collection', 'huyvo'),
        'new_item'           => _x('New Collection', 'huyvo'),
        'edit_item'          => _x('Edit Collection', 'huyvo'),
        'view_item'          => _x('View Collection', 'huyvo'),
        'all_items'          => _x('All Collections', 'huyvo'),
        'search_items'       => _x('Search Collections', 'huyvo'),
        'not_found'          => _x('No collections found', 'huyvo'),
        'not_found_in_trash' => _x('No collections found in Trash', 'huyvo'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,

        'menu_icon'          => 'dashicons-admin-post',

        'rewrite'            => array(
            'slug'       => 'collection',
            'with_front' => false,
        ),

        'capability_type'    => 'post',
        'hierarchical'       => false,

        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions'
        ),

        'taxonomies'         => array('collection_category'),

        'has_archive'        => false,
    );

    register_post_type('collection', $args);
}
add_action('init', 'huyvo_register_post_types');

function huyvo_register_taxonomies()
{

    $labels = array(
        'name'              => _x('Collection Categories', 'taxonomy general name', 'huyvo'),
        'singular_name'     => _x('Collection Category', 'taxonomy singular name', 'huyvo'),
        'search_items'      => __('Search Collection Categories', 'huyvo'),
        'all_items'         => __('All Collection Categories', 'huyvo'),
        'parent_item'       => __('Parent Collection Category', 'huyvo'),
        'parent_item_colon' => __('Parent Collection Category:', 'huyvo'),
        'edit_item'         => __('Edit Collection Category', 'huyvo'),
        'update_item'       => __('Update Collection Category', 'huyvo'),
        'add_new_item'      => __('Add New Collection Category', 'huyvo'),
        'new_item_name'     => __('New Collection Category Name', 'huyvo'),
        'menu_name'         => __('Collection Categories', 'huyvo'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,

        'rewrite' => array(
            'slug'         => 'collection-category',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    );

    register_taxonomy('collection_category', array('collection'), $args);
}
add_action('init', 'huyvo_register_taxonomies');
