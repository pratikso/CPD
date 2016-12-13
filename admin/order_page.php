<?php
ob_start();
// create custom plugin settings menu
add_action('admin_menu', 'cpd_order_create_menu');

function cpd_order_create_menu() {
    //create new top-level menu
    //   add_menu_page('ePDQ Settings', 'ePDQ', 'administrator', __FILE__, 'epdq_payment_settings_page');
//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

    add_submenu_page("cpd_settings", "Orders", "Orders", "administrator", "cpd_order_page", "cpd_order_page_func");
    add_submenu_page("cpd_order_page", "Order Detail", "Order Detail", "administrator", "cpd_order_detail_page", "cpd_order_detail_page_func");
    //call register settings function   
}

function cpd_order_page_func() {
    ?>
    <div class="wrap">
        <h2>Orders</h2>
        <br/>

        <?php
        global $wpdb;

        // print_r($orders);
        if (isset($_POST['delete_order'])) {
            if (!empty($_POST['select_order'])) {
                $order_id_arr = $_POST['select_order'];
                // print_r($order_id_arr);
                $order_id_list = implode(",", $order_id_arr);
                //   echo $order_id_list;

                $query = "DELETE FROM wp_cpm_order WHERE id IN (" . $order_id_list . ")";

                $wpdb->query($query);

                echo "<div class='success_msg'>Selected orders deleted successfully.</div>";
            } else {
                echo "<div class='error_msg'>No orders selected.</div>";
            }
        }
        ?>
        <form action="" method="post">
            <table class="order_table">
                <tr>
                    <th><input type="checkbox" class="select_all_order"></th>
                    <th>Order Id</th>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Payment Mode</th>
                    <th>Order Date</th>
                    <th>Payment ID</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>


    <?php
    $orders = $wpdb->get_results("SELECT * FROM wp_cpm_order ORDER BY order_date DESC");
    $i = 1;
    foreach ($orders as $order) {
        $order_id = $order->id;
        $cart_id = $order->cart_id;
        $cart_detail = $wpdb->get_row("SELECT * FROM wp_cpm_cart WHERE cart_id ='" . $cart_id . "'");

        //  echo "<br/><br/>";
        //print_r($cart_detail);

        $class = "odd";
        $is_even = $i % 2;
        if ($is_even == 0) {
            $class = "even";
        }
        ?>

                    <tr class="<?php echo $class; ?>">
                        <td><input type="checkbox" name="select_order[]" class="order_chk" value="<?php echo $order_id; ?>"></td>
                        <td><?php echo $order_id; ?></td>
                        <td><?php echo $cart_detail->product_id; ?></td>
                        <td><?php echo $cart_detail->product_name; ?></td>
                        <td>
                    <?php
                    $is_recurring = $order->is_recurring;
                    if ($is_recurring == 1) {
                        echo "Recurring";
                    } else {
                        echo "One-off payment";
                    }
                    ?>

                        </td>
                        <td>
        <?php
        $date = $order->order_date;
        echo $date = date("d/m/Y", strtotime($date));
        ?>
                        </td>
                        <td><?php echo $order->payment_id; ?></td>
                        <td><?php echo $order->status; ?></td>
                        <td>
                            <?php
                            $order_detail_url = admin_url('admin.php?page=cpd_order_detail_page&order_id=' . $order->id);
                            ?>
                            <a href="<?php echo $order_detail_url; ?>">View Detail</a></td>
                    </tr>
        <?php
        $i++;
    }
    ?>

            </table>
            <input type="submit" value="Delete selected" name="delete_order">
        </form>


    </div>
                    <?php }

                    function cpd_order_detail_page_func() {
                        ?>
    <div class="wrap order_detail_page">
        <h2>Order Detail</h2>
                <?php
                $order_id = $_REQUEST['order_id'];
                global $wpdb;
                ?>
        <div class="order_detail_page">
    <?php
    //order status change
    if (isset($_POST['save_order_status'])) {
        $temp_order_status = $_POST['order_status_change'];
        $wpdb->update(
                'wp_cpm_order', array(
            'status' => $temp_order_status, // string
                ), array('id' => $order_id), array(
            '%s', // value1
                ), array('%d')
        );
        echo "<div class='success_msg'>Order status changed successfully.</div>";
    }


    $order = $wpdb->get_row("SELECT * FROM wp_cpm_order WHERE id ='" . $order_id . "'");
    $cart_id = $order->cart_id;
    $cart_detail = $wpdb->get_row("SELECT * FROM wp_cpm_cart WHERE cart_id ='" . $cart_id . "'");
    ?>
            <div class="left_col" >
                <h3>Product Detail</h3>
                <table>
                    <tr>
                        <td><strong> Product ID: </strong></td>
                        <td><?php echo $cart_detail->product_id; ?> </td>
                    </tr>

                    <tr>
                        <td><strong> Product Name: </strong></td>
                        <td> <?php echo $cart_detail->product_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong> Order Date: </strong></td>
                        <td>  <?php
            $date = $order->order_date;
            echo $date = date("d/m/Y", strtotime($date));
            ?></td>


                    </tr>
    <?php
    $product_info = $cart_detail->product_info;
    $product_info = json_decode($product_info);

    foreach ($product_info as $key => $value) {
        ?>
                        <tr>
                            <td><strong><?php echo $key; ?></strong></td>
                            <td><?php echo $value; ?></td>
                        </tr>
    <?php } ?>

                </table>
                <br/>
                <h3>Customer Detail</h3>
                            <?php
                            $user_data = json_decode($order->user_data);
                            //  print_r($user_data);
                            ?>
                <table>
                    <tr>
                        <td><strong> Name: </strong></td>
                        <td><?php echo $user_data->prefix . " " . $user_data->first_name . " " . $user_data->last_name; ?> </td>
                    </tr>

                    <tr>
                        <td><strong> Address: </strong></td>
                        <td> <?php echo $user_data->address_house_no . ", " . $user_data->address_line_1 . " " . $user_data->address_line_2; ?></td>
                    </tr>
                    <tr>
                        <td><strong> City: </strong></td>
                        <td> <?php echo $user_data->address_city; ?></td>
                    </tr>

                    <tr>
                        <td><strong> Postcode: </strong></td>
                        <td> <?php echo $user_data->address_postcode; ?></td>
                    </tr>



                    <tr>
                        <td><strong> Country: </strong></td>
                        <td> <?php echo $user_data->address_country; ?></td>
                    </tr>

                    <tr>
                        <td><strong> Email: </strong></td>
                        <td> <?php echo $user_data->address_email; ?></td>
                    </tr>
                    <tr>
                        <td><strong> Contact No.: </strong></td>
                        <td> <?php echo $user_data->address_contact_no; ?></td>
                    </tr>

                    <tr>
                        <td><strong> Home Phone Number.: </strong></td>
                        <td> <?php echo $user_data->address_home_contact_no; ?></td>
                    </tr>


                </table>

                <br/>
                <h3>Payment Detail</h3>
                <table>
                    <tr>
                        <td><strong> Total Payment: </strong></td>
                        <td><?php
            $installment = $cart_detail->installment_price;
            echo "&pound; " . $installment * 12;
            ?> </td>
                    </tr>
                    <tr>
                        <td><strong> Payment Type: </strong></td>
                        <td><?php
            $is_recurring = $order->is_recurring;
            if ($is_recurring == 0) {
                echo "Full Payment";
            } else {
                echo "Recurring";
            }
            ?> </td>
                    </tr>
                    <tr>
                        <td><strong> Payment Status: </strong></td>
                        <td><?php $order_status = $order->status; ?>
                            <form action="" method="post">
                                <select name="order_status_change">
                                    <option value="pending" <?php selected($order_status, "pending"); ?>> Pending </option>
                                    <option value="confirmed" <?php selected($order_status, "confirmed"); ?>> Confirmed </option>
                                    <option value="cancelled" <?php selected($order_status, "cancelled"); ?>> Cancelled </option>
                                    <option value="pending" <?php selected($order_status, "pending"); ?>> Pending </option>
                                </select>
                                <input type="submit" name="save_order_status" value="Change Status">
                            </form>
                        </td>
                    </tr>


                </table>

            </div>



            <div class="Right_col">
    <?php
    // echo $cart_detail->device_info;
    $device_detail = json_decode($cart_detail->device_info);
    // print_r($device_detail);
    if (!empty($device_detail)) {
        ?>
                    <h3>Device Detail:</h3>



                    <table>
        <?php
        foreach ($device_detail as $device) {
            ?>

            <?php foreach ($device as $key => $value) {
                ?>
                                <tr>
                                    <td><strong> <?php echo $key; ?> </strong></td>
                                    <td><?php echo $value; ?></td>
                                </tr>
                        <?php } ?>
                            <tr><td colspan="2">------------</td></tr>

                    <?php } ?>

                    </table>
    <?php } ?>       
            </div>

        </div>
    </div>
                    <?php ob_flush();
                } ?>