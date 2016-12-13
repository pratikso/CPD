<?php

function return_page_func($atts) {
    ob_start();
    print_r($_REQUEST);
    $debug_msg = ob_get_clean();
    wp_mail('pratik.s@cisinlabs.com', 'ePDQ payment details', $debug_msg );
    wp_mail('pratik.s@mailinator.com', 'ePDQ payment details', $debug_msg );
    ob_start();
    //print_r($_GET);
    $sha_method = get_option('epdq_sha_method', 0);
    $aavscheck = 'yes';
    $cvccheck = 'yes';
    $sha_out = get_option('epdq_key_out');
    
    

//@ob_clean();
//header('HTTP/1.1 200 OK');

    $x = array(
        'ORDERID' => isset($_REQUEST['orderID']) ? $_REQUEST['orderID'] : '',
        'CURRENCY' => isset($_REQUEST['currency']) ? $_REQUEST['currency'] : '',
        'AMOUNT' => isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '',
        'PM' => isset($_REQUEST['PM']) ? $_REQUEST['PM'] : '',
        'STATUS' => isset($_REQUEST['STATUS']) ? $_REQUEST['STATUS'] : '',
        'CARDNO' => isset($_REQUEST['CARDNO']) ? $_REQUEST['CARDNO'] : '',
        'ED' => isset($_REQUEST['ED']) ? $_REQUEST['ED'] : '',
        'CN' => isset($_REQUEST['CN']) ? $_REQUEST['CN'] : '',
        'TRXDATE' => isset($_REQUEST['TRXDATE']) ? $_REQUEST['TRXDATE'] : '',
        'PAYID' => isset($_REQUEST['PAYID']) ? $_REQUEST['PAYID'] : '',
        'NCERROR' => isset($_REQUEST['NCERROR']) ? $_REQUEST['NCERROR'] : '',
        'BRAND' => isset($_REQUEST['BRAND']) ? $_REQUEST['BRAND'] : '',
        'IP' => isset($_REQUEST['IP']) ? $_REQUEST['IP'] : '',
        'AAVADDRESS' => isset($_REQUEST['AAVADDRESS']) ? $_REQUEST['AAVADDRESS'] : '',
        'AAVCHECK' => isset($_REQUEST['AAVCheck']) ? $_REQUEST['AAVCheck'] : ($aavscheck == 'yes') ? 'NO' : '',
        'AAVZIP' => isset($_REQUEST['AAVZIP']) ? $_REQUEST['AAVZIP'] : '',
        'AAVMAIL' => isset($_REQUEST['AAVMAIL']) ? $_REQUEST['AAVMAIL'] : '',
        'AAVNAME' => isset($_REQUEST['AAVNAME']) ? $_REQUEST['AAVNAME'] : '',
        'AAVPHONE' => isset($_REQUEST['AAVPHONE']) ? $_REQUEST['AAVPHONE'] : '',
        'ACCEPTANCE' => isset($_REQUEST['ACCEPTANCE']) ? $_REQUEST['ACCEPTANCE'] : '',
        'BIN' => isset($_REQUEST['BIN']) ? $_REQUEST['BIN'] : '',
        'CCCTY' => isset($_REQUEST['CCCTY']) ? $_REQUEST['CCCTY'] : '',
        'COMPLUS' => isset($_REQUEST['COMPLUS']) ? $_REQUEST['COMPLUS'] : '',
        'CVCCHECK' => isset($_REQUEST['CVCCheck']) ? $_REQUEST['CVCCheck'] : ($cvccheck == 'yes') ? 'NO' : '',
        'ECI' => isset($_REQUEST['ECI']) ? $_REQUEST['ECI'] : '',
        'FXAMOUNT' => isset($_REQUEST['FXAMOUNT']) ? $_REQUEST['FXAMOUNT'] : '',
        'FXCURRENCY' => isset($_REQUEST['FXCURRENCY']) ? $_REQUEST['FXCURRENCY'] : '',
        'IPCTY' => isset($_REQUEST['IPCTY']) ? $_REQUEST['IPCTY'] : '',
        'SUBBRAND' => isset($_REQUEST['SUBBRAND']) ? $_REQUEST['SUBBRAND'] : '',
        'VC' => isset($_REQUEST['VC']) ? $_REQUEST['VC'] : '',
    );

    $SHASIGN = isset($_REQUEST['SHASIGN']) ? $_REQUEST['SHASIGN'] : '';
    ksort($x);
    $xy = '';

    foreach ($x as $k => $v) {
        if ($v == '')
            continue;
        $xy.=strtoupper($k) . '=' . $v . $sha_out;
    }

    if ($sha_method == 0)
        $shasignxy = sha1($xy);
    elseif ($sha_method == 1)
        $shasignxy = hash('sha256', $xy);
    elseif ($sha_method == 2)
        $shasignxy = hash('sha512', $xy);
    else {
        
    }

    //if (strtolower($shasignxy) != strtolower($SHASIGN)) {
    if(!empty($x['ORDERID'])){
//        echo '<pre>';
//        print_r($x);
//        print_r($shasignxy);
//        print_r($SHASIGN);
        //print_r('Transaction verification error!');
//        echo '</pre>';
    //} else {
        $_GET['STATUS'] = intval($_GET['STATUS']);
        if (in_array($_GET['STATUS'], array(4, 5, 9, 56))) {
            $noteT = 'Barclay ePDQ transaction is confirmed.<br>';
            $status = 'confirmed';
        } elseif (in_array($_GET['STATUS'], array(41, 51, 91))) {
            $noteT = 'Barclay ePDQ transaction is awaiting for confirmation.<br>';
            $status = 'confirmed';
        } elseif ($_GET['STATUS'] == 2 or $_GET['STATUS'] == 93 or $_GET['STATUS'] == 52 or $_GET['STATUS'] == 92) {
            //$dienote .='<br>Order is failed.';
            $noteT = 'Order is failed.<br>';
            $status = 'failed';
        } elseif ($_GET['STATUS'] == 1) {
            $dienote .='<br>Order is cancelled.';
            $noteT = 'Order is cancelled.<br>';
            $status = 'cancelled';
        } else {
            $noteT = 'Order is failed.<br>';
            $status = 'failed';
        }
        global $wpdb;
        $wpdb->update(
                'wp_cpm_order', array(
            'status' => $status, // string
                ), array('id' => $_GET['orderID']), array(
            '%s', // value1
                ), array('%d')
        );
        echo $noteT;
        ?>

        <?php

    }
    $add_msg = ob_get_clean();
    wp_mail('pratik.s@cisinlabs.com', 'ePDQ debug payment details', $add_msg );
    wp_mail('pratik.s@mailinator.com', 'ePDQ debug payment details', $add_msg );
    return $add_msg;
}

add_shortcode('cpd_return_page', 'return_page_func');
/*
0	Incomplete or invalid
1	Cancelled by customer
2	Authorisation declined
4	Order stored
40	Stored waiting external result
41	Waiting for client payment
5	Authorised
50	Authorized waiting external result
51	Authorisation waiting
52	Authorisation not known
55	Standby
56	OK with scheduled payments
57	Not OK with scheduled payments
59	Authoris. to be requested manually
6	Authorised and cancelled
61	Author. deletion waiting
62	Author. deletion uncertain
63	Author. deletion refused
64	Authorised and cancelled
7	Payment deleted
71	Payment deletion pending
72	Payment deletion uncertain
73	Payment deletion refused
74	Payment deleted
75	Deletion processed by merchant
8	Refund
81	Refund pending
82	Refund uncertain
83	Refund refused
84	Payment declined by the acquirer
85	Refund processed by merchant
9	Payment requested
91	Payment processing
92	Payment uncertain
93	Payment refused
94	Refund declined by the acquirer
95	Payment processed by merchant
96	Refund reversed
99	Being processed
 */

?>