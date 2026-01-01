<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;
$term = get_queried_object();
$title = is_shop() ? 'SHOP ALL' : $term->name;

get_header('shop');
?>
<section class="section-shop-list">
	<div class="shop-banner"><img class="lozad undefined" data-src="<?php echo get_template_directory_uri(); ?>/img/aodai4.png" alt="" />
	</div>
	<div class="wrapper-shop container max-w-full">
		<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action('woocommerce_before_main_content');
		?>
		<div class="shop-description">
			<?php echo $term->description; ?>
		</div>
		<div class="breadcrumb-wrapper">
			<?php get_template_part('components/section-breadcrumb'); ?>
		</div>
		<h1 class="page-title"><?php echo $title; ?></h1>
		<?php get_template_part('components/filter_shop'); ?>
		<?php
		/**
		 * Hook: woocommerce_shop_loop_header.
		 *
		 * @since 8.6.0
		 *
		 * @hooked woocommerce_product_taxonomy_archive_header - 10
		 */
		do_action('woocommerce_shop_loop_header');
		if (woocommerce_product_loop()) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action('woocommerce_before_shop_loop');
			echo '<div class="products-wrapper">';

			woocommerce_product_loop_start();

			if (wc_get_loop_prop('total')) {
				while (have_posts()) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}
			}

			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action('woocommerce_after_shop_loop');

			// Load More button
			global $wp_query;
			$current_page = max(1, get_query_var('paged'));
			$max_pages = $wp_query->max_num_pages;

			if ($max_pages > 1 && $current_page < $max_pages) :
		?>
				<div class="btn-action load-more-wrapper">
					<button class="btn-primary-1 load-more-products"
						data-page="<?php echo esc_attr($current_page); ?>"
						data-max-pages="<?php echo esc_attr($max_pages); ?>">
						<?php _e('View More', 'huyvo'); ?>
					</button>
				</div>
		<?php
			endif;
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action('woocommerce_no_products_found');
		}
		echo '</div>';
		?>
	</div>
	<?php
	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
	?>
</section>
<?php
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');

get_footer('shop');
