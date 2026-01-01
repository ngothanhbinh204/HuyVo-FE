<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package quatatheme
 */
$footer_logo = get_field('footer_logo', 'option');
$footer_desc = get_field('footer_desc', 'option');
?>
</main>
<footer class="footer">
	<div class="footer-main">
		<div class="container max-w-full">
			<div class="footer-grid">
				<div class="footer-col footer-brand">
					<?php if (!empty($footer_logo['url'])) : ?>
						<a class="brand-logo" href="<?php echo home_url('/'); ?>">
							<img class="lozad undefined" data-src="<?php echo $footer_logo['url']; ?>" alt="<?php echo $footer_logo['alt']; ?>">
						</a>
					<?php endif; ?>
					<?php if (!empty($footer_desc)) : ?>
						<p class="brand-desc"><?php echo $footer_desc; ?></p>
					<?php endif; ?>
				</div>
				<div class="footer-col footer-menus">
					<div class="footer-menu">

						<?php wp_nav_menu(array('theme_location' => 'footer-menu-1', 'menu_class' => 'footer-menu-list', 'container' => false)); ?>
					</div>
					<div class="footer-menu">

						<?php wp_nav_menu(array('theme_location' => 'footer-menu-2', 'menu_class' => 'footer-menu-list', 'container' => false)); ?>
					</div>
				</div>
				<div class="footer-col footer-newsletter">
					<?php echo do_shortcode('[contact-form-7 id="1946ec0" title="Form Mail"]'); ?>
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="welcome-popups-wrapper">
	<div class="popup-overlay" id="popup-shipping" data-popup="shipping">
		<div class="popup-container">
			<button class="popup-close" type="button" aria-label="Close popup"><i class="fa-light fa-xmark"></i></button>
			<div class="popup-content">
				<h2 class="popup-title">SELECT YOUR SHIPPING LOCATION</h2>
				<p class="popup-desc">Please note that the items in your cart will be shipped to your selected country.</p>
				<div class="popup-form">
					<div class="form-row">
						<div class="form-col">
							<label class="form-label">Country</label>
							<div class="select-wrapper">
								<select class="form-select" id="shipping-country" name="country">
									<option value="vietnam" selected="selected">Vietnam</option>
									<option value="usa">United States</option>
									<option value="uk">United Kingdom</option>
									<option value="japan">Japan</option>
									<option value="korea">South Korea</option>
									<option value="singapore">Singapore</option>
									<option value="other">Other</option>
								</select><i class="fa-light fa-chevron-down"></i>
							</div>
						</div>
						<div class="form-col">
							<label class="form-label">Language</label>
							<div class="select-wrapper">
								<select class="form-select" id="shipping-language" name="language">
									<option value="vietnamese" selected="selected">Vietnamese</option>
									<option value="english">English</option>
								</select><i class="fa-light fa-chevron-down"></i>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<button class="btn-submit" type="button" data-action="submit-shipping">SHOP NOW</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-overlay" id="popup-newsletter" data-popup="newsletter">
		<div class="popup-container">
			<button class="popup-close" type="button" aria-label="Close popup"><i class="fa-light fa-xmark"></i></button>
			<div class="popup-content">
				<div class="popup-logo">
					<?php if (!empty($footer_logo['url'])) : ?>
						<img class="lozad undefined" data-src="<?php echo $footer_logo['url']; ?>" alt="<?php echo $footer_logo['alt']; ?>">
					<?php endif; ?>
				</div>
				<h2 class="popup-title"><?php _e('SUBSCRIBE TO OUR NEWSLETTER TO STAY TO DATE WITH THE LATEST FROM', 'huyvo'); ?> <strong><?php _e('HUY-VO FASHION', 'huyvo'); ?></strong> <?php _e('NEWS', 'huyvo'); ?></h2>
				<div class="wpcf7">
					<?php echo do_shortcode('[contact-form-7 id="eacad95" title="Form Popup"]'); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>

</body>

</html>