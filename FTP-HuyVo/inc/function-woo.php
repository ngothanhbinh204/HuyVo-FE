<?php
add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});

function theme_remove_woocommerce_hooks()
{
    // Global
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    // Single Product
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    remove_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 20);
    remove_action('woocommerce_after_single_product', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    // remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);


    // Archive Product
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
    // remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
    add_action('shop_filters_after', 'woocommerce_catalog_ordering', 10);
    // Remove default shipping and payment methods
    // remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
    remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);

    // add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
    // add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
    add_action('woocommerce_checkout_before_customer_details', 'woocommerce_output_all_notices', 10);
}
add_action('init', 'theme_remove_woocommerce_hooks');
// add_action('woocommerce_single_product_summary', 'my_all_attributes_switcher', 10);
function my_all_attributes_switcher()
{
    global $product;

    if (! $product->is_type('variable')) return;

    $attributes = $product->get_variation_attributes();
    if (empty($attributes)) return;

    echo '<div class="product-size-swiper relative pt-5">';
    echo '<div class="swiper">';
    echo '<div class="swiper-wrapper">';

    $first_item = true;
    foreach ($attributes as $attribute_name => $attribute_values) {
        foreach ($attribute_values as $attribute_value) {
            $variation = null;
            foreach ($product->get_available_variations() as $var) {
                if (isset($var['attributes']['attribute_' . $attribute_name]) && $var['attributes']['attribute_' . $attribute_name] === $attribute_value) {
                    $variation = wc_get_product($var['variation_id']);
                    break;
                }
            }

            $variation_id = $variation ? $variation->get_id() : '';
            $price = $variation ? $variation->get_price() : '';
            $regular_price = $variation ? $variation->get_regular_price() : '';
            $is_sale_tag = $variation ? get_post_meta($variation->get_id(), 'is_sale_tag', true) : '';
            $price_unit = $variation ? get_post_meta($variation->get_id(), 'price_unit', true) : '';
            $custom_variation_name = $variation ? get_post_meta($variation->get_id(), 'custom_variation_name', true) : '';
            $term = get_term_by('slug', $attribute_value, $attribute_name);

            $variation_image = '';
            if ($variation && $variation->get_image_id()) {
                $variation_image = wp_get_attachment_image_url($variation->get_image_id(), 'medium');
            } else {
                $variation_image = wp_get_attachment_image_url($product->get_image_id(), 'medium');
            }

            $active_class = $first_item ? ' is-active' : '';
            $product_title = !empty($custom_variation_name) ? $custom_variation_name : $term->name;

            echo '<div class="swiper-slide' . $active_class . '">';
            echo '<a class="option-item" data-variation_id="' . esc_attr($variation_id) . '" data-value="' . esc_attr($attribute_value) . '" data-attribute_name="' . esc_attr($attribute_name) . '">';
            echo '<div class="wrap border border-transparent overflow-hidden rounded-3 px-[8px] py-[20px] rounded-br-6">';
            echo '<div class="img overflow-hidden">';
            echo '<img src="' . esc_url($variation_image) . '" alt="' . esc_attr($product_title) . '">';
            echo '</div>';
            echo '<div class="txt">';
            echo '<div class="title font-bold text-center md:font-medium text-primary-1 transition">' . esc_html($product_title) . '</div>';
            echo '</div>';
            echo '</div>';

            if (!empty($price_unit)) {
                echo '<div class="volume start-item gap-1 mt-5">';
                echo '<span class="center-item overflow-hidden rounded-full bg-secondary-1 text-16 font-bold text-12 text-primary-1 px-[8px] h-[32px] md:h-[24px]">' . esc_html($price_unit) . '</span>';
                echo '</div>';
            }

            echo '</a>';
            echo '</div>';

            $first_item = false;
        }
    }

    echo '</div>'; // swiper-wrapper
    echo '</div>'; // swiper
    echo '</div>'; // product-size-swiper

    // Hidden inputs for form submission
    foreach ($attributes as $attribute_name => $attribute_values) {
        echo '<input type="hidden" name="attribute_' . esc_attr($attribute_name) . '" class="attribute_' . esc_attr($attribute_name) . '" value="">';
    }
    echo '<input type="hidden" name="variation_id" class="variation_id" value="">';
}


// Remove default variation select
function remove_variation_dropdown_style()
{
?>
    <style>
        .variations select,
        .variations .label {
            display: none !important;
        }
    </style>
<?php
}



add_filter('woocommerce_available_variation', 'add_parent_gallery_to_variation', 10, 3);
function add_parent_gallery_to_variation($variation_data, $product, $variation)
{
    $thumbnail_id = $variation->get_image_id();

    $parent_id = $variation->get_parent_id();
    $parent_product = wc_get_product($parent_id);

    $gallery_image_ids = $parent_product->get_gallery_image_ids();
    $gallery_image_ids = array_filter(array_merge([$thumbnail_id], $gallery_image_ids));
    $gallery = [];
    foreach ($gallery_image_ids as $image_id) {
        $gallery[] = [
            'url' => wp_get_attachment_url($image_id),
            'thumbnail' => wp_get_attachment_image_url($image_id, 'woocommerce_gallery_thumbnail'),
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true)
        ];
    }

    $variation_data['parent_gallery'] = $gallery;

    return $variation_data;
}


// Hiển thị field trong từng variation
// add_action('woocommerce_variation_options_pricing', 'add_custom_variation_fields', 10, 3);

function add_custom_variation_fields($loop, $variation_data, $variation)
{
    $custom_variation_name = get_post_meta($variation->ID, 'custom_variation_name', true);
    echo '<div class="custom_variation_fields">';
    woocommerce_wp_text_input(
        array(
            'id' => "custom_variation_name_{$loop}",
            'name' => "custom_variation_name[{$variation->ID}]",
            'value' => $custom_variation_name,
            'label' => __('Tên riêng:', 'woocommerce'),
            'wrapper_class' => 'form-row form-row-full',
        )
    );
    echo '</div>';
}

// Lưu dữ liệu khi cập nhật variation
add_action('woocommerce_save_product_variation', 'save_custom_variation_fields', 12, 2);

function save_custom_variation_fields($variation_id, $i)
{
    if (isset($_POST['custom_variation_name'][$variation_id])) {
        update_post_meta(
            $variation_id,
            'custom_variation_name',
            sanitize_text_field($_POST['custom_variation_name'][$variation_id])
        );
    }
}

add_action('woocommerce_admin_process_variation_object', function ($variation, $i) {
    if ($variation->get_regular_price() === '') {
        $variation->set_regular_price(0);
    }

    if ($variation->get_sale_price() === '') {
        $variation->set_sale_price('');
    }
}, 10, 2);

add_action('woocommerce_update_product_variation', 'save_custom_variation_fields_fallback', 10, 1);

function save_custom_variation_fields_fallback($variation_id)
{
    // Tìm index của variation trong form
    $variations = $_POST['variable_post_id'] ?? array();
    $i = array_search($variation_id, $variations);

    if ($i !== false) {
        error_log('Fallback save for variation ID: ' . $variation_id . ', Index: ' . $i);
        save_custom_variation_fields($variation_id, $i);
    }
}

add_filter('woocommerce_show_variation_price', '__return_true');


// Add product variations ACF rule
add_filter('acf/location/rule_values/post_type', 'acf_location_rule_values_Post');
function acf_location_rule_values_Post($choices)
{
    $choices['product_variation'] = 'Product Variation';
    return $choices;
}

$GLOBALS['wc_loop_variation_id'] = null;

function is_field_group_for_variation($field_group, $variation_data, $variation_post)
{
    return (preg_match('/Variation/i', $field_group['title']) == true);
}

add_action('woocommerce_product_after_variable_attributes', function ($loop_index, $variation_data, $variation_post) {
    $GLOBALS['wc_loop_variation_id'] = $variation_post->ID;

    foreach (acf_get_field_groups() as $field_group) {
        if (is_field_group_for_variation($field_group, $variation_data, $variation_post)) {
            acf_render_fields($variation_post->ID, acf_get_fields($field_group));
        }
    }

    $GLOBALS['wc_loop_variation_id'] = null;
}, 10, 3);

add_action('woocommerce_save_product_variation', function ($variation_id, $loop_index) {
    if (!isset($_POST['acf_variation'][$variation_id])) {
        return;
    }
    if (!empty($_POST['acf_variation'][$variation_id]) && is_array($fields = $_POST['acf_variation'][$variation_id])) {
        foreach ($fields as $key => $val) {
            update_field($key, $val, $variation_id);
        }
    }
}, 10, 2);

add_filter('acf/prepare_field', function ($field) {
    if (!$GLOBALS['wc_loop_variation_id']) {
        return $field;
    }

    $field['name'] = preg_replace('/^acf\[/', 'acf_variation[' . $GLOBALS['wc_loop_variation_id'] . '][', $field['name']);

    return $field;
}, 10, 1);



function ajax_get_variation_acf_fields_color()
{
    $parent_id = isset($_POST['parent_id']) ? absint($_POST['parent_id']) : 0;
    $color = isset($_POST['color']) ? sanitize_text_field($_POST['color']) : '';

    if (!$parent_id || !$color) {
        wp_send_json_error(['message' => 'Thiếu parent_id hoặc color']);
    }

    $args = array(
        'post_type'      => 'product_variation',
        'posts_per_page' => -1,
        'post_parent'    => $parent_id,
        'meta_query'     => array(
            array(
                'key'   => 'attribute_pa_mau-sac',
                'value' => $color,
            ),
        ),
    );

    $query = new WP_Query($args);
    $variation_albums = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $variation_id = get_the_ID();
            $album = get_field('variation_album', $variation_id);
            if (!empty($album)) {
                $variation_albums[] = $album;
            }
        }
        wp_reset_postdata();
    }

    wp_send_json_success($variation_albums);
}
add_action('wp_ajax_get_variation_acf_fields_color', 'ajax_get_variation_acf_fields_color');
add_action('wp_ajax_nopriv_get_variation_acf_fields_color', 'ajax_get_variation_acf_fields_color');



// add_action('admin_init', 'transfer_variation_images_to_acf_gallery');
function transfer_variation_images_to_acf_gallery()
{
    // Lấy tất cả sản phẩm biến thể
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'variable',
            ),
        ),
    );
    $products = new WP_Query($args);

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);

            // Lấy tất cả variations của sản phẩm
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                $variation_id = $variation['variation_id'];
                // Lấy dữ liệu từ rtwpvg_images
                $image_ids_data = get_post_meta($variation_id, 'rtwpvg_images', true);

                $image_ids = array();
                if (is_array($image_ids_data)) {
                    $image_ids = array_map('absint', $image_ids_data);
                } elseif (is_string($image_ids_data) && !empty($image_ids_data)) {
                    $image_ids = array_map('absint', explode(',', $image_ids_data));
                }

                // Nếu có ID hình ảnh, gán vào trường ACF Gallery
                if (!empty($image_ids)) {
                    update_field('variation_album', $image_ids, $variation_id);
                }
            }
        }
        wp_reset_postdata();
    }
}

// Function để hiển thị variation fields trong frontend
function display_variation_fields_in_frontend($variation_id)
{
    $custom_variation_name = get_post_meta($variation_id, 'custom_variation_name', true);
    $thanh_phan = get_post_meta($variation_id, 'thanh_phan', true);
    $huong_dan_su_dung = get_post_meta($variation_id, 'huong_dan_su_dung', true);

    $output = '';

    if (!empty($custom_variation_name)) {
        $output .= '<div class="variation-custom-name">';
        $output .= '<h4>Tên riêng:</h4>';
        $output .= '<p>' . esc_html($custom_variation_name) . '</p>';
        $output .= '</div>';
    }

    if (!empty($thanh_phan)) {
        $output .= '<div class="variation-thanh-phan">';
        $output .= '<h4>Thành phần dinh dưỡng:</h4>';
        $output .= '<div class="content">' . wp_kses_post($thanh_phan) . '</div>';
        $output .= '</div>';
    }

    if (!empty($huong_dan_su_dung)) {
        $output .= '<div class="variation-huong-dan">';
        $output .= '<h4>Hướng dẫn sử dụng:</h4>';
        $output .= '<div class="content">' . wp_kses_post($huong_dan_su_dung) . '</div>';
        $output .= '</div>';
    }

    return $output;
}


// 



add_filter('woocommerce_catalog_orderby', 'custom_translate_woocommerce_orderby');
function custom_translate_woocommerce_orderby($sortby)
{
    // unset($sortby['price']);
    // unset($sortby['price-desc']);
    unset($sortby['popularity']);
    unset($sortby['rating']);
    unset($sortby['menu_order']);

    // Sửa / thêm lại
    $sortby['date']   = __('Mới nhất', 'huyvo');
    $sortby['az']     = __('A - Z', 'huyvo');
    $sortby['za']     = __('Z - A', 'huyvo');
    $sortby['price-desc'] = __('Giá: cao đến thấp', 'huyvo');
    $sortby['price-asc'] = __('Giá: thấp đến cao', 'huyvo');

    return $sortby;
}
add_filter('woocommerce_get_catalog_ordering_args', 'custom_catalog_ordering_args');
function custom_catalog_ordering_args($args)
{
    $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : get_option('woocommerce_default_catalog_orderby');

    if ($orderby_value == 'az') {
        $args['orderby'] = 'title';
        $args['order']   = 'asc';
    }

    if ($orderby_value == 'za') {
        $args['orderby'] = 'title';
        $args['order']   = 'desc';
    }

    return $args;
}




// Thêm select chọn số sản phẩm / trang
// add_action('woocommerce_before_shop_loop', 'custom_woo_products_per_page', 15);
function custom_woo_products_per_page()
{
    $current_ppp = isset($_GET['ppp']) ? (int) $_GET['ppp'] : get_option('posts_per_page');
    $options = array(12, 24, 36, 48);
    echo '<form method="get" class="woocommerce-per-page view-filter end-item max-lg:mb-5">';
    echo '<label class="text-18 mr-3 whitespace-nowrap">' . __('Hiển thị', 'canhcamtheme') . '</label>';
    echo '<div class="custom-select">';
    echo '<select name="ppp" onchange="this.form.submit()">';
    foreach ($options as $option) {
        $selected = selected($current_ppp, $option, false);
        echo "<option value='{$option}' {$selected}>{$option}</option>";
    }
    echo '</select>';
    echo '</div>';
    // Giữ lại query string khác (ví dụ orderby, filter)
    foreach ($_GET as $key => $val) {
        if ('ppp' === $key) continue;
        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" />';
    }

    echo '</form>';
}

// Áp dụng số sản phẩm / trang
add_filter('loop_shop_per_page', function ($cols) {
    return isset($_GET['ppp']) ? (int) $_GET['ppp'] : $cols;
}, 20);


function filter_product_by_category()
{
    $category_id = isset($_POST['categoryId']) ? (int) $_POST['categoryId'] : 0;
    if ($category_id) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 8,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );
        $products = new WP_Query($args);
        $html = '';
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                $html .= get_template_part('components/content-product-3');
            }
        } else {
            $html = '<div class="text-center">' . __('Sản phẩm đang cập nhật', 'canhcamtheme') . '</div>';
        }
        wp_reset_postdata();
        echo $html;
        die();
    }
}
add_action('wp_ajax_filter_product_by_category', 'filter_product_by_category');
add_action('wp_ajax_nopriv_filter_product_by_category', 'filter_product_by_category');


function add_Admin_script()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $(document).on(
                'change',
                '[data-name="category"]  select',
                function() {
                    const select2 = $(this).select2("data");
                    const value = select2[0].id;
                    const title = select2[0].text;
                    console.log(value, title);
                    $(this)
                        .closest(".acf-fields")
                        .find('[data-type="relationship"] select optgroup')
                        .each(function() {
                            $(this)
                                .find("option")
                                .each(function() {
                                    if ($(this).text() === title) {
                                        $(this).prop("selected", true);
                                    }
                                });
                        });
                    $(this)
                        .closest(".acf-fields")
                        .find('[data-type="relationship"] select')
                        .trigger("change");
                    $(this).closest(".acf-fields").find(".values ul").children().remove();
                }
            );
        });
    </script>
<?php
}
add_action('admin_footer', 'add_Admin_script');
add_filter('woocommerce_get_price_html', 'custom_price_html_wrapper', 100, 2);
add_filter('woocommerce_variable_sale_price_html', 'custom_price_html_wrapper', 100, 2);
add_filter('woocommerce_variable_price_html', 'custom_price_html_wrapper', 100, 2);

function custom_price_html_wrapper($price, $product)
{
    if (!$product || '' === $product->get_price()) {
        return $price; // Giữ giá gốc nếu không có giá
    }

    $html = '<div class="product-price-wrapper">';

    // Trường hợp sản phẩm Simple hoặc Variable có cùng giá tất cả variations
    if (!$product->is_type('variable') || $product->get_variation_price('min') === $product->get_variation_price('max')) {
        if ($product->is_on_sale()) {
            $html .= '<span class="current-price">' . wc_price($product->get_sale_price()) . '</span>';
            $html .= '<span class="old-price">' . wc_price($product->get_regular_price()) . '</span>';
        } else {
            $html .= '<span class="current-price">' . wc_price($product->get_price()) . '</span>';
        }
    }
    // Trường hợp Variable có giá khác nhau (range)
    else {
        $min_price     = $product->get_variation_price('min');
        $max_price     = $product->get_variation_price('max');
        $min_regular   = $product->get_variation_regular_price('min');
        $min_sale      = $product->get_variation_sale_price('min');

        // Nếu có ít nhất 1 variation đang sale → hiển thị old price là min regular
        if ($min_sale < $min_regular) {
            $html .= '<span class="current-price">' . wc_price($min_price) . '</span>'; // Current là min price (đã sale)
            $html .= '<span class="old-price">' . wc_price($min_regular) . '</span>';
        } else {
            // Không sale → chỉ current price (min price)
            $html .= '<span class="current-price">' . wc_price($min_price) . '</span>';
        }

        // Nếu bạn muốn hiển thị full range (ví dụ: 100.000 ₫ – 200.000 ₫) thì thêm dòng này vào current-price:
        // $html .= '<span class="current-price">' . wc_price($min_price) . ' – ' . wc_price($max_price) . '</span>';
    }

    $html .= '</div>';

    return $html;
}


// *** Auto select first variation ***
add_filter('woocommerce_dropdown_variation_attribute_options_args', 'auto_select_first_variation', 10, 1);
function auto_select_first_variation($args)
{
    $params = 'attribute_' . $args['attribute'];
    $currentUrl = $_SERVER['REQUEST_URI'];
    $active_first_variant = strpos($currentUrl, $params);
    if (count($args['options']) > 0)
        if ($active_first_variant === false)
            $args['selected'] = $args['options'][0];
    return $args;
}

// *** Shop Filter Handler - Apply filters from URL params ***
add_action('woocommerce_product_query', 'apply_shop_filters_from_url', 20);
function apply_shop_filters_from_url($query)
{
    if (is_admin()) {
        return;
    }

    $tax_query = $query->get('tax_query');
    if (!is_array($tax_query)) {
        $tax_query = array();
    }

    // Get all filter params from URL
    $filter_params = array();
    foreach ($_GET as $key => $value) {
        // Skip non-taxonomy params
        if (in_array($key, array('orderby', 'ppp', 'paged', 's'))) {
            continue;
        }

        // Skip query type params (we'll handle them separately)
        if (strpos($key, '_query_type') !== false) {
            continue;
        }

        // Check if it's a valid taxonomy
        $taxonomy = sanitize_key($key);
        if (taxonomy_exists($taxonomy)) {
            $values = explode(',', sanitize_text_field($value));
            $values = array_filter(array_map('trim', $values));

            if (!empty($values)) {
                $query_type = isset($_GET[$taxonomy . '_query_type']) ? sanitize_text_field($_GET[$taxonomy . '_query_type']) : 'IN';

                // Convert query type
                $operator = 'IN';
                if ($query_type === 'and') {
                    $operator = 'AND';
                } elseif ($query_type === 'or') {
                    $operator = 'IN';
                }

                $filter_params[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $values,
                    'operator' => $operator,
                );
            }
        }
    }

    // Merge with existing tax_query
    if (!empty($filter_params)) {
        // If there are multiple filters, they should be AND together
        if (count($filter_params) > 1) {
            $tax_query['relation'] = 'AND';
        }

        $tax_query = array_merge($tax_query, $filter_params);
        $query->set('tax_query', $tax_query);
    }
}

function set_product_per_page()
{
    $per_page = isset($_GET['ppp']) ? intval($_GET['ppp']) : 16;
    return $per_page;
}
add_filter('loop_shop_per_page', 'set_product_per_page');

// *** AJAX Handler for Shop Filter ***
add_action('wp_ajax_filter_shop_products', 'ajax_filter_shop_products');
add_action('wp_ajax_nopriv_filter_shop_products', 'ajax_filter_shop_products');
function ajax_filter_shop_products()
{
    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : '';

    // Build query args
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 16,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    // Build tax_query from filters
    $tax_query = array();
    foreach ($filters as $taxonomy => $values) {
        $taxonomy = sanitize_key($taxonomy);
        if (taxonomy_exists($taxonomy)) {
            $values = explode(',', sanitize_text_field($values));
            $values = array_filter(array_map('trim', $values));

            if (!empty($values)) {
                $query_type = isset($filters[$taxonomy . '_query_type']) ? sanitize_text_field($filters[$taxonomy . '_query_type']) : 'IN';
                $operator = ($query_type === 'and') ? 'AND' : 'IN';

                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $values,
                    'operator' => $operator,
                );
            }
        }
    }

    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }

    // Handle ordering
    if ($orderby) {
        if ($orderby === 'az' || $orderby === 'za') {
            $args['orderby'] = 'title';
            $args['order'] = ($orderby === 'za') ? 'DESC' : 'ASC';
        }
    }

    // Execute query
    $products_query = new WP_Query($args);

    // Buffer output
    ob_start();

    if ($products_query->have_posts()) {
        woocommerce_product_loop_start();

        while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product');
        }

        woocommerce_product_loop_end();
    } else {
        wc_get_template('loop/no-products-found.php');
    }

    wp_reset_postdata();

    $products_html = ob_get_clean();

    wp_send_json_success(array(
        'products'   => $products_html,
        'max_pages'  => $products_query->max_num_pages,
    ));
}

// *** AJAX Handler for Load More Products ***
add_action('wp_ajax_load_more_products', 'ajax_load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'ajax_load_more_products');
function ajax_load_more_products()
{
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : '';

    // Build query args
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 16,
        'paged'          => $page + 1, // Next page
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    // Build tax_query from filters
    $tax_query = array();
    foreach ($filters as $taxonomy => $values) {
        $taxonomy = sanitize_key($taxonomy);
        if (taxonomy_exists($taxonomy)) {
            $values = explode(',', sanitize_text_field($values));
            $values = array_filter(array_map('trim', $values));

            if (!empty($values)) {
                $query_type = isset($filters[$taxonomy . '_query_type']) ? sanitize_text_field($filters[$taxonomy . '_query_type']) : 'IN';
                $operator = ($query_type === 'and') ? 'AND' : 'IN';

                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $values,
                    'operator' => $operator,
                );
            }
        }
    }

    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }

    // Handle ordering
    if ($orderby) {
        if ($orderby === 'az' || $orderby === 'za') {
            $args['orderby'] = 'title';
            $args['order'] = ($orderby === 'za') ? 'DESC' : 'ASC';
        }
    }

    // Execute query
    $products_query = new WP_Query($args);

    // Buffer output
    ob_start();

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product');
        }
    }

    wp_reset_postdata();

    $products_html = ob_get_clean();

    // Check if there are more pages
    $has_more = ($products_query->max_num_pages > $page + 1);

    wp_send_json_success(array(
        'products' => $products_html,
        'has_more' => $has_more,
        'next_page' => $page + 1,
        'max_pages' => $products_query->max_num_pages,
    ));
}


/**
 * @snippet       Toggle Registration / Login Forms @ My Account
 * @tutorial      https://businessbloomer.com/woocommerce-customization
 * @author        Rodolfo Melogli, Business Bloomer
 * @compatible    WooCommerce 10
 * @community     https://businessbloomer.com/club/
 */

add_action('woocommerce_login_form_end', 'bbloomer_my_account_registration_show_onclick');

function bbloomer_my_account_registration_show_onclick()
{

    if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) return;

    echo '<p><a href="#" id="show-registration">No account? Register here</a></p>';

    wc_enqueue_js("
    
      var loginForm = $('#customer_login > .u-column1').removeClass('col-1');
      var regForm = $('#customer_login > .u-column2').removeClass('col-2');      
        regForm.hide();
 
        $('#show-registration').click(function(e) {
            e.preventDefault();
            loginForm.slideToggle();
            regForm.slideToggle();
        });
       
   ");
}

add_action('woocommerce_register_form_end', 'bbloomer_my_account_login_show_onclick');

function bbloomer_my_account_login_show_onclick()
{

    if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) return;

    echo '<p><a href="#" id="hide-registration">Already have an account? Log in here</a></p>';

    wc_enqueue_js("
    
      var loginForm = $('#customer_login > .u-column1');
      var regForm = $('#customer_login > .u-column2');      
 
        $('#hide-registration').click(function(e) {
            e.preventDefault();
            loginForm.slideToggle();
            regForm.slideToggle();
        });
       
   ");
}


add_filter('woocommerce_add_to_cart_fragments', 'custom_cart_fragments');
function custom_cart_fragments($fragments)
{
    // Cập nhật fragment cho cart-count
    $fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';

    // Cập nhật fragment cho widget_shopping_cart_content (nếu cần tùy chỉnh)
    ob_start();
?>
    <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
    </div>
<?php
    $fragments['div.widget_shopping_cart_content'] = ob_get_clean();

    return $fragments;
}


//Thêm VNĐ cho woo
add_filter('woocommerce_currency_symbol', function ($symbol, $currency) {
    if ($currency === 'VND') {
        $symbol = 'đ';
    }
    return $symbol;
}, 10, 2);


// Bước 1: Không gắn variation attributes vào tên sản phẩm (trong cart, checkout, order...)
add_filter('woocommerce_product_variation_title_include_attributes', '__return_false');

// Bước 2: Đảm bảo variation attributes vẫn hiển thị ở phần meta (dưới tên sản phẩm)
add_filter('woocommerce_is_attribute_in_product_name', '__return_false');

add_filter('woocommerce_cart_item_thumbnail', 'change_cart_item_thumbnail_to_full', 10, 3);

function change_cart_item_thumbnail_to_full($thumbnail, $cart_item, $cart_item_key)
{
    $_product = $cart_item['data'];

    // Lấy hình full size (original uploaded)
    return $_product->get_image('full');
}


add_action('wp_ajax_update_mini_cart', 'ajax_update_mini_cart');
add_action('wp_ajax_nopriv_update_mini_cart', 'ajax_update_mini_cart');

function ajax_update_mini_cart()
{
    if (
        empty($_POST['cart_item_key']) ||
        ! isset($_POST['quantity'])
    ) {
        wp_send_json_error();
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity      = max(1, intval($_POST['quantity']));

    if (WC()->cart->get_cart_item($cart_item_key)) {
        WC()->cart->set_quantity($cart_item_key, $quantity, true);
        wp_send_json_success();
    }

    wp_send_json_error();
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('wc-cart-fragments');
});



add_action('woocommerce_register_form_start', 'custom_add_register_fields');
function custom_add_register_fields()
{
?>
    <p class="form-row form-row-wide">
        <label for="reg_full_name">
            <?php _e('Họ và tên', 'huyvo'); ?> <span class="required">*</span>
        </label>
        <input type="text"
            name="full_name"
            id="reg_full_name"
            class="input-text"
            value="<?php if (!empty($_POST['full_name'])) echo esc_attr($_POST['full_name']); ?>" />
    </p>

    <p class="form-row form-row-wide">
        <label for="reg_phone">
            <?php _e('Số điện thoại', 'huyvo'); ?> <span class="required">*</span>
        </label>
        <input type="text"
            name="phone"
            id="reg_phone"
            class="input-text"
            value="<?php if (!empty($_POST['phone'])) echo esc_attr($_POST['phone']); ?>" />
    </p>
<?php
}

add_action('woocommerce_register_post', 'custom_validate_register_fields', 10, 3);
function custom_validate_register_fields($username, $email, $errors)
{

    if (empty($_POST['full_name'])) {
        $errors->add('full_name_error', 'Vui lòng nhập họ tên.');
    }

    if (empty($_POST['phone'])) {
        $errors->add('phone_error', 'Vui lòng nhập số điện thoại.');
    }

    return $errors;
}
add_action('show_user_profile', 'custom_show_user_fields');
add_action('edit_user_profile', 'custom_show_user_fields');

function custom_show_user_fields($user)
{
?>
    <h3>Thông tin bổ sung</h3>

    <table class="form-table">
        <tr>
            <th>Họ và tên</th>
            <td>
                <input type="text" name="full_name" class="input-text"
                    value="<?php echo esc_attr(get_user_meta($user->ID, 'full_name', true)); ?>" />
            </td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>
                <input type="text" name="phone" class="input-text"
                    value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>" />
            </td>
        </tr>
    </table>
    <?php
}


add_action('personal_options_update', 'custom_save_user_fields');
add_action('edit_user_profile_update', 'custom_save_user_fields');

function custom_save_user_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id)) return false;

    update_user_meta($user_id, 'full_name', sanitize_text_field($_POST['full_name']));
    update_user_meta($user_id, 'billing_first_name', sanitize_text_field($_POST['full_name']));
    update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    update_user_meta($user_id, '_billing_phone', sanitize_text_field($_POST['phone']));
}


// Checkout VietNam

add_filter('woocommerce_checkout_fields', 'custom_vn_checkout_fields');
function custom_vn_checkout_fields($fields)
{

    $fields['billing']['billing_city']['required']  = false;

    // Ưu tiên lấy country từ POST (khi update_order_review) hoặc từ customer object
    $country = '';
    if (isset($_POST['billing_country']) && !empty($_POST['billing_country'])) {
        $country = sanitize_text_field($_POST['billing_country']);
    } else {
        $wc = WC();
        if ($wc && $wc->customer) {
            $country = $wc->customer->get_country();
        }
    }

    // Fallback
    if (empty($country)) {
        $country = 'VN';
    }

    // Cấu hình billing_state để dùng cho Tỉnh/Thành phố VN
    // Luôn set priority cho billing_state (không phụ thuộc vào country)
    if (isset($fields['billing']['billing_state'])) {
        $fields['billing']['billing_state']['priority'] = 60;
    }

    if ($country == 'VN') {
        if (isset($fields['billing']['billing_state'])) {
            $fields['billing']['billing_state']['required'] = true;
            $fields['billing']['billing_state']['label'] = 'Tỉnh / Thành phố';
            $fields['billing']['billing_state']['type'] = 'select';
            $fields['billing']['billing_state']['class'] = array('form-row-wide', 'address-field', 'validate-required', 'select');

            // Load options từ tỉnh thành VN
            $province_options = array('' => 'Chọn Tỉnh / Thành');
            $provinces = get_tinh_thanh_vietnam();
            if (!empty($provinces) && is_array($provinces)) {
                foreach ($provinces as $code => $name) {
                    if (!empty($code) && !empty($name)) {
                        $province_options[$code] = $name;
                    }
                }
            }
            $fields['billing']['billing_state']['options'] = $province_options;
        }
    } else {
        if (isset($fields['billing']['billing_state'])) {
            $fields['billing']['billing_state']['required'] = false;
        }
    }

    // Quận huyện - luôn set priority, không phụ thuộc vào country
    if (!isset($fields['billing']['billing_district'])) {
        $fields['billing']['billing_district'] = array();
    }
    $fields['billing']['billing_district']['label'] = 'Quận / Huyện';
    $fields['billing']['billing_district']['required'] = $country == 'VN' ? true : false;
    $fields['billing']['billing_district']['type'] = 'select';
    $fields['billing']['billing_district']['class'] = array('form-row-wide', 'address-field', 'validate-required', 'select');
    $fields['billing']['billing_district']['priority'] = 61; // Luôn set priority
    if (!isset($fields['billing']['billing_district']['options']) || empty($fields['billing']['billing_district']['options'])) {
        $fields['billing']['billing_district']['options'] = array('' => 'Chọn Quận / Huyện');
    }

    // Phường xã - luôn set priority, không phụ thuộc vào country
    if (!isset($fields['billing']['billing_ward'])) {
        $fields['billing']['billing_ward'] = array();
    }
    $fields['billing']['billing_ward']['label'] = 'Phường / Xã';
    $fields['billing']['billing_ward']['required'] = $country == 'VN' ? true : false;
    $fields['billing']['billing_ward']['type'] = 'select';
    $fields['billing']['billing_ward']['class'] = array('form-row-wide', 'address-field', 'validate-required', 'select');
    $fields['billing']['billing_ward']['priority'] = 62; // Luôn set priority
    if (!isset($fields['billing']['billing_ward']['options']) || empty($fields['billing']['billing_ward']['options'])) {
        $fields['billing']['billing_ward']['options'] = array('' => 'Chọn Phường / Xã');
    }

    return $fields;
}



// Ẩn các field VN mặc định, chỉ hiển thị khi chọn VN (JavaScript sẽ xử lý việc hiển thị)
add_action('wp_head', 'hide_vn_fields_by_default');
function hide_vn_fields_by_default()
{
    if (is_checkout()) {
    ?>
        <style>
            /* #billing_province_field,
            #billing_district_field,
            #billing_ward_field {
                display: none;
            } */
        </style>
<?php
    }
}

// Get tinh thanh pho
function get_tinh_thanh_vietnam()
{
    global $tinh_thanhpho;

    return $tinh_thanhpho;
}

// Get quan huyen
function get_tinh_quan_huyen_vietnam()
{
    global $quan_huyen;
    return $quan_huyen;
}

// Get xa phuong thitran
function get_xaphuongthitran_vietnam()
{
    global $xa_phuong_thitran;
    return $xa_phuong_thitran;
}


function get_ward_vietnam($ward_id)
{
    if (empty($ward_id)) {
        return '';
    }

    global $xa_phuong_thitran;
    if (empty($xa_phuong_thitran) || !is_array($xa_phuong_thitran)) {
        return '';
    }

    foreach ($xa_phuong_thitran as $key => $value) {
        if (isset($value['xaid']) && $value['xaid'] == $ward_id) {
            return isset($value['name']) ? $value['name'] : '';
        }
    }
    return '';
}
function get_district_vietnam($district_id)
{
    if (empty($district_id)) {
        return '';
    }

    global $quan_huyen;
    if (empty($quan_huyen) || !is_array($quan_huyen)) {
        return '';
    }

    foreach ($quan_huyen as $key => $value) {
        if (isset($value['maqh']) && $value['maqh'] == $district_id) {
            return isset($value['name']) ? $value['name'] : '';
        }
    }
    return '';
}
function get_province_vietnam($province_id)
{
    if (empty($province_id)) {
        return '';
    }

    global $tinh_thanhpho;
    if (empty($tinh_thanhpho) || !is_array($tinh_thanhpho)) {
        return '';
    }

    if (isset($tinh_thanhpho[$province_id])) {
        return $tinh_thanhpho[$province_id];
    }
    return '';
}
add_filter('woocommerce_states', 'vn_woocommerce_states');

function vn_woocommerce_states($states)
{
    $states['VN'] = get_tinh_thanh_vietnam();
    return $states;
}

// AJAX: Get provinces (Tỉnh/Thành phố)
add_action('wp_ajax_get_provinces', 'ajax_get_provinces');
add_action('wp_ajax_nopriv_get_provinces', 'ajax_get_provinces');
function ajax_get_provinces()
{
    $provinces = get_tinh_thanh_vietnam();
    $options = array('' => 'Chọn Tỉnh / Thành');

    foreach ($provinces as $code => $name) {
        $options[$code] = $name;
    }

    wp_send_json_success($options);
}

// AJAX: Get districts (Quận/Huyện) by province
add_action('wp_ajax_get_districts', 'ajax_get_districts');
add_action('wp_ajax_nopriv_get_districts', 'ajax_get_districts');
function ajax_get_districts()
{
    $province_code = isset($_POST['province']) ? sanitize_text_field($_POST['province']) : '';

    if (empty($province_code)) {
        wp_send_json_error(array('message' => 'Thiếu mã tỉnh thành'));
    }

    $districts = get_tinh_quan_huyen_vietnam();
    $options = array('' => 'Chọn Quận / Huyện');

    foreach ($districts as $district) {
        if ($district['matp'] === $province_code) {
            $options[$district['maqh']] = $district['name'];
        }
    }

    wp_send_json_success($options);
}

// AJAX: Get wards (Phường/Xã) by district
add_action('wp_ajax_get_wards', 'ajax_get_wards');
add_action('wp_ajax_nopriv_get_wards', 'ajax_get_wards');
function ajax_get_wards()
{
    $district_code = isset($_POST['district']) ? sanitize_text_field($_POST['district']) : '';

    if (empty($district_code)) {
        wp_send_json_error(array('message' => 'Thiếu mã quận huyện'));
    }

    $wards = get_xaphuongthitran_vietnam();
    $options = array('' => 'Chọn Phường / Xã');

    if (!empty($wards) && is_array($wards)) {
        foreach ($wards as $ward) {
            if (isset($ward['maqh']) && $ward['maqh'] === $district_code) {
                $options[$ward['xaid']] = $ward['name'];
            }
        }
    }

    wp_send_json_success($options);
}


add_filter('woocommerce_checkout_fields', function ($fields) {

    $fields['billing']['billing_first_name']['label'] = false;
    $fields['billing']['billing_first_name']['placeholder'] = 'Nhập Họ';

    $fields['billing']['billing_last_name']['label'] = false;
    $fields['billing']['billing_last_name']['placeholder'] = 'Nhập Tên';
    $fields['billing']['billing_last_name']['label'] = false;
    $fields['billing']['billing_phone']['placeholder'] = 'Nhập Số điện thoại';

    // Kiểm tra quốc gia hiện tại
    // Ưu tiên lấy từ POST (khi update_order_review) hoặc từ customer object
    $country = '';
    if (isset($_POST['billing_country']) && !empty($_POST['billing_country'])) {
        $country = sanitize_text_field($_POST['billing_country']);
    } else {
        $wc = WC();
        if ($wc && $wc->customer) {
            $country = $wc->customer->get_country();
        }
    }

    // Fallback nếu vẫn không có
    if (empty($country)) {
        $country = 'VN'; // Default hoặc có thể để empty
    }

    // Priority
    // Họ & Tên
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_last_name']['priority']  = 20;

    // Email & Phone → lên ngay sau tên
    $fields['billing']['billing_email']['priority'] = 30;
    $fields['billing']['billing_phone']['priority'] = 40;

    // Country
    $fields['billing']['billing_country']['priority'] = 50;

    // VN address - sắp xếp thứ tự: Tỉnh (billing_state) → Quận → Phường → Address 1
    // Luôn set priority cho các field VN (không phụ thuộc vào country check)
    // Vì khi update_order_review, country có thể chưa được set đúng
    if (isset($fields['billing']['billing_state'])) {
        $fields['billing']['billing_state']['priority'] = 60;
    }
    if (isset($fields['billing']['billing_district'])) {
        $fields['billing']['billing_district']['priority'] = 61;
    }
    if (isset($fields['billing']['billing_ward'])) {
        $fields['billing']['billing_ward']['priority'] = 62;
    }

    // Address 1 - set priority dựa trên country
    if ($country === 'VN') {
        // Address 1 hiển thị sau Phường/Xã
        $fields['billing']['billing_address_1']['priority'] = 63;
    } else {
        // Nếu không phải VN, giữ priority mặc định
        if (!isset($fields['billing']['billing_address_1']['priority']) || $fields['billing']['billing_address_1']['priority'] < 70) {
            $fields['billing']['billing_address_1']['priority'] = 70;
        }
    }

    // Address - điều chỉnh required dựa trên quốc gia
    if ($country === 'VN') {
        $fields['billing']['billing_address_1']['required'] = true;
        $fields['billing']['billing_address_2']['required'] = false;
        $fields['billing']['billing_postcode']['required'] = false;

        // Ẩn city và state mặc định khi VN (vì đã dùng billing_state cho tỉnh)
        if (isset($fields['billing']['billing_city'])) {
            $fields['billing']['billing_city']['priority'] = 100; // Đẩy xuống cuối để ẩn
        }
    } else {
        // Nếu không phải VN, set priority cho city và state
        if (isset($fields['billing']['billing_city'])) {
            $fields['billing']['billing_city']['priority'] = 70;
        }
    }

    $fields['billing']['billing_address_2']['priority'] = 71;
    $fields['billing']['billing_postcode']['priority'] = 72;

    return $fields;
});

add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    // Lấy country từ POST hoặc từ customer object
    $country = '';
    if (isset($_POST['billing_country']) && !empty($_POST['billing_country'])) {
        $country = sanitize_text_field($_POST['billing_country']);
    } else {
        $wc = WC();
        if ($wc && $wc->customer) {
            $country = $wc->customer->get_country();
        }
    }

    // Chỉ validate khi country là VN
    if ($country !== 'VN') {
        return;
    }

    // Validate các field VN - chỉ validate nếu field được submit
    // if (isset($_POST['billing_state']) && empty($_POST['billing_state'])) {
    //     $errors->add('billing_state', __('Vui lòng chọn Tỉnh / Thành phố', 'huyvo'));
    // }

    // if (isset($_POST['billing_district']) && empty($_POST['billing_district'])) {
    //     $errors->add('billing_district', __('Vui lòng chọn Quận / Huyện', 'huyvo'));
    // }

    // if (isset($_POST['billing_ward']) && empty($_POST['billing_ward'])) {
    //     $errors->add('billing_ward', __('Vui lòng chọn Phường / Xã', 'huyvo'));
    // }

    if (isset($_POST['billing_address_1']) && empty($_POST['billing_address_1'])) {
        $errors->add('billing_address_1', __('Vui lòng nhập địa chỉ cụ thể', 'huyvo'));
    }
}, 10, 2);

// Lưu các trường custom vào order meta khi checkout
// Note: billing_state đã được WooCommerce tự động lưu, không cần lưu lại
add_action('woocommerce_checkout_update_order_meta', 'save_custom_checkout_fields_to_order');
function save_custom_checkout_fields_to_order($order_id)
{
    // Kiểm tra order_id hợp lệ
    if (!$order_id || !is_numeric($order_id)) {
        return;
    }

    // Lưu district
    if (isset($_POST['billing_district']) && !empty($_POST['billing_district'])) {
        update_post_meta($order_id, '_billing_district', sanitize_text_field($_POST['billing_district']));
    }

    // Lưu ward
    if (isset($_POST['billing_ward']) && !empty($_POST['billing_ward'])) {
        update_post_meta($order_id, '_billing_ward', sanitize_text_field($_POST['billing_ward']));
    }
}

// Hiển thị các trường custom trong admin order details
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_checkout_fields_in_admin');
function display_custom_checkout_fields_in_admin($order)
{
    // Lấy tỉnh thành từ billing_state (WooCommerce đã lưu)
    $province_code = $order->get_billing_state();
    $district_code = get_post_meta($order->get_id(), '_billing_district', true);
    $ward_code = get_post_meta($order->get_id(), '_billing_ward', true);

    if ($province_code) {
        $province_name = get_province_vietnam($province_code);
        echo '<p><strong>Tỉnh / Thành phố:</strong> ' . esc_html($province_name) . ' (' . esc_html($province_code) . ')</p>';
    }

    if ($district_code) {
        $district_name = get_district_vietnam($district_code);
        echo '<p><strong>Quận / Huyện:</strong> ' . esc_html($district_name) . ' (' . esc_html($district_code) . ')</p>';
    }

    if ($ward_code) {
        $ward_name = get_ward_vietnam($ward_code);
        echo '<p><strong>Phường / Xã:</strong> ' . esc_html($ward_name) . ' (' . esc_html($ward_code) . ')</p>';
    }
}

// Hiển thị các trường custom trong order details cho customer
add_action('woocommerce_order_details_after_customer_details', 'display_custom_checkout_fields_in_order_details');
function display_custom_checkout_fields_in_order_details($order)
{
    // Lấy tỉnh thành từ billing_state (WooCommerce đã lưu)
    $province_code = $order->get_billing_state();
    $district_code = get_post_meta($order->get_id(), '_billing_district', true);
    $ward_code = get_post_meta($order->get_id(), '_billing_ward', true);

    if ($province_code || $district_code || $ward_code) {
        echo '<section class="woocommerce-customer-details">';
        echo '<h2 class="woocommerce-order-details__title">Địa chỉ chi tiết</h2>';
        echo '<address>';

        if ($province_code) {
            $province_name = get_province_vietnam($province_code);
            echo '<p><strong>Tỉnh / Thành phố:</strong> ' . esc_html($province_name) . '</p>';
        }

        if ($district_code) {
            $district_name = get_district_vietnam($district_code);
            echo '<p><strong>Quận / Huyện:</strong> ' . esc_html($district_name) . '</p>';
        }

        if ($ward_code) {
            $ward_name = get_ward_vietnam($ward_code);
            echo '<p><strong>Phường / Xã:</strong> ' . esc_html($ward_name) . '</p>';
        }

        echo '</address>';
        echo '</section>';
    }
}

// Lưu vào user meta để tự động điền khi checkout lại
// Note: billing_state đã được WooCommerce tự động lưu vào user meta, không cần lưu lại
add_action('woocommerce_checkout_update_customer_data', 'save_custom_checkout_fields_to_user_meta', 10, 2);
function save_custom_checkout_fields_to_user_meta($customer, $data)
{
    // Kiểm tra customer object hợp lệ
    if (!$customer || !is_object($customer) || !method_exists($customer, 'get_id')) {
        return;
    }

    $user_id = $customer->get_id();
    if (!$user_id || !is_numeric($user_id)) {
        return;
    }

    // Lưu district
    if (isset($_POST['billing_district']) && !empty($_POST['billing_district'])) {
        update_user_meta($user_id, 'billing_district', sanitize_text_field($_POST['billing_district']));
    }

    // Lưu ward
    if (isset($_POST['billing_ward']) && !empty($_POST['billing_ward'])) {
        update_user_meta($user_id, 'billing_ward', sanitize_text_field($_POST['billing_ward']));
    }
}

// Tự động điền giá trị từ user meta khi checkout
// Note: billing_state đã được WooCommerce tự động populate, không cần xử lý
add_filter('woocommerce_checkout_get_value', 'populate_custom_checkout_fields_from_user_meta', 10, 2);
function populate_custom_checkout_fields_from_user_meta($value, $input)
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();

        if ($input === 'billing_district') {
            $value = get_user_meta($user_id, 'billing_district', true);
        }

        if ($input === 'billing_ward') {
            $value = get_user_meta($user_id, 'billing_ward', true);
        }
    }

    return $value;
}
