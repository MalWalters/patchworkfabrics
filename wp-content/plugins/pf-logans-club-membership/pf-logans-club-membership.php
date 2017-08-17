<?php
/*
Plugin Name: Logans Club Membership
Plugin URL: 
Description: Logan's Club Membership. This creates a custom user role 'Logans Club Member'; custom fields on the user admin screen and settings options for category discounts
Version: 0.3
Author: Malcolm Walters
Author URI:  
*/

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	// Create custom user role when plugin is activated
	function add_logans_club_member_role_on_plugin_activation() {
       add_role( 'logans_club_member_role', 'Logans Club Member', array( 'read' => true, 'level_0' => true ) );
	   //Check to see if the discounts are active
	}
	
    register_activation_hook( __FILE__, 'add_logans_club_member_role_on_plugin_activation' );
	
	// Remove custom user role when plugin is desactivated
	function remove_logans_club_member_custom_roes_on_plugin_deactivation(){
			remove_role('logans_club_member_role');
	}
		
	register_deactivation_hook( __FILE__, 'remove_logans_club_member_custom_roes_on_plugin_deactivation' );
		

	add_action( 'show_user_profile', 'yoursite_extra_user_profile_fields' );

	add_action( 'edit_user_profile', 'yoursite_extra_user_profile_fields' );


	function yoursite_extra_user_profile_fields( $user ) {
	?>
	  <h3><?php _e("Logan's Club Membership", "blank"); ?></h3>
	  <table class="form-table">
		<tr>
		  <th><label for="logans-club-membership-number"><?php _e("Club Membership Number"); ?></label></th>
		  <td>
			<input type="text" name="logans-club-membership-number" id="logans-club-membership-number" 
				value="<?php echo esc_attr( get_the_author_meta( 'logans-club-membership-number', $user->ID ) ); ?>" size = "5"/> <i class="fa fa-thumbs-up fa-2x" style="color: green"></i><br />
			<span class="description"><?php _e("Please enter memberhsip number."); ?></span>
		  </td>
		</tr>
		</table>
		<table class="form-table">
		<tr>
		  <th>Expiry Date</th>
		  <td><input 	type="month" 
						name="logans-club-membership-expiry-date"
						id="logans-club-membership-expiry-date"
						value="<?php echo esc_attr( get_the_author_meta( 'logans-club-membership-expiry-date', $user->ID ) ); ?>"
						/></td>
		</tr>
	  </table>
	<?php
	}

	// Load Font Awesome icons for use in displaying membership status
	add_action('admin_head','load_font_awesome_icons');
	
	function load_font_awesome_icons(){
		echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"  rel="stylesheet">';
	}
	
	// Add save Club Membership Number when admin presses update
	add_action( 'edit_user_profile_update', 'save_logans_club_membership_number_user_profile_fields' );

	function save_logans_club_membership_number_user_profile_fields( $user_id ) {
	  $saved = false;
	  if ( current_user_can( 'edit_user', $user_id ) ) {
		update_user_meta( $user_id, 'logans-club-membership-number', $_POST['logans-club-membership-number'] );
		$saved = true;
	  }
	  return true;
	  }
	
	// Add save Club Membership expiry date when admin presses update
	add_action( 'edit_user_profile_update', 'save_logans_club_membership_expiry_date_user_profile_fields' );	
	function save_logans_club_membership_expiry_date_user_profile_fields( $user_id ) {
	  $saved = false;
	  if ( current_user_can( 'edit_user', $user_id ) ) {
		update_user_meta( $user_id, 'logans-club-membership-expiry-date', $_POST['logans-club-membership-expiry-date'] );
		$saved = true;
	  }
	  return true;
	  }
?>