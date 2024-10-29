<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://allysonflores.com/
 * @since      1.0.0
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/public
 * @author     Allyson Flores <elixirlouise@gmail.com>
 */
class Alf_Disable_Product_For_Woocommerce_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Disable Products From Purchase
	 *
	 * @since    1.0.0
	 */
	public function make_products_disabled( $is_purchasable, $product ) {
		$disabled = $this->check_disabled( $product->get_id() );

		return ( $disabled === true )? false : true;
	}

	/**
	 * Change Disabled Product Button Text
	 *
	 * @since    1.0.0
	 */
	public function change_disabled_btn_txt( $buttonText ) {
		global $product;
		$disabled = $this->check_disabled( $product->get_id() );

	    $d_btn_txt = get_post_meta( $product->get_id(), '_alf_dp_btn_text', true );
	    $d_btn_txt = ( isset( $d_btn_txt ) && $d_btn_txt != "" )? $d_btn_txt : get_option( 'alf_dp_button_text', __( 'Unavailable', $this->plugin_name ) );

	    return ( $disabled === true )? $d_btn_txt : $buttonText;
	}

	/**
	 * Disabled Single Products Page Message
	 *
	 * @since    1.0.0
	 */
	public function disabled_single_product_page_msg() {
		global $product;
	    $disabled = $this->check_disabled( $product->get_id() );

	    $d_msg = get_post_meta( $product->get_id(), '_alf_dp_msg_text', true );
	    $d_msg = ( isset( $d_msg ) && $d_msg != "" )? $d_msg : get_option( 'alf_dp_msg_text', __( 'This product is unavailable as of the moment.', $this->plugin_name ) );

	  	if ( $disabled === true ) {
	    	echo '<div class="woocommerce npwpMessage"><div class="woocommerce-info" style="margin-bottom: 0px;">' . $d_msg . '</div></div>';
	    }
	}

	/**
	 * Remove other Woocommerce actions
	 *
	 * @since    1.0.0
	 */
	public function remove_actions(){
	    $disabled = $this->check_disabled( get_the_ID() );
	    if ( $disabled === true ) {
	    	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	    }
	}

	/**
	 * Get Scheduled
	 *
	 * @since    1.0.0
	 */
	public function check_disabled( $id = null ) {
		$id = isset($id)? $id : get_the_ID();
		$return = false;

		if( !isset($id) || ( isset($id) && (int) $id <= 0 ) ) return $return;

		$parent = wp_get_post_parent_id($id);
		$id = ( $parent > 0 )? $parent : $id;
		$disabled = get_post_meta( $id, '_alf_dp_disabled', true );
	    $schedules = get_post_meta( $id, '_alf_dp_schedule', true );
	    $cat_terms = $this->get_disabled_cat( $id );

	    if( isset($schedules) && is_array($schedules) && count($schedules) > 0 && $disabled === 'yes' && !isset($cat_terms) ){
	    	$return = $this->validate_schedule($schedules);
		}elseif( $disabled === 'yes' ){
	    	$return = true;
	    }elseif( isset($cat_terms) ){
    		foreach ($cat_terms as $term) {
    			$disabled = get_term_meta( $term, '_alf_dp_disabled', true);

    			if( $disabled != 'yes' ) continue;

    			$schedules = get_term_meta( $term, '_alf_dp_schedule', true);
		        $return = $this->validate_schedule($schedules);
    		}
    	}

	    // var_dump($return);
	    // var_dump(get_option('timezone_string'));
		// print("<pre>".print_r($schedules,true)."</pre>");
		// die();
		return $return;
	}

	/**
	 * Get Disabled Categories
	 *
	 * @since    1.0.0
	 */
	public function get_disabled_cat( $id ) {
		$a = array(
			'fields' 	 => 'ids',
			'hide_empty' => true,
			'meta_query' => array(
			    array(
			       'key'       => '_alf_dp_disabled',
			       'value'     => 'yes',
			       'compare'   => '='
			    )
			)
		);
		$cats = wp_get_post_terms( $id, 'product_cat', $a );
		return ( count($cats) > 0 )? $cats : null;
	}

	/**
	 * Validate Schedules
	 *
	 * @since    1.0.0
	 */
	public function validate_schedule( $schedules ) {
		$return = false;

		if( isset($schedules) && is_array($schedules) && count($schedules) > 0 ){
	        foreach ($schedules as $key => $sched) {
    			$from_str = strtotime( str_replace('-', '/', $sched['from']) );
    	    	$to_str = strtotime( str_replace('-', '/', $sched['to']) );
	        	$date = date('m-d-Y');
	        	$from = date('m-d-Y', $from_str);
	        	$to = date('m-d-Y', $to_str);

	        	if( isset($sched['annual']) ){
	        		$date = date('m-d');
	        		$from = date('m-d', $from_str);
	        		$to = date('m-d', $to_str);
	        	}

	        	if( $date >= $from && $date <= $to ){

	        		$return = true;
	        		break;
	        	}

		    }
		}
        
	    return $return;
	}
}