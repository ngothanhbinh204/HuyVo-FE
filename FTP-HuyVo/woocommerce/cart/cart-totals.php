<?php

/**
 * Cart totals - Custom với danh sách sản phẩm
 */

defined('ABSPATH') || exit;

$payment_support = get_field('payment_support', 'option');
$customer_service = get_field('customer_service', 'option');
?>

<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<h2><?php esc_html_e('Cart totals', 'woocommerce'); ?></h2>

	<!-- === PHẦN DANH SÁCH SẢN PHẨM TRONG GIỎ === -->


	<table cellspacing="0" class="shop_table shop_table_responsive">
		<?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
			$quantity = $cart_item['quantity'];
			$line_total = WC()->cart->get_product_subtotal($_product, $quantity);
		?>
			<tr class="cart-item-summary">
				<td class="item-name"><?php echo esc_html($product_name); ?>(<?php echo $quantity; ?>)</td>
				<td class="item-price"><?php echo $line_total; ?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="cart-subtotal">
			<th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
				<td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>
		<?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>
			<tr class="shipping">
				<th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
				<td data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>
		<?php endif; ?>

		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee">
				<th><?php echo esc_html($fee->name); ?></th>
				<td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
				$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}

			if ('itemized' === get_option('woocommerce_tax_total_display')) {
				foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<th><?php echo esc_html($tax->label) . $estimated_text; ?></th>
						<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php endforeach;
			} else { ?>
				<tr class="tax-total">
					<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></th>
					<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
		<?php }
		}
		?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<!-- <tr class="order-total">
			<th><?php esc_html_e('Total', 'woocommerce'); ?></th>
			<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr> -->

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</table>

	<div class="wc-proceed-to-checkout">
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<div class="payment-support">
		<div class="payment-support-title"><?php esc_html_e('Shipping costs will be calculated in checkout', 'woocommerce'); ?></div>
		<div class="payment-support-list">
			<?php foreach ($payment_support as $payment) : ?>
				<img src="<?php echo $payment['url']; ?>" alt="<?php echo $payment['alt'] ?? 'payment'; ?>">
			<?php endforeach; ?>
		</div>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>

<div class="box-policy">
	<div class="header-policy">
		<div class="header-policy-title"><?php esc_html_e('Customer Service', 'woocommerce'); ?></div>
	</div>
	<div class="cart-policy-list">
		<?php foreach ($customer_service as $item) : ?>
			<div class="cart-policy-item">
				<div class="policy-item-header">
					<div class="item-title">
						<span><?php echo $item['title'] ?? ''; ?></span>
					</div>
					<div class="icon">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M16.8281 7.25782C16.7803 7.14406 16.6998 7.04701 16.597 6.97889C16.4941 6.91076 16.3733 6.87462 16.2499 6.875H3.74994C3.62655 6.87462 3.5058 6.91076 3.40292 6.97889C3.30004 7.04701 3.21963 7.14406 3.17181 7.25782C3.12679 7.37326 3.11536 7.49909 3.13887 7.62076C3.16237 7.74242 3.21984 7.85495 3.30463 7.94532L9.55463 14.1953C9.67409 14.3108 9.83376 14.3754 9.99994 14.3754C10.1661 14.3754 10.3258 14.3108 10.4453 14.1953L16.6953 7.94532C16.78 7.85495 16.8375 7.74242 16.861 7.62076C16.8845 7.49909 16.8731 7.37326 16.8281 7.25782Z" fill="#504B4C" />
						</svg>
					</div>
				</div>
				<div class="policy-item-content">
					<?php echo $item['content'] ?? ''; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>