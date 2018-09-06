<?php
/**
 * Admin settings
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Simple_Payment_Discounts_Admin class.
*/
class WC_Simple_Payment_Discounts_Admin {
	
	/**
	 * Initialize the plugin admin.
	*/
	public function __construct() {
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'menu_woocommerce_simple_payment_discounts' ) );
		// Register plugin settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Create plugin admin menu on woocommerce menu
	*/
	public function menu_woocommerce_simple_payment_discounts() {
		add_submenu_page(
				'woocommerce',
				__( 'WooCommerce Simple Payment Discounts', 'woo-simple-payment-discounts' ),
				__( 'WooCommerce Simple Payment Discounts', 'woo-simple-payment-discounts' ),
				'manage_woocommerce',
				'woo-simple-payment-discounts', array( $this, 'woocommerce_simple_payment_discounts_page' )
			);
	}

	/**
	 * Register settings.
	*/
	public function register_settings() {	
		register_setting( 'woocommerce_simple_payment_discounts_group', 'woocommerce_simple_payment_discounts', array( $this, 'validate_settings' ) );
	}

	/**
	 * Validate settings.
	*/
	public function validate_settings($options) {
		$output = array();
		foreach ( $options as $key => $value ) {
			// Validate amount.
			$amount = floatval( wc_format_decimal( $value['amount'] ) );
			$output[ $key ]['amount'] = $amount;
			// Validate type.
			$type = esc_attr( $value['type'] );
			$output[ $key ]['type'] = $type;
			// Validate custom name.
			$custom_name = esc_attr( $value['custom_name'] );
			$output[ $key ]['custom_name'] = $custom_name;	
		}
		return $output;
	}

	/**
	 * Admin page.
	*/
	public function woocommerce_simple_payment_discounts_page() { 		
		$settings = get_option( 'woocommerce_simple_payment_discounts' );
		
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		include_once( 'html/admin-page.php' );
	}

}

new WC_Simple_Payment_Discounts_Admin();