<?php

/**
 * Template Name: Morderne
 */

global $post;
$morderne_banner = get_field('morderne_banner', $post->ID);
$morderne_description = get_field('morderne_description', $post->ID);
$morderne_category_blocks = get_field('morderne_category_blocks', $post->ID);

get_header();
?>
<section class="section-moderne-heritage">
    <div class="shop-banner">
        <?php if (!empty($morderne_banner['url'])) : ?>
            <img class="lozad undefined" data-src="<?php echo $morderne_banner['url']; ?>" alt="<?php echo $morderne_banner['alt'] ?? get_the_title(); ?>">

        <?php endif; ?>
    </div>
    <div class="container max-w-full">
        <div class="shop-description">
            <?php if (!empty($morderne_description)) : ?>
                <?php echo $morderne_description; ?>
            <?php endif; ?>
        </div>
        <div class="breadcrumb-wrapper">
            <?php get_template_part('components/section-breadcrumb'); ?>
        </div>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <div class="moderne-heritage-content">
            <?php if (!empty($morderne_category_blocks)) : ?>
                <?php foreach ($morderne_category_blocks as $morderne_category_block) :
                    $product_category = !empty($morderne_category_block['product_category']) ? $morderne_category_block['product_category'] : '';
                    $category_title = !empty($morderne_category_block['category_title']) ? $morderne_category_block['category_title'] : $product_category->name;
                    $view_all_link = !empty($morderne_category_block['view_all_link']) ? $morderne_category_block['view_all_link'] : get_term_link($product_category);
                    $products_limit = !empty($morderne_category_block['products_limit']) ? $morderne_category_block['products_limit'] : -1;
                    $products_orderby = !empty($morderne_category_block['products_orderby']) ? $morderne_category_block['products_orderby'] : 'date';

                    // Build query args based on orderby
                    $query_args = array(
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $product_category->term_id,
                            ),
                        ),
                        'posts_per_page' => $products_limit,
                    );

                    // Handle different orderby options for WooCommerce
                    switch ($products_orderby) {
                        case 'price':
                            $query_args['meta_key'] = '_price';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order'] = 'ASC';
                            break;
                        case 'popularity':
                            $query_args['meta_key'] = 'total_sales';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order'] = 'DESC';
                            break;
                        case 'rating':
                            $query_args['meta_key'] = '_wc_average_rating';
                            $query_args['orderby'] = 'meta_value_num';
                            $query_args['order'] = 'DESC';
                            break;
                        case 'menu_order':
                            $query_args['orderby'] = 'menu_order';
                            $query_args['order'] = 'ASC';
                            break;
                        case 'title':
                            $query_args['orderby'] = 'title';
                            $query_args['order'] = 'ASC';
                            break;
                        case 'rand':
                            $query_args['orderby'] = 'rand';
                            break;
                        case 'date':
                        default:
                            $query_args['orderby'] = 'date';
                            $query_args['order'] = 'DESC';
                            break;
                    }

                    $products = new WP_Query($query_args);
                ?>
                    <div class="category-block">
                        <div class="category-block-header">
                            <h2 class="category-title"><?php echo $category_title; ?></h2><a class="view-all-link" href="<?php echo $view_all_link; ?>"><?php _e('VIEW ALL', 'huyvo'); ?></a>
                        </div>
                        <div class="slider-wrapper">
                            <div class="nav-btn nav-prev"><i class="fa-light fa-chevron-left"></i></div>
                            <div class="category-product-slider">
                                <div class="swiper">
                                    <div class="swiper-wrapper">
                                        <?php if ($products->have_posts()) : ?>
                                            <?php while ($products->have_posts()) : $products->the_post(); ?>
                                                <div class="swiper-slide">
                                                    <?php wc_get_template_part('content', 'product'); ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="nav-btn nav-next"><i class="fa-light fa-chevron-right"></i></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php get_footer(); ?>