<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://allysonflores.com/
 * @since      1.0.0
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/includes
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class Alf_Disable_Product_For_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'alf-disable-product-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
