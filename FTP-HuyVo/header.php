<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package quatatheme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Space+Grotesk:wght@300..700&family=Tektur:wght@400..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<?php
$keyword_search_header = get_field('keyword_search_header', 'option');
?>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header class="header">
		<div class="container">
			<div class="header-wrapper">
				<nav class="header-left">
					<?php echo custom_mega_menu(); ?>
				</nav>
				<?php the_custom_logo(); ?>
				<div class="header-right">
					<button class="header-icon search-btn" type="button" aria-label="Search"><i class="fa-regular fa-magnifying-glass"></i></button>
					<?php if (is_user_logged_in()) : ?>
						<a class="header-icon user-btn"
							href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"
							aria-label="Account">
							<i class="fa-regular fa-user"></i>
							<?php echo esc_html(wp_get_current_user()->display_name); ?>
						</a>
					<?php else : ?>
						<a class="header-icon user-btn"
							href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
							aria-label="Account">
							<i class="fa-regular fa-user"></i>
						</a>
					<?php endif; ?>

					<a class="header-icon cart-btn" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Cart"><i class="fa-regular fa-bag-shopping"></i> <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span></a>
					<button class="header-hamburger" type="button" aria-label="Menu"><span></span><span></span><span></span></button>
				</div>
			</div>
		</div>
	</header>
	<div class="mobile-menu">
		<div class="mobile-menu-overlay"></div>
		<div class="mobile-menu-inner">
			<button class="mobile-menu-close" type="button" aria-label="Close Menu"><i class="fa-light fa-xmark"></i></button>
			<div class="mobile-menu-body">
				<nav class="mobile-nav">
					<?php wp_nav_menu(array('theme_location' => 'primary-mobile', 'menu_class' => 'mobile-menu-list', 'container' => false, 'walker' => new Mobile_Menu_Walker())); ?>
				</nav>
			</div>
			<div class="mobile-menu-footer"><a class="shop-all-link" href="<?php wc_get_page_permalink('shop'); ?>"><span><?php _e('SHOP ALL', 'huyvo'); ?></span><i class="fa-light fa-arrow-up-right"></i></a></div>
		</div>
	</div>
	<div class="header-search-overlay">
		<div class="search-overlay-backdrop"></div>
		<div class="search-overlay-content">
			<div class="container">
				<button class="search-close" type="button" aria-label="Close Search"><i class="fa-solid fa-xmark"></i></button>
				<div class="search-form-wrapper">
					<form class="search-form" action="<?php echo home_url('/'); ?>" method="GET">
						<input class="search-input" type="text" name="s" placeholder="<?php _e('Search...', 'huyvo'); ?>" autocomplete="off">
						<button class="search-submit" type="submit" aria-label="Submit Search"><i class="fa-regular fa-magnifying-glass"></i></button>
					</form>
				</div>
				<div class="search-suggestions">
					<div class="search-popular">
						<h4 class="search-section-title"><?php _e('Popular Searches', 'huyvo'); ?></h4>
						<ul class="search-tags">
							<?php if (!empty($keyword_search_header)) : ?>
								<?php foreach ($keyword_search_header as $item) : ?>
									<li><a href="<?php echo home_url('/?s=' . urlencode($item['keyword'])); ?>"><?php echo $item['keyword']; ?></a></li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mini-cart-wrapper">
		<div class="mini-cart-header">
			<h4 class="mini-cart-title"><?php _e('Shopping Cart', 'huyvo'); ?></h4>
			<button class="mini-cart-close" type="button" aria-label="Close Mini Cart"><i class="fa-light fa-xmark"></i></button>
		</div>
		<div class="widget_shopping_cart_content">
			<?php woocommerce_mini_cart(); ?>
		</div>
	</div>
	<main>