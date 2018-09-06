<?php
/**
 * Add discounts
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WC_Simple_Payment_Add_Discounts class.
*/
class WC_Simple_Payment_Add_Discounts {

    public function __construct() {
      // Load Scripts.
      add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
      add_action('woocommerce_cart_totals_after_order_total', array( $this, 'display_discounts_cart') );
      add_action('woocommerce_cart_calculate_fees', array( $this, 'display_discounts_checkout' ));
    }

    /**
     * Script files
     */
    public function enqueue_scripts() {
      if ( is_checkout() ) {
          wp_enqueue_script('woo-simple-payment-discounts', plugins_url('assets/js/wspd_custom.min.js', plugin_dir_path(__FILE__)), array('wc-checkout'), false, true);
      }
    }

    /**
     * Calcule the discount amount cart.
    */
    public function calculate_discount_cart($value, $type, $subtotal) {
      if ($type == 'fixed') {
          $total = $subtotal - $value;
      }
      if ($type == 'percentage') {
          $total = $subtotal - ($subtotal / 100) * $value;
      }

      return $total;
    }

    /**
     * Calcule the discount amount checkout.
    */
    public function calculate_discount_checkout($value, $type, $subtotal) {
      if ($type == 'fixed') {
          $total = $subtotal - ($subtotal - $value);
      }
      if ($type == 'percentage') {
          $total = $subtotal - ($subtotal - ($subtotal / 100) * $value);
      }

      return $total;
    }

    /**
     * Display discounts cart.
    */
    public function display_discounts_cart() {
      // Gets the settings.
      $settings = get_option('woocommerce_simple_payment_discounts');
      
      foreach ($settings as $setting) {
      	$value = $setting['amount'];
      	$type = $setting['type'];
        $name = $setting['custom_name'];
        $subtotal = WC()->cart->cart_contents_total;
        $shipping_total = floatval(WC()->cart->shipping_total);
        $value_discount = wc_price($value);
        $name_discount = $type == 'fixed' ? '('. sprintf( __( '%s off', 'woo-simple-payment-discounts' ), $value_discount ) . ')' : '('. sprintf( __( '%s%% off', 'woo-simple-payment-discounts' ), $value ) . ')';

      	if ( $value > 0 ) {	 	
        	$cart_discount = $this->calculate_discount_cart($value, $type, $subtotal);
          $cart_discount_shipping = $cart_discount + $shipping_total;
        	$cart_discount_total = wc_price($cart_discount_shipping);
        	echo "<tr>
							    <th>$name $name_discount</th>
							    <td>$cart_discount_total</td>
							  </tr>";  
				}  
      }
    }

    /**
     * Display discounts checkout.
    */
    public function display_discounts_checkout($cart) {
    	if ( is_admin() && ! defined( 'DOING_AJAX' ) || is_cart() ) {
        return;
      }
    	// Gets the settings.
      $settings = get_option('woocommerce_simple_payment_discounts');

      if (isset($settings[WC()->session->chosen_payment_method])) {
        $value = $settings[WC()->session->chosen_payment_method]['amount'];
        $type = $settings[WC()->session->chosen_payment_method]['type'];
        $name = $settings[WC()->session->chosen_payment_method]['custom_name'];
        $subtotal = $cart->cart_contents_total;
        $name_discount = $type == 'percentage' ? '&nbsp;('. sprintf( __( '%s%% off', 'woo-simple-payment-discounts' ), $value ) . ')' : '';
        if ( $value > 0 ) {
          $cart_discount = $this->calculate_discount_checkout($value, $type, $subtotal) * -1;
          WC()->cart->add_fee($name.$name_discount, $cart_discount, true);
        }
      }
    }

}

new WC_Simple_Payment_Add_Discounts();