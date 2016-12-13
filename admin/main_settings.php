<h2>Settings</h2>
<?php
if (current_user_can( 'manage_options' ))
{
?>
<form method="post" action="options.php" class="cpd-setting-form">
     <?php
     
settings_fields( 'cpd-settings-group' );
do_settings_sections( 'cpd-settings-group' );
     ?>
<table>
     <tr>
     <th>  Checkout Page   </th>
    
          
          <td>
			<select name="checkout_page_id">
			<option value="">-Select Page-</option>
			<?php
			$checkout_page_id = get_option('checkout_page_id');
			$pages = get_pages(); 
			foreach ( $pages as $page ) {
			$option = '<option value="'. $page->ID.'"'.selected( $page->ID, $checkout_page_id,false ).'>';
			$option .= $page->post_title;
			$option .= '</option>';
			echo $option;
			}
			?>
			
			</select><br/>
			<?php  if ($checkout_page_id =="")
		{
	       echo '<div class="error_msg">Please select checkout page.</div>';
		echo "Create a page, put shortcode <strong>[cpd_checkout]</strong> in page editor, Then select that page from above list.";
		}
		?>
		<br/>
		</td>
          
          
     
     </tr>
     
     <tr>
     <th>  Checkout confirm page   </th>
    
          
          <td>
			<select name="confirm_page_id">
			<option value="">-Select Page-</option>
			<?php
			$confirm_page_id = get_option('confirm_page_id');
			$pages = get_pages(); 
			foreach ( $pages as $page ) {
			$option = '<option value="'. $page->ID.'"'.selected( $page->ID, $confirm_page_id,false ).'>';
			$option .= $page->post_title;
			$option .= '</option>';
			echo $option;
			}
			?>
			
			</select><br/>
			<?php  if ($confirm_page_id =="")
		{
			echo '<div class="error_msg">Please select checkout page.</div>';
		echo "Create a page, put shortcode <strong>[cpd_confirm_page]</strong> in page editor, Then select that page from above list.";
		}
		?>
		<br/>
		</td>
     </tr>
     
     <tr>
     <th>  Payment return page    </th>
    
          <td>
			<select name="return_page_id">
			<option value="">-Select Page-</option>
			<?php
			$return_page_id = get_option('return_page_id');
			$pages = get_pages(); 
			foreach ( $pages as $page ) {
			$option = '<option value="'. $page->ID.'"'.selected( $page->ID, $return_page_id,false ).'>';
			$option .= $page->post_title;
			$option .= '</option>';
			echo $option;
			}
			?>
			
			</select><br/>
			<?php  if ($return_page_id =="")
		{
		echo '<div class="error_msg">Please select return page.</div>';
		echo "Create a page, put shortcode <strong>[cpd_return_page]</strong> in page editor, Then select that page from above list.";
		}
		?>
		<br/>
	  </td>
     
     </tr>
</table>
<?php submit_button(); ?>
     
</form>

<?php }
else{
      echo "<br/><br/><div class='error_msg'>You don't have sufficient permission to access this page!</div>";	
}
?>

