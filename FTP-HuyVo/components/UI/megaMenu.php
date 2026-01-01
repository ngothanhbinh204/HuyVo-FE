<?php
$menu_name = 'primary';
$locations = get_nav_menu_locations();

if (!isset($locations[$menu_name]))
	return;

$menu_items = wp_get_nav_menu_items($locations[$menu_name], [
	'update_post_term_cache' => false,
	'suppress_filters' => false
]);

if (empty($menu_items))
	return;

// Build menu hierarchy and set active states

if (!function_exists('build_menu_with_active_states')) {
	function build_menu_with_active_states($menu_items)
	{
		$ACTIVE_CLASS = "current-custom-active current-post-ancestor";

		$current_url = trailingslashit(home_url(add_query_arg(array(), $_SERVER['REQUEST_URI'])));
		$menu_hierarchy = array();
		$items_by_id = array();
		$current_post_cats = array(); // Initialize as empty array

		if (is_single()) {
			$post_id = get_the_ID();
			$post_type = get_post_type($post_id);

			if ($post_type == 'post') {
				$categories = get_the_category($post_id);
				foreach ($categories as $cat) {
					$current_post_cats[] = $cat->term_id;
					// Also add all parent categories
					$parent_id = $cat->parent;
					while ($parent_id) {
						$current_post_cats[] = $parent_id;
						$parent = get_term($parent_id, 'category');
						$parent_id = $parent->parent;
					}
				}
			} else {
				// Handle custom post types and their taxonomies
				$taxonomies = get_object_taxonomies($post_type);
				foreach ($taxonomies as $tax) {
					$terms = get_the_terms($post_id, $tax);
					if ($terms && !is_wp_error($terms)) {
						foreach ($terms as $term) {
							$current_post_cats[] = $term->term_id;
							// Also add all parent terms
							$parent_id = $term->parent;
							while ($parent_id) {
								$current_post_cats[] = $parent_id;
								$parent = get_term($parent_id, $tax);
								$parent_id = $parent->parent;
							}
						}
					}
				}
			}
		}
		foreach ($menu_items as $item) {
			$item->is_active = false;
			$item->classes = array_unique((array) $item->classes);

			// Broader active state check
			$item_url = trailingslashit($item->url);
			$is_home_url = trailingslashit(home_url()) === $item_url;
			if (
				$item_url === $current_url || // Exact URL match
				(!$is_home_url && strpos($current_url, $item_url) === 0) || // Current URL starts with item URL (for parent pages, but not home)
				in_array($ACTIVE_CLASS, $item->classes) || // Already marked active
				(is_singular() && get_the_ID() == $item->object_id) || // Matches current post/page
				(is_single() && $item->type == 'taxonomy' && in_array($item->object_id, $current_post_cats))
			) {
				$item->is_active = true;
			}
			// $item->title = $item->title . 'fix';
			$items_by_id[$item->ID] = $item;
			$item->children = array();
		}

		// Second pass: build hierarchy
		foreach ($menu_items as $item) {
			if ($item->menu_item_parent == 0) {
				$menu_hierarchy[] = $item;
			} elseif (isset($items_by_id[$item->menu_item_parent])) {
				$parent = $items_by_id[$item->menu_item_parent];
				$parent->children[] = $item;
			}
		}

		// Third pass: propagate active states from children to parents
		$shop_all_url = trailingslashit(home_url('/shop/')); // Adjust this to your actual "Shop All" URL
		$changed = true;
		// Keep propagating until no more changes (handles multiple levels)
		while ($changed) {
			$changed = false;
			foreach ($menu_items as $item) {
				if ($item->is_active && $item->menu_item_parent != 0) {
					// Propagate active state to parent
					if (isset($items_by_id[$item->menu_item_parent])) {
						$parent = $items_by_id[$item->menu_item_parent];
						$parent_url = trailingslashit($parent->url);

						// Only propagate if parent is not "Shop All" and not already active
						if ($parent_url !== $shop_all_url && !$parent->is_active) {
							$parent->is_active = true;
							$changed = true;
						}
					}
				}
			}
		}

		// Also propagate up the hierarchy for all active items
		foreach ($menu_items as $item) {
			if ($item->is_active && $item->menu_item_parent != 0) {
				if (isset($items_by_id[$item->menu_item_parent])) {
					$current_parent = $items_by_id[$item->menu_item_parent];
					while (
						$current_parent->menu_item_parent != 0 &&
						isset($items_by_id[$current_parent->menu_item_parent])
					) {
						$current_parent = $items_by_id[$current_parent->menu_item_parent];
						// Only propagate if not "Shop All"
						$current_parent_url = trailingslashit($current_parent->url);
						if ($current_parent_url === $shop_all_url) {
							break;
						}
						if (!$current_parent->is_active) {
							$current_parent->is_active = true;
						}
					}
				}
			}
		}

		// Fourth pass: ensure active class is added to all active items (including propagated parents)
		foreach ($menu_items as $item) {
			if ($item->is_active && !in_array($ACTIVE_CLASS, $item->classes)) {
				$item->classes[] = $ACTIVE_CLASS;
			}
		}


		return $menu_hierarchy;
	}
}


// Apply the new function
$menu_items = build_menu_with_active_states($menu_items);

if (!function_exists('get_item_class')) {
	function get_item_class($menu_item)
	{
		global $ACTIVE_CLASS;
		$classes = array();

		$is_mega_menu = get_field('mega_menu', $menu_item);
		$original_id = 0;
		if (

			is_page_template('templates/template-about-history.php')
		) {
			$pages = get_pages(array(
				'meta_key' => '_wp_page_template',
				'meta_value' => 'templates/template_about.php'
			));

			if (!empty($pages)) {
				$original_id = $pages[0]->ID;
			}
		}
		$languages = apply_filters('wpml_active_languages', NULL, []);

		if (!empty($languages)) {
			foreach ($languages as $lang_code => $lang) {
				$translated_id = apply_filters('wpml_object_id', $original_id, 'nav_menu_item', true, $lang_code);

				if ($menu_item->object_id == $translated_id) {
					$classes[] = "current-custom-active current-post-ancestor";
				}
			}
		}


		if ($menu_item->children)
			$classes[] = 'menu-item has-children menu-item-has-children';
		if ($is_mega_menu)
			$classes[] = 'menu-item has-children menu-item-has-children has-dropdown';
		if ($menu_item->is_active)
			$classes[] = $ACTIVE_CLASS;
		$classes[] = 'menu-item';
		$classes = array_merge($classes, array_filter($menu_item->classes));
		return implode(' ', array_unique($classes));
	}
}
?>
<?php if ($menu_items): ?>

	<ul class="menu menu-list">
		<?php foreach ($menu_items as $parent_key => $menu_level_1): ?>
			<?php
			$is_mega_menu = get_field('mega_menu', $menu_level_1);
			$image_1 = get_field('image_1', $menu_level_1);
			$image_2 = get_field('image_2', $menu_level_1);
			?>

			<li class="<?= get_item_class($menu_level_1) ?>">
				<?php if ($menu_level_1->children): ?>
					<a class="btn-hover with-bg header-link header-link" href="<?= $menu_level_1->url ?>" title="<?= $menu_level_1->title ?>" <?= $menu_level_1->target ? ' target="' . htmlspecialchars($menu_level_1->target, ENT_QUOTES) . '"' : '' ?>>
						<span class="btn-inner">
							<span class="text main-text"><?= $menu_level_1->title ?></span>
							<span class="text hover-text" aria-hidden="true" data-text="<?= $menu_level_1->title ?>"></span>
						</span>
					</a>

					<?php if (!$is_mega_menu): ?>
						<ul class="sub-menu">
							<?php foreach ($menu_level_1->children as $menu_level_2): ?>
								<li class="<?= get_item_class($menu_level_2) ?>">
									<a href="<?= $menu_level_2->url ?>" title="<?= $menu_level_2->title ?>" <?= $menu_level_2->target ? ' target="' . htmlspecialchars($menu_level_2->target, ENT_QUOTES) . '"' : '' ?>><?= $menu_level_2->title ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

				<?php elseif (!$menu_level_1->children && !$is_mega_menu): ?>
						<a class="btn-hover with-bg header-link header-link" href="<?= $menu_level_1->url ?>" title="<?= $menu_level_1->title ?>" <?= $menu_level_1->target ? ' target="' . htmlspecialchars($menu_level_1->target, ENT_QUOTES) . '"' : '' ?>>
							<span class="btn-inner">
								<span class="text main-text"><?= $menu_level_1->title ?></span>
								<span class="text hover-text" aria-hidden="true" data-text="<?= $menu_level_1->title ?>"></span>
							</span>
						</a>
				<?php endif; ?>
				<?php if ($is_mega_menu): ?>
					<?php
					$image_first = '';
					$title_first = '';
					$url_first = '';
					$menu_level_2_children = '';
					?>
					<div class="mega-menu">
						<div class="container">
							<div class="mega-menu-wrapper">
								<?php foreach ($menu_level_1->children as $menu_level_2): ?>
									<div class="mega-menu-column">
										<div class="column-title"><?php echo $menu_level_2->title; ?></div>
										<ul class="sub-menu menu-list">
											<?php foreach ($menu_level_2->children as $menu_level_3): ?>
												<li class="menu-item"><a href="<?php echo $menu_level_3->url; ?>"><?php echo $menu_level_3->title; ?></a></li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endforeach; ?>
								<div class="mega-menu-images">
									<div class="image-wrapper">
										<?php if (!empty($image_1['url'])) : ?>
											<img class="lozad undefined" data-src="<?php echo $image_1['url']; ?>" alt="<?php echo $image_1['alt']; ?>" />
										<?php endif; ?>
									</div>
									<div class="image-wrapper">
										<?php if (!empty($image_2['url'])) : ?>
											<img class="lozad undefined" data-src="<?php echo $image_2['url']; ?>" alt="<?php echo $image_2['alt']; ?>" />
										<?php endif; ?>
									</div>
								</div>
								<div class="mega-menu-footer"><a class="shop-all-link" href="<?php echo $menu_level_1->url; ?>"><span><?php _e('SHOP ALL', 'huyvo'); ?></span><i class="fa-regular fa-arrow-up-right"></i></a></div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>