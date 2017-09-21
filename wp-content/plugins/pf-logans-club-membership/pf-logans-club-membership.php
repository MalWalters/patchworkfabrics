<?php
/*
Plugin Name: Logans Club Membership
Plugin URL: 
Description: Logan's Club Membership. This creates a custom user role 'Logans Club Member'; custom fields on the user admin screen and settings options for category discounts
Version: 0.8
Author: Malcolm Walters
Author URI:  
*/

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	// Create custom user role when plugin is activated
	function add_logans_club_member_role_on_plugin_activation() {
       add_role( 'logans_club_member', 'Logans Club Member', array( 'read' => true, 'level_0' => true ) );
	   //Check to see if the discounts are active
	}
	
    register_activation_hook( __FILE__, 'add_logans_club_member_role_on_plugin_activation' );
	
	// Remove custom user role when plugin is desactivated
	function remove_logans_club_member_custom_roes_on_plugin_deactivation(){
			remove_role('logans_club_member');
	}
		
	register_deactivation_hook( __FILE__, 'remove_logans_club_member_custom_roes_on_plugin_deactivation' );
	
/******************************************************************************************************************************************
Check when user logs in that membership is still valid ie membership expiry date < todays date
If membership has expired the set user role to customer
*/

function check_expiry_date_at_login ($user){
	echo "Here";
	$user_id = get_current_user_id();
	var_dump($user_id);
	$user = get_user_meta($user_id);
	$user_meta = get_userdata($user_id);
	if ($user_meta->roles != 'logans_club_member'){
		return;
	}
	$logans_membership_info = get_user_meta( $user_id, 'logans-club-membership-info', false);
	$expiry_date = date('d-M-Y',strtotime($logans_membership_info[0]['logans-membership-expiry-date']));
	if (is_logans_club_membership_expired($expiry_date)){
		update_user_meta($user_id,'role','Customer','Logans Club Member');
		$user = new WP_User($user_id);
		$user->remove_role( 'logans_club_member' );
		$user->add_role( 'customer' );
	}
}

//add_action('wp_authenticate','check_expiry_date_at_login');


/** USER RELATED***************************************************************************************************************************
 *Add tab to My Account page
 */

/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
 
function logans_account_menu_items( $items ) {
    $items['logans-club'] = __( 'Logans Club', 'logans-club' );
    return $items;
}

add_filter( 'woocommerce_account_menu_items', 'logans_account_menu_items', 10, 1 );

/**
 * Add endpoint
 */
 
function logans_add_my_account_endpoint() {
    add_rewrite_endpoint( 'logans-club', EP_PAGES );
}

add_action( 'init', 'logans_add_my_account_endpoint' );

/**
 * Logan's Club Membership content
 */
 
function logans_information_endpoint_content() {
    ?>
	<h3>Logan's Patchwork Club Membership Information</h3>
	<?php
	$user_id = get_current_user_id();
	$user = get_user_meta($user_id);
	$user_meta = get_userdata($user_id);
	$role = $user_meta->roles;
	// if there's no metadata for this user
	if (!$user){
		echo "It looks like you're not a member. Click here to read about the Logan's Club!";
		return;
	}
	$logans_membership_info = get_user_meta( $user_id, 'logans-club-membership-info', false);
	$expiry_date = date('d-M-Y',strtotime($logans_membership_info[0]['logans-membership-expiry-date']));
	?>
	<p>
	Your Logan's Membership ID is: PQ <input type="text" readonly="readonly" value="<?php _e($logans_membership_info[0]['logans-membership-number']) ?>" size='3'>
	</p>
	<p>
	<?php 
		if (is_logans_club_membership_expired($logans_membership_info[0]['logans-membership-expiry-date'])){
			
			echo "Your membership has <strong>Expired</strong>. <i class='fa fa-times-circle fa-2x' style='color: red'></i></p>
					<p>Your membership expired on ";
			echo $expiry_date;
			echo "<p>Thank you for being part of the Logan's Patchwork Club. If you would like to renew your membership, please contact us and we'll be happy to help.</p>";
		} else
			{
			echo "Your membership is <strong>Active</strong> <i class='fa fa-check fa-2x' style='color: green'></i></p>
					<p>Your membership is due for renewal before ";
			echo $expiry_date;
		}
}

add_action( 'woocommerce_account_logans-club_endpoint', 'logans_information_endpoint_content' );

/*
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */

function logans_woo_my_account_order() {
	$myorder = array(
		'edit-account'       => __( 'Change My Details', 'woocommerce' ),
		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'orders'             => __( 'Orders', 'woocommerce' ),
		'downloads'          => __( 'Downloads', 'woocommerce' ),
		'edit-address'       => __( 'Addresses', 'woocommerce' ),
		'logans-club' 		 => __( 'Logans Club', 'woocommerce' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),
	);

	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'logans_woo_my_account_order' );

	
/* FOR USER LOGIN- Add fields to user profile page
This adds the fields to the my accounts section of the website when the user is logged in
*/
	add_action( 'woocommerce_edit_account_form', 'logans_club_member_info_fields_profile' );
	
	function logans_club_member_info_fields_profile(){
		
		$user_id = get_current_user_id();
		$user = get_user_meta($user_id);
		// if there's no metadata for this user
		if (!$user){
			echo "<h3> Logan's Club Membership</h3>";
			echo "It looks like you're not a member. Click here to read about the Logan's Club!";
			return;
		}
		
		$logans_membership_info = get_user_meta( $user_id, 'logans-club-membership-info', false);
		
		$expiry_date = $logans_membership_info[0]['logans-membership-expiry-date'];
		?>
		
		<h3>Logan's Club Membership</h3>
		<p>
		Membership Number: <br /><input type="text" readonly="readonly" value="<?php _e($logans_membership_info[0]['logans-membership-number']) ?>" size='3'> Membership Status:<br />
		Membership Expiry Date: <br /><input type="text" readonly="readonly" value="<?php _e($logans_membership_info[0]['logans-membership-expiry-date']) ?>" size='10'> <br />
		</p>
		<?php
	}
	
	// Show fields when editing profile as admin
	add_action( 'edit_user_profile', 'logans_club_member_info_fields' );
	
/*****************************************************************************************************************************************
/** HELPER FUNCTIONS
- Compares expiry date string (in user_meta_data) to today's date. If membership has expired this should return false.
*/

	function is_logans_club_membership_expired($expiry_date){
		$date = date('Y-m-d');
		if ($expiry_date >=  $date){
			return false;
		}
		return true;
	}

/*****************************************************************************************************************************************


/* ADMINISTRATION RELATED
/* FOR ADMIN DASHBOARD - Adding fields to user profile as admin
This adds the fields for Logans club membership that is viewed throught eh administration dashboard

*/	
	function logans_club_member_info_fields( $user ) {
		
		$logans_membership_info = get_user_meta( $user->ID, 'logans-club-membership-info', false);
		$expiry_date = $logans_membership_info[0]['logans-membership-expiry-date'];
	?>
	  <h3><?php _e("Logan's Club Membership", "blank"); ?></h3>
	  <table class="form-table">
		<tr>
		  <th><label for="logans-club-membership-number"><?php _e("Club Membership Number : PQ"); ?></label></th>
		  <td>
			<input type="text" name="logans-club-membership-number" id="logans-club-membership-number" 
				value="<?php _e($logans_membership_info[0]['logans-membership-number']); ?>" size = "5"/> 
				<?php 
				// Compare expiry date to today's date and assign correct icons
				
				if (is_logans_club_membership_expired($logans_membership_info[0]['logans-membership-expiry-date'])){
						echo "Membership Status: <strong>Expired</strong>. <i class='fa fa-times-circle fa-2x' style='color: red'></i></p>";
					} else
				{
						echo "Membership Status: <strong>Active</strong> <i class='fa fa-check fa-2x' style='color: green'></i></p>";
					}
				?>
				
			<span class="description"><?php _e("Please enter memberhsip number."); ?></span>
		  </td>
		</tr>
		</table>
		<table class="form-table">
		<tr>
		  <th>Expiry Date</th>
		  <td><input 	type="date" 
						name="logans-club-membership-expiry-date"
						id="logans-club-membership-expiry-date"
						value="<?php _e($logans_membership_info[0]['logans-membership-expiry-date']) ?>"
						/></td>
		</tr>
	  </table>
	<?php
	}
	
/****************************************************************************************************
* Change price and add Logan's Club Membership pricing
*/


add_filter('woocommerce_get_price', 'custom_price_WPA111772', 10, 2);
/**
 * custom_price_WPA111772 
 *
 * filter the price based on category and user role
 * @param  $price   
 * @param  $product 
 * @return 
 */
function custom_price_WPA111772($price, $product) {
    if (!is_user_logged_in()) return $price;

    //check if the product is in a category you want, let say shirts
    if( has_term( 'shirts', 'product_cat' ,$product->ID) ) {
        //check if the user has a role of dealer using a helper function, see bellow
        if (has_role_WPA111772('dealer')){
            //give user 10% of
            $price = $price * 0.9;
        }
    }
    return $price;
}

/**
 * has_role_WPA111772 
 *
 * function to check if a user has a specific role
 * 
 * @param  string  $role    role to check against 
 * @param  int  $user_id    user id
 * @return boolean
 */
function has_role_WPA111772($role = '',$user_id = null){
    if ( is_numeric( $user_id ) )
        $user = get_user_by( 'id',$user_id );
    else
        $user = wp_get_current_user();

    if ( empty( $user ) )
        return false;

    return in_array( $role, (array) $user->roles );
}


/****************************************************************************************************
*/
	
	// Load Font Awesome icons for use in displaying membership status
	add_action('admin_head','load_font_awesome_icons');
	
	function load_font_awesome_icons(){
		echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"  rel="stylesheet">';
	}
	
	// Add update function for Club Membership info (Array with club number and expiry date)
	add_action ('edit_user_profile_update','update_logans_club_member_info');
	
	function update_logans_club_member_info($user_id){
		//Create Array
		$club_info = array(
							'logans-membership-number' => $_POST['logans-club-membership-number'],
							'logans-membership-expiry-date' => $_POST['logans-club-membership-expiry-date']);
		$saved = false;
		$unique = false;
		if ( current_user_can( 'edit_user', $user_id ) ) {
			update_user_meta( $user_id, 'logans-club-membership-info', $club_info, $unique );
			$saved = true;
	  }
		return true;
	  }
	  
	 add_filter ('woocommerce_get_price', 'show_logans_club_member_price', 10, 2);
	 
	function show_logans_club_member_price($price, $product) {
		if (!is_user_logged_in()) {
			 return $price;
			}
		 $user = wp_get_current_user();
		 if ( in_array( 'logans_club_member', (array) $user->roles)) {
			 $logansPrice = $price * .9;
			 return $logansPrice;
		}
	}
	 
	add_filter ('woocommerce_get_price_html', 'show_logans_club_member_price_label');
	
	function show_logans_club_member_price_label ($price){
		
		if (!is_user_logged_in()) {
			 return $price;
			}
		 $user = wp_get_current_user();
		 if ( in_array( 'logans_club_member', (array) $user->roles)) {
			 $return_string = "Logan's Club Member Price:\n<br>" . $price;
			 return $return_string;
		}
		return $price;
	}
	  
?>