<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('init', 'return_controler_func');

function return_controler_func() {
    if (!isset($_POST['orderID'])) {
        return;
    }
    $sha_method = get_option('epdq_sha_method', 0);
    $aavscheck = 'yes';
    $cvccheck = 'yes';
    $sha_out = get_option('epdq_key_out');

    @ob_clean();
    header('HTTP/1.1 200 OK');

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

    if (strtolower($shasignxy) != strtolower($SHASIGN)) {
        print_r('Transaction verification error!');
    } else {
        $_GET['STATUS'] = intval($_GET['STATUS']);
        if (in_array($_GET['STATUS'], array(4, 5, 9))) {
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
    }
    die;
}