<?php

/**
 * Template phần filter trên archive-product.php hoặc content-product.php
 */

global $wp_query;

// Lấy các filter từ ACF options (như bạn đang làm)
$list_filter_product = get_field('list_filter_product', 'option');
$term_parent = get_queried_object();
if ($term_parent) {
    $term_parent = !empty($term_parent->term_id) ? $term_parent->term_id : 0;
} else {
    $term_parent = 0;
}

if (!empty($list_filter_product)) :
?>
    <?php do_action('shop_filters_before'); ?>

    <div class="shop-filters">
        <?php foreach ($list_filter_product as $item) :
            // $query_type = $item['type_query'];
            $title = !empty($item['title']) ? $item['title'] : 'Filter';

            // Xác định taxonomy
            if ($item['type'] === 'category') {
                $taxonomy = 'product_cat';
            } elseif ($item['type'] === 'attribute') {
                $taxonomy = $item['attribute']; // ví dụ pa_color, pa_size
            } else {
                continue;
            }
            $args = [
                'taxonomy'   => $taxonomy,
                'hide_empty' => true, // chỉ lấy term có sản phẩm
            ];
            // if ($item['type'] === 'category') {
            //     $args['exclude'] = [$term_parent];
            // }

            // Lấy tất cả terms của taxonomy đó
            $terms = get_terms($args);

            if (is_wp_error($terms) || empty($terms)) continue;

            // Lấy các giá trị filter đang active từ URL
            $active_values = array();
            if (isset($_GET[$taxonomy])) {
                $active_values = array_map('trim', explode(',', sanitize_text_field($_GET[$taxonomy])));
            }
        ?>
            <div class="filter-dropdown" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                <button class="filter-trigger<?php echo !empty($active_values) ? ' has-active' : ''; ?>" type="button" data-filter-name="<?php echo esc_attr($title); ?>">
                    <span class="filter-label">
                        <?php
                        if (!empty($active_values)) {
                            $active_count = count($active_values);
                            if ($active_count === 1) {
                                $active_term = get_term_by('slug', $active_values[0], $taxonomy);
                                echo $active_term ? esc_html($active_term->name) : esc_html($title);
                            } else {
                                echo esc_html($title) . ' (' . $active_count . ')';
                            }
                        } else {
                            echo esc_html($title);
                        }
                        ?>
                    </span>
                    <i class="fa-light fa-chevron-down"></i>
                    <?php if (!empty($active_values)) : ?>
                        <span class="filter-clear" title="Clear filter"><i class="fa-solid fa-xmark"></i></span>
                    <?php endif; ?>
                </button>
                <div class="filter-dropdown-menu">
                    <ul class="filter-options" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                        <?php foreach ($terms as $term) :
                            $is_active = in_array($term->slug, $active_values);
                        ?>
                            <li class="filter-option<?php echo $is_active ? ' is-active' : ''; ?>" data-value="<?php echo esc_attr($term->slug); ?>">
                                <a href="#" data-filter-value="<?php echo esc_attr($term->slug); ?>">
                                    <span class="option-label"><?php echo esc_html($term->name); ?></span>
                                    <span class="option-count">(<?php echo esc_html($term->count); ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="product-count"><span><?php echo sprintf(__('%s PRODUCTS', 'huyvo'), $wp_query->found_posts); ?></span></div>

    <?php do_action('shop_filters_after'); ?>


<?php endif; ?>