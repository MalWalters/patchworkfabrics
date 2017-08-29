<?php
/*****************************************************
* Admin form for Minimum cut notice text
* This form updates the text that is stored in the options table and is displayed by the price box for single items if fabri by the metre
* 
*/
?>
<div class="logans-custom-settings">
		<h2>Minimum Cut Notice</h2>
		<input type="hidden" name="mfc_hidden" value="Y">
		<p>This is the text that will be shown to the customer when purchasing fabric by the metre.</p>

			<?php
			if($_POST['mfc_hidden'] == 'Y'){
					//Form Data sent
					$current_label = $_POST['mfc_label'];
					update_option('mfc_label', $current_label);
					?>
					<div class="updated"><p><strong>Update Successful..</strong></p></div>
					<?php
				} else {
					// Normal page display
					$current_label = get_option('mfc_label');
				}
			?>
		<form name="mfc_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mfc_hidden" value="Y">
			<?php echo "<h4>Current Label</h4>"; ?>
			<p><input type="text" name="mfc_label" value="<?php echo $current_label; ?>" size='60'></p>
			<p class="submit">
			<input type="submit" name="Submit" value="Update" />
			</p>
		</form>
			
	</div>
