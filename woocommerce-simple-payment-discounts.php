<?php
/**
 * Plugin Name: WooCommerce Simple Payment Discounts 
 * Plugin URI:  https://github.com/GabrielS0uza/woocommerce-simple-payment-discounts
 * Description: Adds discounts on specific payment methods in WooCommerce.
 * Author:      Gabriel Souza
 * Author URI:  https://github.com/GabrielS0uza
 * Version:     1.0.0
 * License: GPLv2 or later
 * Text Domain: woo-simple-payment-discounts
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Simple_Payment_Discounts' ) ) :

/**
 * WooCommerce Simple Payment Discounts plugin.
*/
class WC_Simple_Payment_Discounts {
	
	/**
	 * Plugin version.
	*/
	const VERSION = '1.0.0';
	
	/**
	 * Instance of this class.
	*/
	protected static $instance = null;
	
	/**
	 * Initialize the plugin.
	*/
	private function __construct() {
		// Load plugin text domain.
    add_action('init', array( $this, 'load_plugin_textdomain' ));

    if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX )) {
        $this->load_admin_files();
    }

    $this->load_dependancy();
	}
	
	/**
	 * Return an instance of this class.
	*/
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	protected function load_dependancy() {
    include_once( 'includes/class-wc-simple-payment-add-discounts.php' );
  }

  protected function load_admin_files() {
    include_once( 'includes/admin/class-wc-simple-payment-discounts-admin.php' );
  }

  public static function activate() {
    add_option('woocommerce_simple_payment_discounts', array());
  }

  /**
   * Load the plugin text domain for translation.
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain( 'woo-simple-payment-discounts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }

}

	/**
	* Install plugin default options.
	*/
	register_activation_hook(__FILE__, array('WC_Simple_Payment_Discounts', 'activate'));

	/**
	* Initialize the plugin actions.
	*/
	add_action('plugins_loaded', array('WC_Simple_Payment_Discounts', 'get_instance'));

endif;