<?php
/*
Plugin Name: Logans Custom Functions
Plugin URL: 
Description: Custom functions for Logan's Patchwork Fabrics. Curently included are: Custom order status; Disabling the Product Review Tab; Widgetise the sidebar
Version: 0.6
Author: Malcolm Walters
Author URI:  
*/

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
Remove Title from front page
*/
	
add_filter( 'woocommerce_show_page_title' , 'woo_hide_page_title' );

function woo_hide_page_title() {
	return false;
}	
	
/************************************************************************************************************************************************************
* Create Logans Admin Menu
* This will be for all custom settings for Logan's Patchwork Fabrics
*/

add_action( 'admin_menu', 'logans_custom_menu_settings' );

function logans_custom_menu_settings() {
	add_options_page( 'Logans Custom Settings', 'Logans Settings', 'manage_options', 'logans-custom-settings', 'logans_custom_plugin_options' );

}

function logans_custom_plugin_options(){
	
	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
    }else{
		$active_tab = "minimum-cut";}    // end if
	?>
	<h1> Custom Settings for Logan's Patchwork Fabrics</h1>
	<h2 class="nav-tab-wrapper">
		<a href="?page=logans-custom-settings&tab=minimum-cut" class="nav-tab <?php echo $active_tab == 'minimum-cut' ? 'nav-tab-active' : ''; ?>">Minimum Cut Notice</a>
		<a href="?page=logans-custom-settings&tab=member-discounts" class="nav-tab <?php echo $active_tab == 'member-discounts' ? 'nav-tab-active' : ''; ?>">Club Membership Discounts</a>
	</h2>
	<form method="post">
	<?php
	if ($active_tab == "minimum-cut"){
		 include('pf-logans-minimum-cut-notice-admin-form.php');
		}else{
			if (is_plugin_inactive("pf-logans-club-membership/pf-logans-club-membership.php")){
				echo "<h3>To access these settings please, activate the Logans Club Membership plugin in the Plugins page</h3>";
			}else{
		include('pf-logans-club-member-discount-admin-form.php');
			}
		}
	
}

/****************************************************
* Remove Comments from the admin menu
* The website should not allow comments on posts or product reviews
*/
function custom_menu_page_removing() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'custom_menu_page_removing' );

/***************************************************
* Custom Order Status
*/
// Register new status
function register_awaiting_stock_order_status() {
    register_post_status( 'wc-awaiting-stock', array(
        'label'                     => 'Awaiting stock',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting stock <span class="count">(%s)</span>', 'Awaiting stock <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_awaiting_stock_order_status' );

// Add to list of WC Order statuses
function add_awaiting_stock_to_order_statuses( $order_statuses ) {
  
    $new_order_statuses = array();
  
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
  
        $new_order_statuses[ $key ] = $status;
  
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-awaiting-stock'] = 'Awaiting stock';
        }
    }
  
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_awaiting_stock_to_order_statuses' );

// Add custom icon for custom order status

/**
 * Adds icons for any custom order statuses
 * Tutorial: http://www.skyverge.com/blog/changing-woocommerce-custom-order-status-icons/
**/
add_action( 'wp_print_scripts', 'skyverge_add_custom_order_status_icon' );
function skyverge_add_custom_order_status_icon() {
	
	if( ! is_admin() ) { 
		return; 
	}
	
	?> <style>
		/* Add custom status order icons */
		.column-order_status mark.awaiting-stock {
			content: url(/wp-content/uploads/2017/05/LogansCustomOrderStatus-AwaitingStock.png);
		}
	
		/* Repeat for each different icon; tie to the correct status */
 
	</style> <?php
}

/**************************************************
* Disable Product Review Tab
*/
 
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );

function wcs_woo_remove_reviews_tab($tabs) {
 unset($tabs['reviews']);
 return $tabs;
}


//OneAll SOCIAL MEDIA PLUGIN
//Handle data retrieved from a social network profile
  function oa_social_login_store_extended_data ($user_data, $identity)
  {
    // $user_data is an object that represents the newly added user
    // The format is similar to the data returned by $user_data = get_userdata ($user_id);
 
    // $identity is an object that contains the full social network profile
     
    //Example to store the gender
    update_user_meta ($user_data->ID, 'gender', $identity->gender);
  }
 
  //This action is called whenever Social Login adds a new user
  add_action ('oa_social_login_action_after_user_insert', 'oa_social_login_store_extended_data', 10, 2);

?>
