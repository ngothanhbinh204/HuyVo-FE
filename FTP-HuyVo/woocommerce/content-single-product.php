<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;


// $product_news_cat = get_field('product_news_cat', $product->get_id());
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$gallery = [];
$images = $product->get_gallery_image_ids();
$image_id = $product->get_image_id();
$gallery[] = wp_get_attachment_image_url($image_id, 'full');
if (!empty($images)) {
	foreach ($images as $image) {
		$gallery[] = wp_get_attachment_image_url($image, 'full');
	}
}
$care_guide = get_field('care_guide', 'option');
$shipping_returns = get_field('shipping_returns', 'option');
$single_product_size_guide = get_field('single_product_size_guide', 'option');
?>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class(' section-shop-detail ', $product); ?>>
	<div class="container max-w-full">
		<div class="product-detail-layout">
			<div class="product-media" id="productMediaScroll">
				<div class="product-thumbs swiper">
					<div class="thumb-scroll-indicator" id="mediaThumbDragBox"></div>
					<div class="thumb-list swiper-wrapper" id="mediaThumbDragParent">
						<?php if (!empty($gallery)) : ?>
						<?php foreach ($gallery as $image) : ?>
						<div class="product-thumb swiper-slide"><img class="lozad undefined"
								data-src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" />
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="product-images swiper">
					<div class="swiper-wrapper">
						<?php if (!empty($gallery)) : ?>
						<?php foreach ($gallery as $image) : ?>
						<div class="product-image swiper-slide"><img class="lozad undefined"
								data-src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" />
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="product-info">
				<div class="product-info-inner">
					<div class="breadcrumb-wrapper">
						<?php get_template_part('componets/section-breadcrumb'); ?>
					</div>
					<div class="wrapper-inner summary entry-summary">
						<h1 class="product-title title-24"><?php the_title(); ?></h1>
						<div class="price">
							<?php echo $product->get_price_html(); ?>
						</div>
						<div class="product-desc body-14">
							<div class="desc-wrapper"><?php echo $product->get_short_description(); ?></div>
						</div>
						<?php
						/**
						 * Hook: woocommerce_single_product_summary.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						do_action('woocommerce_single_product_summary');
						?>
						<div class="product-meta-tabs">
							<div class="meta-tab-item"><span
									class="tab-label"><?php _e('Product Details', 'huyvo'); ?></span>
								<div class="tab-content">
									<?php the_content(); ?>
								</div>
							</div>
							<div class="meta-tab-item"><span
									class="tab-label"><?php _e('Care Guide', 'huyvo'); ?></span>
								<div class="tab-content">
									<?php echo !empty($care_guide) ? apply_filters('the_content', $care_guide) : ''; ?>
								</div>
							</div>
							<div class="meta-tab-item"><span
									class="tab-label"><?php _e('Shipping & Returns', 'huyvo'); ?></span>
								<div class="tab-content">
									<?php echo !empty($shipping_returns) ? apply_filters('the_content', $shipping_returns) : ''; ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-size-guide" id="popupSizeGuide">
		<div class="popup-backdrop"></div>
		<div class="popup-content">
			<button class="popup-close"><i class="fa-light fa-xmark"></i></button>
			<div class="popup-header-center">
				<h2 class="popup-title">
					<?php echo !empty($single_product_size_guide['title']) ? $single_product_size_guide['title'] : ''; ?>
				</h2>
				<div class="popup-desc">
					<?php echo !empty($single_product_size_guide['description']) ? $single_product_size_guide['description'] : ''; ?>
				</div>
			</div>
			<div class="popup-tabs-header">
				<?php if (!empty($single_product_size_guide['tabs'])) : ?>
				<?php foreach ($single_product_size_guide['tabs'] as $key => $tab) : ?>
				<button class="tab-item <?php echo $key === 0 ? 'active' : ''; ?>"
					data-tab="tab-guide-<?php echo esc_attr($key); ?>"><?php echo esc_html($tab['title_tab']); ?></button>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<?php if (!empty($single_product_size_guide['tabs'])) : ?>
			<?php foreach ($single_product_size_guide['tabs'] as $key => $tab) : ?>
			<div class="popup-tab-content <?php echo $key === 0 ? 'active' : ''; ?>"
				id="tab-guide-<?php echo esc_attr($key); ?>">
				<?php if (! empty($tab['product_size_guide_content']) && is_array($tab['product_size_guide_content'])) : ?>
				<?php foreach ($tab['product_size_guide_content'] as  $row) : ?>
				<?php $layout = $row['acf_fc_layout']; ?>

				<?php if ($layout === 'title') : ?>
				<h3 class="measurement-unit"><?php echo esc_html($row['title']); ?></h3>
				<?php endif; ?>

			

				<?php if ($layout === 'image') : ?>
				<div class="measurement-guide-image">
					<div class="image-size">
						<?php
											$image = $row['image'];
											if ($image && is_array($image)) :
											?>
						<img class="lozad" data-src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt'] ? $image['alt'] : $image['title']); ?>" />
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>

                	<?php if ($layout === 'content_image') : ?>
				<div class="measurement-guide">
					<!-- Cột ảnh bên trái -->
					<div class="guide-image-col">
						<div class="guide-image">
							<?php
												$image = $row['image'];
												if ($image && is_array($image)) :
												?>
							<img class="lozad" data-src="<?php echo esc_url($image['url']); ?>"
								alt="<?php echo esc_attr($image['alt'] ? $image['alt'] : $image['title']); ?>" />
							<?php endif; ?>
						</div>
					</div>

					<!-- Cột text hướng dẫn bên phải -->
					<div class="guide-text-col">
						<?php if (! empty($row['content'])) : ?>
						<div class="desc">
                            <?php echo apply_filters('the_content', $row['content']); ?>
                        </div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
$categories = get_the_terms($product->get_id(), 'product_cat');
$category_ids = array();
foreach ($categories as $category) {
	$category_ids[] = $category->term_id;
}
$args = array(
	'post_type' => 'product',
	'post_status' => 'publish',
	'posts_per_page' => 4,
	'post__not_in' => array($product->get_id()),
	'orderby' => 'date',
	'order' => 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $category_ids,
		),
	),
);
$related_products = new WP_Query($args);
?>
<section class="section-related-products section-py">
	<div class="container max-w-full">

		<div class="category-block">
			<div class="category-block-header mb-4">
				<h2 class="category-title"><?php _e('RELATED PRODUCTS', 'huyvo'); ?></h2>
			</div>
			<div class="slider-wrapper">
				<div class="nav-btn nav-prev"><i class="fa-light fa-chevron-left"></i></div>
				<div class="category-product-slider">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php if ($related_products->have_posts()) : ?>
							<?php while ($related_products->have_posts()) : $related_products->the_post(); ?>
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
	</div>
</section>
<?php do_action('woocommerce_after_single_product'); ?>