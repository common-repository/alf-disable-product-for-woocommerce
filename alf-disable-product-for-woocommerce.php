<?php
/**
 * @link              http://allysonflores.com/
 * @since             1.0.0
 * @package           Alf_Disable_Product_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       ALF Disable Product for Woocommerce
 * Plugin URI:        https://bitbucket.org/allouise/alf-disable-product-for-woocommerce/
 * Description:       Simple plugin to Disable Woocommerce Single Product (or by Category) on a span of Dates.
 * Version:           1.0.0
 * Author:            Allyson Flores
 * Author URI:        http://allysonflores.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alf-disable-product-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.9.0
 * WC tested up to: 4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALF_DISABLE_PRODUCT_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/*
 * Check for Woocommerce Dependencies
 */
function dependency_alf_disable_product_for_woocommerce( $cons = false ){
	if ( !class_exists( 'woocommerce' ) || !defined('WC_VERSION') || ( defined('WC_VERSION') && WC_VERSION < 3.0 ) ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

		if( $cons === true ){
			wp_die( __('<p><strong>ALF Disable Product for Woocommerce:</strong> Please make sure at least <strong>Woocommerce <u>3.9.0</u></strong> is installed & activated<p>').'<a href="'.get_admin_url().'plugins.php">'.__('Go Back').'</a>' );
		}else{
			echo '<div class="error" style="-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif">'.__('<p><strong>ALF Disable Product for Woocommerce:</strong> Please make sure at least <strong>Woocommerce <u>3.9.0</u></strong> is installed & activated<p>').'</div>';
		}
	}
}
add_action( 'plugins_loaded', 'dependency_alf_disable_product_for_woocommerce');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-alf-disable-product-for-woocommerce-activator.php
 */
function activate_alf_disable_product_for_woocommerce() {
	dependency_alf_disable_product_for_woocommerce(true);
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-alf-disable-product-for-woocommerce-activator.php';
	Alf_Disable_Product_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-alf-disable-product-for-woocommerce-deactivator.php
 */
function deactivate_alf_disable_product_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-alf-disable-product-for-woocommerce-deactivator.php';
	Alf_Disable_Product_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_alf_disable_product_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_alf_disable_product_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-alf-disable-product-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alf_disable_product_for_woocommerce() {

	$plugin = new Alf_Disable_Product_For_Woocommerce();
	$plugin->run();

}
run_alf_disable_product_for_woocommerce();
