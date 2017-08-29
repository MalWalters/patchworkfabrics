<?php
/************************************************
* Admin form for Logans Club Member discounts
* This form updates the options table with values for percentage discounts for all top level categories of products
*/
?>
<div class="logans-custom-settings">
		<h2>Club Members Discount</h2>
		<input type="hidden" name="pf-logans-club-member-discountadmin-form-hidden" value="Y">
		
		
		<?php
			if($_POST['pf-logans-club-member-discountadmin-form-hidden'] == 'Y'){
					//Form Data sent
					$current_discount_array = array(
												"Fabric"=>$_POST['clubMemberDiscount_Fabric'],
												"Panels"=>$_POST['clubMemberDiscount_Panels'],
												"FabricPacks"=>$_POST['clubMemberDiscount_FabricPacks'],
												"ProjectPacks"=>$_POST['clubMemberDiscount_ProjectPacks'],
												"Patterns"=>$_POST['clubMemberDiscount_Patterns'],
												"QuiltKits"=>$_POST['clubMemberDiscount_QuiltKits'],
												"Books"=>$_POST['clubMemberDiscount_Books'],
												"Haberdashery"=>$_POST['clubMemberDiscount_Haberdashery'],
												"Software"=>$_POST['clubMemberDiscount_Software']
												);
					update_option('pf-logans-club-member-discounts', $current_discount_array);
					?>
					<div class="updated"><p><strong>Update Successful.</strong></p></div>
					<?php
				} else {
					// Normal page display
					$current_discount_array = get_option('pf-logans-club-member-discounts');
					
				}
			?>
		
		<form name="pf-logans-club-member-discount-admin-form" method="post" action="">
			<table>
				<tr>
					<td>Fabric by the metre: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Fabric" value="<?php echo $current_discount_array['Fabric']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Panels: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Panels" value="<?php echo $current_discount_array['Panels']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Fabric Packs: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_FabricPacks" value="<?php echo $current_discount_array['FabricPacks']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Project Packs: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_ProjectPacks" value="<?php echo $current_discount_array['ProjectPacks']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Patterns: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Patterns" value="<?php echo $current_discount_array['Patterns']; ?>" style="text-align:right">%</td>
				</tr>
					<td>Quilt Kits: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_QuiltKits" value="<?php echo $current_discount_array['QuiltKits']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Books: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Books" value="<?php echo $current_discount_array['Books']; ?>" style="text-align:right">%</td>
				</tr>
				<tr>
					<td>Haberdashery: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Haberdashery" value="<?php echo $current_discount_array['Haberdashery']; ?>" style="text-align:right">%</td>
				</tr>
					<td>Software: </td><td><input type="number" min="0" max="100" step="0.25" name="clubMemberDiscount_Software" value="<?php echo $current_discount_array['Software']; ?>" style="text-align:right">%</td>
				</tr>
			</table>
		</form>
			<p class="submit">
			<input type="submit" name="Submit" value="Update" />
			</p>
		 </div>