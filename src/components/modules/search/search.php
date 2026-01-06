<?php get_header() ?>

<div class="single-frame section">

	<section class="page-search-results">
		<div class="container">
			<div class="search-header">
				<h1 class="page-title"><?php _e('Tìm kiếm', 'huyvo') ?></h1>
				<div class="search-query-text"><?php _e('Kết quả tìm kiếm từ khóa:', 'huyvo') ?> "
					<span><?php echo get_search_query() ?></span> "
				</div>
			</div>
			
			<?php
			$post_types = array(
				'product' => __('Sản phẩm', 'huyvo'),
				'post'    => __('Bài viết', 'huyvo'),
			);

			$has_global_results = false;

			foreach ($post_types as $post_type => $post_type_label) {
				$type_query = new WP_Query(array(
					's'              => get_search_query(),
					'post_type'      => $post_type,
					'posts_per_page' => 8,
				));

				if ($type_query->have_posts()) {
					$has_global_results = true;
			?>
				<div class="post-type-section <?php echo esc_attr($post_type); ?>-section">
					<h2 class="section-title">
						<?php echo esc_html($post_type_label); ?>
						<span class="count">(<?php echo $type_query->found_posts; ?>)</span>
					</h2>
					<div class="search-grid">
						<?php while ($type_query->have_posts()) : $type_query->the_post(); ?>
							<?php if ($post_type == 'product') : ?>
								<?php wc_get_template_part('content', 'product'); ?>
							<?php else : ?>
								<?php get_template_part('components/content-post'); ?>
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
				</div>
			<?php
				}
				wp_reset_postdata();
			}

			if (!$has_global_results) :
			?>
				<div class="no-results">
					<p><?php _e('Không tìm thấy kết quả nào phù hợp với từ khóa của bạn.', 'huyvo'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>
<?php get_footer() ?>