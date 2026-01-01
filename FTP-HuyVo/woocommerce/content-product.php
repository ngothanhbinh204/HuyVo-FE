<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}
$image_main = get_the_post_thumbnail_url($product->get_id());
$get_gallery = $product->get_gallery_image_ids();
if ($get_gallery) {
	$image_hover = wp_get_attachment_url($get_gallery[0]);
} else {
	$image_hover = $image_main;
}
?>
<div <?php wc_product_class('product-item', $product); ?>>
	<div class="product-img"><a class="product-link" href="<?php echo get_permalink($product->get_id()); ?>">
			<div class="product-images">
				<img class="lozad img-main" data-src="<?php echo $image_main; ?>" alt="" />
				<img class="lozad img-hover" data-src="<?php echo $image_hover; ?>" alt="" />
			</div>
		</a></div>
	<div class="product-info">
		<div class="product-tags">
			<?php $product_tags = get_the_terms($product->get_id(), 'product_tag'); ?>
			<?php if ($product_tags) : ?>
				<?php foreach ($product_tags as $product_tag) : ?>
					<span class="product-tag"><?php echo $product_tag->name; ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<h3 class="product-name"><a href="<?php echo get_permalink($product->get_id()); ?>"><?php echo get_the_title($product->get_id()); ?></a></h3>
		<p class="product-price"><?php echo $product->get_price_html(); ?></p>
	</div>
	<div class="product-variants">
		<?php
		if ($product->is_type('variable')) :
			$attributes = $product->get_attributes();
		?>
			<?php foreach ($attributes as $attribute_name => $options) :
				$name_not_pa = str_replace('pa_', '', $attribute_name);
				if ($name_not_pa == 'color' || $name_not_pa == 'size') {
					$name_not_pa = $name_not_pa . 's';
				}
			?>
				<ul class="product-variants-list variant-<?php echo esc_attr($name_not_pa); ?>" data-attribute-name="<?php echo esc_attr($attribute_name); ?>">
					<?php foreach ($options['options'] as $option_id) :
						$term = get_term_by('id', $option_id, $attribute_name);
						$color = get_term_meta($term->term_id, 'product_attribute_color', true);
						$image_id = get_term_meta($term->term_id, 'product_attribute_image', true);
						$fallback_image = wp_get_attachment_url($image_id);
						$variation_image = $variation_images[$term->slug] ?? '';
						$final_image = $fallback_image;
					?>
						<?php if ($fallback_image) : ?>
							<li>
								<a href="<?php echo get_permalink($product->get_id()); ?>?attribute_<?php echo esc_attr($attribute_name); ?>=<?php echo esc_attr($term->slug); ?>" title="<?php echo $term->name; ?>"
									data-color="<?php echo esc_attr($term->slug); ?>"
									data-color-code="<?php echo esc_attr($color); ?>"
									data-thumbnail="<?php echo esc_url($variation_image); ?>"
									data-attribute="<?php echo esc_attr($attribute_name); ?>"
									style=" background-image: url(<?php echo esc_url($final_image); ?>); background-size: cover;">
									<?php echo $term->name; ?></a>
							</li>
						<?php else : ?>
							<li>
								<a href="<?php echo get_permalink($product->get_id()); ?>?attribute_<?php echo esc_attr($attribute_name); ?>=<?php echo esc_attr($term->slug); ?>" title="<?php echo $term->name; ?>"
									data-color="<?php echo esc_attr($term->slug); ?>"
									data-color-code="<?php echo esc_attr($color); ?>"
									data-thumbnail="<?php echo esc_url($variation_image); ?>"
									data-attribute="<?php echo esc_attr($attribute_name); ?>"
									style=" background-color: <?php echo esc_attr($color); ?>;">
									<?php if (empty($color)) : ?>
										<?php echo $term->name; ?>
									<?php endif; ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>