<?php
$cart_id = $_POST['cart_id'];
$user_email = $_POST['address_email'];
$admin_email = get_bloginfo( 'admin_email' );

$user_name = $_POST['prefix']." ".$_POST['first_name']." ".$_POST['last_name'];
global $wpdb;
$order_obj = $wpdb->get_row("SELECT * FROM wp_cpm_order WHERE id = " . $order_id);
$cart_obj = $wpdb->get_row("SELECT * FROM wp_cpm_cart WHERE cart_id = " . $cart_id);
$product_info = $cart_obj->product_info;
$device_detail = json_decode($cart_obj->device_info);
$user_data = json_decode($order_obj->user_data);
$product_info = json_decode($product_info);
$installment = $cart_obj->installment_price;
	$lump = $installment*12;
	$lump = round($lump,2);

$user_message = "Hello ".$user_name.",";
$user_message.= "<br/>Thanks for your order.<br/>";



$admin_message = "Hello,<br/>";
$admin_message.= "You have received new order<br/>";

$admin_message.= "<br/><b>Customer Detail:</b><br/><br/>";
$admin_message.= "<table>";
$admin_message.= "<tr><td>Customer Name </td><td> - </td><td>".$user_data->prefix." ".$user_data->first_name." ".$user_data->last_name."</td></tr>";

$admin_message.= "<tr><td>Address </td><td> - </td><td>".$user_data->address_house_no.", ".$user_data->address_line_1."<br/>".$user_data->address_line_2."</td></tr>";
$admin_message.= "<tr><td>City </td><td> - </td><td>".$user_data->address_city."</td></tr>";
$admin_message.= "<tr><td>Postcode</td><td> - </td><td>".$user_data->address_postcode."</td></tr>";
$admin_message.= "<tr><td>Country</td><td> - </td><td>".$user_data->address_country."</td></tr>";
$admin_message.= "<tr><td>Email</td><td> - </td><td>".$user_data->address_email."</td></tr>";
$admin_message.= "<tr><td>Contact No.</td><td> - </td><td>".$user_data->address_contact_no."</td></tr>";
$admin_message.= "<tr><td>Home Phone no. </td><td> - </td><td>".$user_data->address_home_contact_no."</td></tr>";
$admin_message.= "</table>";

$message.= "<br/><b>Order detail:</b> <br/>";

$message.="<table>";
$message.= "<tr><td>Product_name</td><td> - </td><td>".$cart_obj->product_name."</td></tr>";
foreach($product_info as $key=>$value)
{
$message.= "<tr><td>".$key."</td><td> - </td> <td>".$value."</td></tr>";	
}
$message.= "<br/>";
$message.="</table>";
if(!empty($device_detail))
{
$message.= "<br/><b>Device Info</b><br/><br/>";

	foreach ($device_detail as $device)
	{
		$message.="<table>";
		 foreach($device as $key=>$value)
		  {
			if($key!="Device_id")
			{
		   $message.= "<tr><td>".$key."</td><td> - </td> <td>".$value."</td></tr>";
			}
		  }	
		$message.="</table>";
		 $message.="<br/>------------<br/>";		
	}

}
if($order_obj->is_recurring == '1'){
	$message.= "<br/><b>Payment type:</b> Monthly payment &pound;".$installment;
}
else
{
	$message.= "<br/><b>Payment type:</b> One time payment &pound;".$lump;
}
$message .= "<br/><br/>Digital Support Services";
//echo $user_message.$message;
///print_r($order_obj);

//echo "<br/><br/>";
//echo $admin_message.$message;
///print_r($cart_obj);



$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: Digital Support Services<noreply@digitalsupportservices.co.uk.com>' . "\r\n";

$user_subject = "You have made new order.";

$admin_subject = "You have received new order.";
//Send email to admin
mail($admin_email,$admin_subject,$admin_message.$message,$headers);

//Send email to user
mail($user_email,$user_subject,$user_message.$message,$headers);

?>