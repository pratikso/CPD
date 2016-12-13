<?php
function confirm_page_func($atts) {
    global $wpdb;
    ob_start();
    $order_id = $_GET['order'];

    $PW = esc_attr(get_option('epdq_key_in'));//'tony999$123TONY11';
    $PSPID = esc_attr(get_option('epdq_userid'));//'tonyryan';

    // order description
    $OrderID = $order_id;    //Order Id - needs to be unique

    $order_obj = $wpdb->get_row("SELECT * FROM wp_cpm_order WHERE id = " . $order_id);
    $cart_id = $order_obj->cart_id;
    $cart_obj = $wpdb->get_row("SELECT * FROM wp_cpm_cart WHERE cart_id = " . $cart_id); //product_name
    $product_info = $cart_obj->product_info;
   //print_r($order_obj);
    //print_r(json_decode($product_info));
    //Product description
    $OrderDataRaw = $cart_obj->product_name . "($product_info->Month/$product_info->Year, $product_info->Company)";

    $user_str = $order_obj->user_data;
    $user_data = json_decode($user_str);
    $UserTitle = "";

    $UserFirstname = $user_data->first_name;
    $UserSurname = $user_data->last_name;
    $BillHouseNumber = $user_data->address_house_no;
    $Ad1 = $user_data->address_line_1;
    $Ad2 = $user_data->address_line_2;
    $BillTown = $user_data->address_city;
    $BillCountry = $user_data->address_country;
    $Pcde = "NN4 7SG";
    $ContactTel = $user_data->address_contact_no;
    $ShopperEmail = $user_data->address_email;
    $ShopperLocale = "en_GB";
    $CurrencyCode = "GBP";

    $Addressline1n2 = $BillHouseNumber . " " . $Ad1 . ", " . $Ad2;
    $CustomerName = $UserTitle . " " . $UserFirstname . " " . $UserSurname;
    if($order_obj->is_recurring == '1'){
        $is_shaduled = true;
    } else {
        $is_shaduled = false;
    }
    
    //installment_price
    $emi_cycle = 12;
    $insatllments = $cart_obj->installment_price;
    $amount = $insatllments * $emi_cycle;
    $amount = round($amount,2);
    $date_string = '';
    $date_input = '';
    $pay_string = '';
    $pay_input = '';

    $PaymentAmount = $amount * 100;
    if ($is_shaduled) {
         $emi_ammount = $insatllments*100;
	//round($PaymentAmount / $emi_cycle);
         $PaymentAmount1 = $PaymentAmount - ($emi_ammount * ($emi_cycle - 1 ));
        $pay_string .= "AMOUNT1=" . $PaymentAmount1 . $PW;
        $pay_input .= '<input type="hidden" name="AMOUNT1" value="' . $PaymentAmount1 . '">';
        for ($i = 1; $i < $emi_cycle; $i++) {
            $pay_string .= "AMOUNT" . ($i + 1) . "=" . $emi_ammount . $PW;
            $pay_input .= '<input type="hidden" name="AMOUNT' . ($i + 1) . '" value="' . $PaymentAmount1 . '">';
        }
        $payment_date = date("d-m-Y");;
        //echo "</br>";
        $time = strtotime($payment_date);
        $day = date('d', $time);
        for ($i = 1; $i < $emi_cycle; $i++) {
            if ($i == 1) {
                $days = 28;
            } else {
                $j = $i - 1;
                $days = 28 + (30 * $j);
            }
            $k = $i + 1;
            $months = $i;
            if (in_array($day, array(29, 30, 31))) {
                $final = date("t/m/Y", strtotime($days . " day", $time));
            } else {
                $final = date("d/m/Y", strtotime($months . " month", $time));
            }
            //echo "<br>";
            $date_input .= '<input type="hidden" name="EXECUTIONDATE' . $k . '" value="' . $final . '">';
            $date_string .='EXECUTIONDATE' . $k . '=' . $final . $PW;
        }
    }




    //- payment design options - //
    $TXTCOLOR = esc_attr(get_option('epdq_txtcolor'));
    $TBLTXTCOLOR = esc_attr(get_option('epdq_tbltxtcolor'));
    $FONTTYPE = "Helvetica, Arial";
    $BUTTONTXTCOLOR = esc_attr(get_option('epdq_btntxtcolor'));
    $BGCOLOR = esc_attr(get_option('epdq_bgcolor'));
    $TBLBGCOLOR = esc_attr(get_option('epdq_tblbgcolor'));
    $BUTTONBGCOLOR = esc_attr(get_option('epdq_btnbgcolor'));
    $TITLE = esc_attr(get_option('epdq_title'));
    $LOGO = esc_attr(get_option('epdq_logo'));
    $PMLISTTYPE = 1;

    //= create string to hash (digest) using values of options/details above
    $DigestivePlain = "AMOUNT=" . $PaymentAmount . $PW;
    if ($is_shaduled) {
        $DigestivePlain .= $pay_string;
    }

    $DigestivePlain .= "BGCOLOR=" . $BGCOLOR . $PW .
            "BUTTONBGCOLOR=" . $BUTTONBGCOLOR . $PW .
            "BUTTONTXTCOLOR=" . $BUTTONTXTCOLOR . $PW .
            "CN=" . $CustomerName . $PW .
            "COM=" . $OrderDataRaw . $PW .
            "CURRENCY=" . $CurrencyCode . $PW .
            "EMAIL=" . $ShopperEmail . $PW;
    if ($is_shaduled) {
        $DigestivePlain .= $date_string;
    }
    $DigestivePlain .= "FONTTYPE=" . $FONTTYPE . $PW .
            "LANGUAGE=" . $ShopperLocale . $PW .
            "LOGO=" . $LOGO . $PW .
            "ORDERID=" . $OrderID . $PW .
            "OWNERADDRESS=" . $Addressline1n2 . $PW .
            "OWNERCTY=" . $BillCountry . $PW .
            "OWNERTELNO=" . $ContactTel . $PW .
            "OWNERTOWN=" . $BillTown . $PW .
            "OWNERZIP=" . $Pcde . $PW .
            "PMLISTTYPE=" . $PMLISTTYPE . $PW .
            "PSPID=" . $PSPID . $PW .
            "TBLBGCOLOR=" . $TBLBGCOLOR . $PW .
            "TBLTXTCOLOR=" . $TBLTXTCOLOR . $PW .
            "TITLE=" . $TITLE . $PW .
            "TXTCOLOR=" . $TXTCOLOR . $PW .
            "";

    //=SHA encrypt the string=//
    $strHashedString_plain = strtoupper(sha1($DigestivePlain));
    ?>
    
    <?php $cart_data = $cart_obj;
    $installment = $cart_data->installment_price;
	$lump = $installment*12;
	$lump = round($lump,2);
    ?>
    
    <div class="order_detail">
		 <div class="product_inner">
	 <h3>Order detail</h3>
	 <div class="order_row"> <strong>Product Name : </strong><?php echo $cart_data->product_name; ?> </div>
	<?php
	$product_info = $cart_data->product_info;
	$product_info = json_decode($product_info);
	foreach($product_info as $key=>$value)
	{
		?>
		<div class="order_row"> <strong><?php echo $key; ?> : </strong><?php echo $value; ?> </div>
	<?php
	}
	
	?>
	<?php if($is_shaduled) {?>
	<div class="order_row"> <strong>Installment Price : </strong><?php echo "&pound;".$installment; ?> </div>
	<?php }else { ?>
	<div class="order_row"> <strong>One time payment Price : </strong><?php echo "&pound;".$lump; ?> </div>
	<?php } ?>
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
    
    
    
    
    <br/>
       
    
    <form name="OrderForm" id="OrderForm" action="https://payments.epdq.co.uk/ncol/prod/orderstandard.asp" method="POST">
        <input type="hidden" name="AMOUNT" id="AMOUNT" value="<?PHP print $PaymentAmount; ?>"/>
        <?php if ($is_shaduled) { ?>
            <?php echo $pay_input; ?>
            <?php echo $date_input; ?>
        <?php } ?>
        <input type="hidden" name="CN" value="<?PHP print $CustomerName; ?>"> 
        <input type="hidden" name="COM" value="<?PHP print $OrderDataRaw; ?>">
        <input type="hidden" name="CURRENCY" id="CURRENCY" value="<?PHP print $CurrencyCode; ?>"/>
        <input type="hidden" name="EMAIL" id="EMAIL" value="<?PHP print $ShopperEmail; ?>">
        <input type="hidden" name="FONTTYPE" id="FONTTYPE" value="<?PHP print $FONTTYPE; ?>">
        <input type="hidden" name="LANGUAGE" id="LANGUAGE" value="<?PHP print $ShopperLocale; ?>">
        <input type="hidden" name="LOGO" value="<?PHP print $LOGO; ?>">
        <input type="hidden" name="ORDERID" id="ORDERID" value="<?PHP print $OrderID ?>"/> 
        <input type="hidden" name="OWNERADDRESS" id="OWNERADDRESS" value="<?PHP print $Addressline1n2; ?>">
        <input type="hidden" name="OWNERCTY" id="OWNERCTY" value="<?PHP print $BillCountry; ?>">
        <input type="hidden" name="OWNERTELNO" value="<?PHP print $ContactTel; ?>"> 
        <input type="hidden" name="OWNERTOWN" id="OWNERTOWN" value="<?PHP print $BillTown; ?>">
        <input type="hidden" name="OWNERZIP" id="OWNERZIP" value="<?PHP print $Pcde; ?>">
        <input type="hidden" name="PMLISTTYPE" id="PMLISTTYPE" value="<?PHP print $PMLISTTYPE ?>"/>												
        <input type="hidden" name="PSPID" id="PSPID" value="<?PHP print $PSPID ?>"/>
        <input type="hidden" name="BGCOLOR" id="BGCOLOR" value="<?PHP print $BGCOLOR; ?>"/>
        <input type="hidden" name="BUTTONBGCOLOR" id="BUTTONBGCOLOR" value="<?PHP print $BUTTONBGCOLOR; ?>"/>
        <input type="hidden" name="BUTTONTXTCOLOR" id="BUTTONTXTCOLOR" value="<?PHP print $BUTTONTXTCOLOR; ?>"/>
        <input type="hidden" name="TBLBGCOLOR" id="TBLBGCOLOR" value="<?PHP print $TBLBGCOLOR; ?>"/>
        <input type="hidden" name="TBLTXTCOLOR" id="TBLTXTCOLOR" value="<?PHP print $TBLTXTCOLOR; ?>">
        <input type="hidden" name="TITLE" id="TITLE" value="<?PHP print $TITLE; ?>"/>
        <input type="hidden" name="TXTCOLOR" id="TXTCOLOR" value="<?PHP print $TXTCOLOR; ?>">
        <input type="hidden" name="SHASign" value="<?PHP print $strHashedString_plain; ?>">
        <input type="submit" id="tstbtn" value="I confirm my payment" />
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('cpd_confirm_page', 'confirm_page_func');
?>