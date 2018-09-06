<?php

/*
 * Admin page
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>
	
	<form method="post" action="options.php">
	    <?php settings_fields( 'woocommerce_simple_payment_discounts_group' ); ?>
			<p><?php esc_html_e( 'Enter an amount (e.g. 10.99) for each payment gateway you want a discount. Use zero (0) for not applying discounts.', 'woo-simple-payment-discounts' ); ?><br/><?php esc_html_e( 'Select the type of discount for each payment.', 'woo-simple-payment-discounts' ); ?><br/><?php esc_html_e( 'You can also display a custom discount name if you want.', 'woo-simple-payment-discounts' ); ?></p>
	    <table class="wc_gateways widefat">
	    	<thead>
	    		<tr>
	    			<th><strong><?php esc_html_e( 'Payment method', 'woo-simple-payment-discounts' ); ?></strong></th>
	    			<th><strong><?php esc_html_e( 'Discount', 'woo-simple-payment-discounts' ); ?></strong></th>
	    			<th><strong><?php esc_html_e( 'Type', 'woo-simple-payment-discounts' ); ?></strong></th>
	    			<th><strong><?php esc_html_e( 'Custom name', 'woo-simple-payment-discounts' ); ?></strong></th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    	<?php
					foreach ( $payment_gateways as $gateway ) {
						$amount = isset($settings[$gateway->id]['amount']) ? $settings[$gateway->id]['amount'] : '0';
						$custom_name = isset($settings[$gateway->id]['custom_name']) ? $settings[$gateway->id]['custom_name'] : esc_attr( $gateway->title );
				?>
				<tr>
					<td>
						<label for="woocommerce_simple_payment_discounts_<?php echo esc_attr( $gateway->id ); ?>_amount"><strong><?php echo esc_attr( $gateway->title ); ?></strong></label>
					</td>
					<td>
						<input type="text" class="input-text regular-input" value="<?php echo $amount; ?>" id="woocommerce_simple_payment_discounts_<?php echo esc_attr( $gateway->id ); ?>_amount" name="woocommerce_simple_payment_discounts[<?php echo esc_attr( $gateway->id ); ?>][amount]">
					</td>
					<td>
						<select id="woocommerce_simple_payment_discounts_type" name="woocommerce_simple_payment_discounts[<?php echo esc_attr($gateway->id); ?>][type]">
							<option value="fixed" <?php if (isset($settings[$gateway->id]['type']) && $settings[$gateway->id]['type'] == "fixed") echo 'selected="selected"'; ?>><?php esc_html_e( 'Fixed', 'woo-simple-payment-discounts' ); ?></option>
							<option value="percentage" <?php if (isset($settings[$gateway->id]['type']) && $settings[$gateway->id]['type'] == "percentage") echo 'selected="selected"'; ?>><?php esc_html_e( 'Percentage', 'woo-simple-payment-discounts' ); ?></option>
						</select>
					</td>
					<td>
						<input type="text" class="input-text regular-input" value="<?php echo ( empty($custom_name) ) ? esc_attr( $gateway->title ) : $custom_name; ?>" id="woocommerce_simple_payment_discounts_<?php echo esc_attr( $gateway->id ); ?>_custom_name" name="woocommerce_simple_payment_discounts[<?php echo esc_attr( $gateway->id ); ?>][custom_name]">
					</td>
				</tr>
			<?php } ?>
	      </tbody>
	    </table>  
 
	    <?php submit_button(); ?>
	</form>

</div>