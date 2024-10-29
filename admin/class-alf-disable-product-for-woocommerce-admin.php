<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://allysonflores.com/
 * @since      1.0.0
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/admin
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class Alf_Disable_Product_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if( 
			( isset($_GET['action']) && $_GET['action'] == 'edit' && get_post_type() == 'product' ) || 
			( isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'product_cat' )
		){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/alf-disable-product-for-woocommerce-product-tab-data.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( 
			( isset($_GET['action']) && $_GET['action'] == 'edit' && get_post_type() == 'product' ) || 
			( isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'product_cat' )
		){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/alf-disable-product-for-woocommerce-product-tab-data.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Add Settings Links in Plugin list page
	 *
	 * @since    1.0.0
	 */
	public function add_settings_link_plugin( $links ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=alf-disable-purchase' ) . '">' . __('Settings') . '</a>';
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=product' ) . '">' . __('Products') . '</a>';
		$links[] = '<a href="' . admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) . '">' . __('Categories') . '</a>';
		return $links;
	}

	/**
	 * Add new page for the Admin Area.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu_page() {
		add_submenu_page( 'edit.php?post_type=product', __( "ALF Disable Products", $this->plugin_name ), __( "ALF Disable Products", $this->plugin_name ), "manage_options", $this->plugin_name.'_settings', array( $this, 'display_settings_page' ) );
	}

	/**
	 * Register Plugin Woocommerce Settings
	 *
	 * @since    1.0.0
	 */
	public function product_settings_section( $sections ) {
		$sections['alf-disable-purchase'] = __( 'ALF Disable Products', $this->plugin_name );
		return $sections;
	}

	/**
	 * Plugin Woocommerce Settings Fields
	 *
	 * @since    1.0.0
	 */
	public function product_settings_field( $settings, $current_section ) {
		if ( $current_section == 'alf-disable-purchase' ) {

			$new_settings = array();

			#Title
			$new_settings[] = array( 
				'name' => __( 'ALF Disable Products', $this->plugin_name ), 
				'type' => 'title', 
				'desc' => __( 'Configure the global options for ALF Disable Products Plugin.', $this->plugin_name ), 
				'id' => 'alf-disable-purchase' 
			);

			#Button Text
			$new_settings[] = array(
				'name'     => __( '"Add to cart" Button Text', $this->plugin_name ),
				'desc_tip' => __( 'Change the Add to Cart button text for disabled products.', $this->plugin_name ),
				'id'       => 'alf_dp_button_text',
				'type'     => 'text',
				'default'  => __( 'Unavailable', $this->plugin_name )
			);

			#Single Product Page Message
			$new_settings[] = array(
				'name'     => __( 'Single Product Notice', $this->plugin_name ),
				'desc_tip' => __( 'Change text to display on Single Product page\'s Message', $this->plugin_name ),
				'id'       => 'alf_dp_msg_text',
				'type'     => 'text',
				'default'  => __( 'This product is unavailable as of the moment.', $this->plugin_name )
			);

			$new_settings[] = array( 'type' => 'sectionend', 'id' => 'alf-disable-purchase' );
			return $new_settings;
		
		/**
		 * If not, return the standard settings
		 **/
		} else {
			return $settings;
		}
	}

	/**
	 * Products List Custom column
	 *
	 * @since    1.0.0
	 */
	public function products_list_custom_column( $posts_columns ) {
	    $posts_columns['_alf_dp_disabled'] = __( 'Purchase Disabled?', $this->plugin_name );
	    return $posts_columns;
	}

	/**
	 * Plugin Woocommerce Settings Fields
	 *
	 * @since    1.0.0
	 */
	public function products_list_custom_column_display( $column_name, $post_id ) {
	    if ( '_alf_dp_disabled' == $column_name ) {
	        $disabled = get_post_meta( $post_id, '_alf_dp_disabled', true );
	        $schedules = get_post_meta( get_the_ID(), '_alf_dp_schedule', true );
	        $schedules = ( isset($schedules) && is_array($schedules) && count($schedules) > 1 )? '<span data-tip="Dated Unavailability" title="Dated Unavailability" class="dashicons dashicons-calendar-alt" style=" color: #ea0000;font-size: 1.7em;margin: 3px 0 0 15px;"></span>' : ''; 

	        echo ( $disabled === 'yes' )? '<span class="dashicons dashicons-yes" style=" color: #ea0000; font-size: 2.5em; margin: 0 0 0 -10px;"></span>' . $schedules : '<span class="na">–</span>';
	    }
	}

	/**
	 * Product Tax List Custom column
	 *
	 * @since    1.0.0
	 */
	public function product_tax_custom_column( $tax_columns ) {
        $tax_columns['_alf_dp_disabled'] = __( 'Purchase Disabled?', $this->plugin_name );
        return $tax_columns;
	}

	/**
	 * Product Tax List Custom column Display
	 *
	 * @since    1.0.0
	 */
	public function product_tax_custom_column_display( $content, $column_name, $term_id ) {
        if ( '_alf_dp_disabled' == $column_name ) {
	        $disabled = get_term_meta( $term_id, '_alf_dp_disabled', true);
	        $schedules = get_term_meta( $term_id, '_alf_dp_schedule', true);
	        $schedules = ( isset($schedules) && is_array($schedules) && count($schedules) > 0 )? '<span data-tip="Dated Unavailability" title="Dated Unavailability" class="dashicons dashicons-calendar-alt" style=" color: #ea0000;font-size: 1.7em;margin: 3px 0 0 15px;"></span>' : ''; 

	        $content = ( $disabled === 'yes' )? '<span class="dashicons dashicons-yes" style=" color: #ea0000; font-size: 2.5em; margin: 0 0 0 -10px;"></span>' . $schedules : '<span class="na">–</span>';
	    }
        return $content;
	}

	/**
	 * Single Product Admin Tab
	 *
	 * @since    1.0.0
	 */
	public function product_admin_tab( $tabs ) {
	    $tabs['alf_dp_tab'] = array(
	        'label'         => __( 'Disable Product', $this->plugin_name ),
	        'target'        => 'alf_dp_tab',
	        'class'         => array( 'show_if_simple', 'show_if_variable', 'show_if_grouped', 'show_if_external', 'show_if_virtual', 'show_if_downloadable'),
	        /*'priority'      => 80,*/
	    );
	    return $tabs;
	}

	/**
	 * Single Product Admin Tab Fields
	 *
	 * @since    1.0.0
	 */
	public function product_admin_tab_content() {
		$schedules = get_post_meta( get_the_ID(), '_alf_dp_schedule', true );
		require_once plugin_dir_path( __FILE__ ). 'partials/alf-disable-product-for-woocommerce-product-tab-data.php';
	}

	/**
	 * Save Product Tab Data
	 *
	 * @since    1.0.0
	 */
	public function save_product_tab_data( $post_id ) {
	    $disabled = isset( $_POST['_alf_dp_disabled'] ) ? 'yes' : 'no';
	    $d_btn_txt = sanitize_text_field( $_POST['_alf_dp_btn_text'] );
	    $d_btn_txt = isset( $d_btn_txt )? $d_btn_txt : ''; 
	    $d_msg_txt = sanitize_text_field( $_POST['_alf_dp_msg_text'] );
	    $scheds = ( isset( $_POST['alf_dp_schedule'] ) && $disabled === 'yes' )? $this->sanitize_array($_POST['alf_dp_schedule']) : null;
	    /*$scheds = $this->fix_validate_schedule($scheds);*/

	   	/*echo '<pre>' . var_export($scheds, true) . '</pre>';
	    die();*/
	    update_post_meta( $post_id, '_alf_dp_schedule', $scheds );
	    update_post_meta( $post_id, '_alf_dp_disabled', $disabled );
	    update_post_meta( $post_id, '_alf_dp_btn_text', $d_btn_txt );
	    update_post_meta( $post_id, '_alf_dp_msg_text', $d_msg_txt );
	}

	/**
	 * Validate & Fix Schedule
	 *
	 * @since    1.0.0
	 */
	/*public function fix_validate_schedule( $scheds ) {
		$scheds = ( is_array($scheds) && count($scheds) > 0 )? $scheds : null;


		var_dump($scheds);

		if( isset($scheds) ){
			foreach ($scheds as $key => $sched) {
				$from = DateTime::createFromFormat('m-d-Y', $sched['from']);
				$to = DateTime::createFromFormat('m-d-Y', $sched['to']);
				if( ( !isset($sched['from']) || !isset($sched['to']) ) || 
					( isset($sched['from']) && isset($sched['to']) && ( 
						( $from && $from->format('m-d-Y') != $sched['from'] || $to && $to->format('m-d-Y') != $sched['to'] ) ||
						( $sched['from'] == '' || $sched['from'] == '' ) || 
						( $sched['from'] > $sched['to'] )
					) ) 
				){
					unset($scheds[$key]);
					continue;
				}else{
					$scheds[$key]['from'] = $from->format('m-d-Y');
					$scheds[$key]['to'] = $to->format('m-d-Y');
				}
			}
			$scheds = ( count($scheds) > 0 )? array_values($scheds) : null;
		}

		// var_dump($scheds);
		die();

		return $scheds;
	}*/

	/**
	 * Create Product Category Fields
	 *
	 * @since    1.0.0
	 */
	public function create_product_cat_fields() {
		require_once plugin_dir_path( __FILE__ ). 'partials/alf-disable-product-for-woocommerce-product-category.php';
	}

	/**
	 * Edit Product Category Fields
	 *
	 * @since    1.0.0
	 */
	public function edit_product_cat_fields($term) {
	    $term_id = $term->term_id;
	    $disabled = get_term_meta( $term_id, '_alf_dp_disabled', true);
	    $schedules = get_term_meta( $term_id, '_alf_dp_schedule', true);
	    require_once plugin_dir_path( __FILE__ ). 'partials/alf-disable-product-for-woocommerce-product-category-edit.php';
	}

	/**
	 * Save Product Category Fields
	 *
	 * @since    1.0.0
	 */
	public function save_product_cat_fields($term_id) {
		$disabled = isset( $_POST['_alf_dp_disabled'] ) ? 'yes' : 'no';
		$scheds = ( isset( $_POST['alf_dp_schedule'] ) && $disabled === 'yes' )? $this->sanitize_array($_POST['alf_dp_schedule']) : null;
		/*$scheds = $this->fix_validate_schedule($scheds);*/

		if( $disabled === 'yes' ){
			update_term_meta( $term_id, "_alf_dp_disabled" , $disabled );
			update_term_meta( $term_id, "_alf_dp_schedule" , $scheds );
		}
	}

	/**
	 * Sanitize Multi-Dimenstion Array
	 *
	 * @since    1.0.0
	 */
	public function sanitize_array($data = array()) {
		if (!is_array($data) || !count($data)) {
			return array();
		}

		foreach ($data as $k => $v) {
			$from = strtotime( str_replace('-', '/', $v['from']) );
	    	$to = strtotime( str_replace('-', '/', $v['to']) );

			if( is_array($v) && $from !== false && $to !== false &&
				$to >= $from 
			){
				$data[$k] = array_map( 'sanitize_text_field', $v );
			}else{
				unset($data[$k]);
			}
		}
		return $data;
	}
}