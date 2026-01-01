<?php get_header() ?>

<div class="single-frame">

	<section class="news-list-2 max-md:py-[32px] md:py-18  ">
		<div class="container">
			<h1 class="block-title text-center mb-30px"><?php _e('Tìm kiếm', 'huyvo') ?></h1>
			<div class="search-query"><?php _e('Kết quả tìm kiếm từ khóa:', 'huyvo') ?> "
				<span><?php echo get_search_query() ?></span> "
			</div>
			<?php
			$post_types = array(
				'product' => __('Sản phẩm', 'huyvo'),
				'post' => __('Bài viết', 'huyvo'),

			);

			$has_results = false;

			foreach ($post_types as $post_type => $post_type_label) {
				$type_query = new WP_Query(array(
					's' => get_search_query(),
					'post_type' => $post_type,
					'posts_per_page' => 8,
				));

				if ($type_query->have_posts()) {
					$has_results = true;
				}

				// Hiển thị section cho post_type
			?>
				<div class="post-type-section <?php echo esc_attr($post_type); ?>-section mb-10">
					<h2 class="section-title title-48 mb-10"><?php echo esc_html($post_type_label); ?></h2>
					<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 lg:gap-base gap-3">
						<?php if ($type_query->have_posts()) : ?>
							<?php while ($type_query->have_posts()) : $type_query->the_post(); ?>
								<?php if ($post_type == 'product') : ?>
									<?php wc_get_template_part('content', 'product'); ?>

								<?php else : ?>
									<?php get_template_part('components/content-post'); ?>
								<?php endif; ?>

							<?php endwhile; ?>
						<?php else : ?>
							<p><?php _e('Không tìm thấy kết quả nào.', 'forestbay'); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php
				wp_reset_postdata();
			}
			?>
		</div>
	</section>
</div>
<?php get_footer() ?>