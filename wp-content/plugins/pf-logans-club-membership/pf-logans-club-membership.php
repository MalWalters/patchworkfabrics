<?php
/*
Plugin Name: Logans Club Membership
Plugin URL: 
Description: Logan's Club Membership. This creates a custom user role 'Logans Club Member'; custom fields on the user admin screen and settings options for category discounts
Version: 0.1
Author: Malcolm Walters
Author URI:  
*/

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	// Create custom user role when plugin is activated
	function add_logans_club_member_role_on_plugin_activation() {
       add_role( 'logans_club_member_role', 'Logans Club Member', array( 'read' => true, 'level_0' => true ) );
	}
	
    register_activation_hook( __FILE__, 'add_logans_club_member_role_on_plugin_activation' );
	
	// Remove custom user role when plugin is desactivated
	function remove_logans_club_member_custom_roes_on_plugin_adctivation(){
			remove_role('logans_club_member_role');
	}
		
	register_deactivation_hook( __FILE__, 'remove_logans_club_member_custom_roes_on_plugin_adctivation' );
	
	
	
	
	
?>