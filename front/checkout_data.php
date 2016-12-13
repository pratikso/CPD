<?php
function checkout_data_func()
{
	$product_id = $_POST['product_id'];
	$product_name = $_POST['product_name'];
	$installment = $_POST['installment'];
	$product_info = stripslashes($_POST['product_info']);
	$device_info = stripslashes($_POST['device_info']);
	$user_cart_id = $_POST['user_cart_id'];
		
	global $wpdb;
	$wpdb->show_errors();
	$wpdb->insert(
	'wp_cpm_cart', array(
		'product_id' => $product_id,
		'product_name' => $product_name,
		'installment_price'=> $installment,
		'product_info'=> $product_info,
		'device_info'=> $device_info,
		 'user_cart_id'=> $user_cart_id	 
            ), array(
        '%d',
        '%s',
        '%f',
        '%s',
	'%s',
	'%s'
            )
    );
    $order_id = $wpdb->insert_id;
	die;
}

add_action( 'wp_ajax_checkout_data', 'checkout_data_func' );
add_action( 'wp_ajax_nopriv_checkout_data', 'checkout_data_func' );
?>