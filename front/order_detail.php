<?php function order_detail_func( $atts )
{
ob_start();
	
 $user_cart_id = $_COOKIE['user_cart'];
    if (!empty($user_cart_id))  {
        global $wpdb;
        $cart_data = $wpdb->get_row("SELECT * FROM wp_cpm_cart WHERE user_cart_id ='" . $user_cart_id . "'");
	$installment = $cart_data->installment_price;
	$lump = $installment*12;
	$lump = round($lump,2);
       //  print_r($cart_data);
	 ?>
	 
	 <div class="order_detail">
		 <div class="product_inner">
	 <h3>Order detail</h3>
	 
	
	<div class="order_row"> <strong>Installment Price : </strong><?php
	
	echo "&pound;".$installment; ?> </div>
	<div class="order_row"> <strong>One time payment Price : </strong><?php echo "&pound;".$lump; ?> </div>
	 <?php 	  
	  
	  $device_detail = json_decode($cart_data->device_info);
	   if(!empty($device_detail))
         {
	  ?>
	  </div>
	  <div class="device_detail">
		<h4>Other devices</h4>
		  <?php foreach ($device_detail as $device)
                {
                ?>
		 <div class="device_inner">
		 <?php
		 foreach($device as $key=>$value)
		  {  ?>
		 <?php if($key!="Device_id") { ?>
		<div class="order_row"> <strong><?php echo $key; ?> : </strong><?php echo $value; ?> </div>
		<?php } ?>
		<?php }
		?>
		</div>
		<?php
		} ?>
		
	  </div>
	  
	  <?php } ?>
	  
	 </div>
	 
	 <?php
    }
	
return ob_get_clean();
}
 add_shortcode( 'order_detail', 'order_detail_func' );
?>