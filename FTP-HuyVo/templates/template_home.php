<?php

/**
 * The template for displaying the home page
 * Template Name: Trang chủ
 * @package quatatheme
 */
global $post;
$home_banner = get_field('home_banner', $post->ID);
$home_explore = get_field('home_explore', $post->ID);
$home_new_arrivals = get_field('home_new_arrivals', $post->ID);
$home_popular = get_field('home_popular', $post->ID);
$home_banner_cta = get_field('home_banner_cta', $post->ID);
$home_categories = get_field('home_categories', $post->ID);


get_header();
?>
<h1 class="hidden"><?php echo get_option('blogname'); ?></h1>
<section class="section-home-banner">
	<div class="banner-wrapper">
		<div class="banner-media">
			<div class="banner-poster">
				<?php if (!empty($home_banner['banner_poster'])) : ?>
				<img class="lozad undefined" data-src="<?php echo $home_banner['banner_poster']['url']; ?>"
					alt="<?php echo $home_banner['banner_poster']['alt']  ?? get_the_title($post->ID); ?>" />
				<?php endif; ?>
			</div>
			<?php if (!empty($home_banner['banner_video'])) : ?>
			<video class="banner-video" autoplay muted loop playsinline preload="metadata"
				poster="<?php echo $home_banner['banner_poster']['url'] ?? ''; ?>">
				<source src="<?php echo $home_banner['banner_video']['url']; ?>" type="video/mp4">
			</video>
			<?php endif; ?>
			<button class="sound-toggle" type="button" aria-label="Toggle Sound">
				<i class="fa-light fa-volume-xmark icon-muted"></i><i
					class="fa-light fa-volume icon-unmuted"></i></button>
		</div>
		<div class="banner-content">
			<div class="container">
				<div class="banner-top-text">
					<?php if (!empty($home_banner['banner_text_line_1'])) : ?>
					<p class="text-line"><?php echo $home_banner['banner_text_line_1']; ?></p>
					<?php endif; ?>
					<?php if (!empty($home_banner['banner_text_line_2'])) : ?>
					<p class="text-line"><?php echo $home_banner['banner_text_line_2']; ?></p>
					<?php endif; ?>
				</div>
				<div class="banner-bottom-content">
					<?php if (!empty($home_banner['banner_subtitle'])) : ?>
					<p class="banner-subtitle"><?php echo $home_banner['banner_subtitle']; ?></p>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>
<section class="section-home-explore">
	<div class="container max-w-full">
		<div class="section-title">
			<?php if (!empty($home_explore['explore_subtitle'])) : ?>
			<span class="section-subtitle"><?php echo $home_explore['explore_subtitle']; ?></span>
			<?php endif; ?>
			<?php if (!empty($home_explore['explore_heading'])) : ?>
			<h2 class="section-heading"><?php echo $home_explore['explore_heading']; ?></h2>
			<?php endif; ?>
		</div>
		<div class="explore-slider">
			<div class="swiper">
				<div class="swiper-wrapper">
					<?php if (!empty($home_explore['explore_images'])) : ?>
					<?php foreach ($home_explore['explore_images'] as $item) : ?>
					<div class="swiper-slide">
						<div class="explore-item">
							<img class="lozad undefined" data-src="<?php echo $item['url']; ?>"
								alt="<?php echo $item['alt'] ?? get_the_title($post->ID); ?>" />
						</div>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
</section>
<section class="section section-home-new-arrivals">
	<div class="container max-w-full">
		<div class="section-header">
			<div class="title-wrapper">
				<?php if (!empty($home_new_arrivals['new_arrivals_heading'])) : ?>
				<h2 class="section-heading"><?php echo $home_new_arrivals['new_arrivals_heading']; ?></h2>
				<?php endif; ?>
				<?php if (!empty($home_new_arrivals['new_arrivals_category'])) : ?>
				<a class="view-all-link"
					href="<?php echo get_term_link($home_new_arrivals['new_arrivals_category']->term_id); ?>"><?php _e('VIEW ALL', 'huyvo'); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $home_new_arrivals['new_arrivals_category']->term_id,
                ),
            ),
        );
        $products = new WP_Query($args);
        if ($products->have_posts()) :
        ?>
		<div class="slider-wrapper">
			<button class="nav-btn nav-prev" type="button" aria-label="Previous"><i
					class="fa-light fa-chevron-left"></i></button>
			<div class="new-arrivals-slider">
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php while ($products->have_posts()) : $products->the_post(); ?>
						<div class="swiper-slide">
							<?php wc_get_template_part('content', 'product'); ?>
						</div>
						<?php endwhile; ?>

					</div>
				</div>
			</div>
			<button class="nav-btn nav-next" type="button" aria-label="Next"><i
					class="fa-light fa-chevron-right"></i></button>
		</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
<section class="section section-home-popular">
	<div class="container max-w-full">
		<div class="popular-wrapper">
			<div class="popular-left">
				<div class="popular-grid">
					<?php
                    $count = count($home_popular['popular_images']);
                    if (!empty($home_popular['popular_images'])) : ?>
					<?php foreach ($home_popular['popular_images'] as $key => $image) : ?>
					<div class="popular-item <?php echo $key + 1 > 6 ? 'hidden' : ''; ?>">
						<div class="item-image"><img class="lozad undefined" data-src="<?php echo $image['url']; ?>"
								alt="<?php echo $image['alt'] ?? get_the_title($post->ID); ?>" />
							<div class="item-number"><?php echo $key + 1; ?></div>
						</div>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php if ($count > 6) : ?>
				<div class="popular-action"><a class="btn-primary-1 btn-view-more"
						href="#"><?php _e('VIEW MORE', 'huyvo'); ?></a></div>
				<?php endif; ?>
			</div>
			<div class="popular-content">
				<div class="content-inner">
					<?php if (!empty($home_popular['popular_heading'])) : ?>
					<h2 class="section-heading"><?php echo $home_popular['popular_heading']; ?></h2>
					<?php endif; ?>
					<div class="section-desc">
						<div class="section-desc-wrapper">
							<?php if (!empty($home_popular['popular_description'])) : ?>
							<?php echo $home_popular['popular_description']; ?>
							<?php endif; ?>
						</div>
						<div class="section-desc-more">
							<button class="btn-readmore"><span>Read more</span><em
									class="icon chevdown fa-solid fa-chevron-down"></em></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php if (!empty($home_banner_cta)) : ?>
<?php foreach ($home_banner_cta as $item) : ?>
<section class="section-home-banner-cta">
	<div class="banner-wrapper">
		<div class="banner-image">
			<?php if (!empty($item['cta_banner_image']['url'])) : ?>
			<img class="lozad undefined" data-src="<?php echo $item['cta_banner_image']['url']; ?>"
				alt="<?php echo $item['cta_banner_image']['alt'] ?? get_the_title($post->ID); ?>" />
			<?php endif; ?>
		</div>
		<div class="banner-content">
			<div class="container">
				<div class="content-inner">
					<?php if (!empty($item['cta_heading'])) : ?>
					<h2 class="banner-heading"><?php echo $item['cta_heading']; ?></h2>
					<?php endif; ?>
					<?php if (!empty($item['cta_button'])) : ?>
					<a class="btn-primary-3"
						href="<?php echo $item['cta_button']['url']; ?>"><?php echo $item['cta_button']['title']; ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endforeach; ?>
<?php endif; ?>
<section class="section section-home-categories">
	<div class="categories-grid">
		<?php if (!empty($home_categories)) : ?>
		<?php foreach ($home_categories as $item) : ?>
		<div class="card-image-overlay">
			<div class="card-image">
				<?php if (!empty($item['category_card_image']['url'])) : ?>
				<img class="lozad undefined" data-src="<?php echo $item['category_card_image']['url']; ?>"
					alt="<?php echo $item['category_card_image']['alt'] ?? get_the_title($post->ID); ?>" />
				<?php endif; ?>
				<div class="card-overlay">
					<?php if (!empty($item['category_card_title'])) : ?>
					<h3 class="card-title"><?php echo $item['category_card_title']; ?></h3>
					<?php endif; ?>
					<?php if (!empty($item['category_card_button'])) : ?>
					<a class="btn-outline"
						href="<?php echo $item['category_card_button']['url']; ?>"><?php echo $item['category_card_button']['title']; ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>