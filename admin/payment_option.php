<?php
// create custom plugin settings menu
add_action('admin_menu', 'epdq_payment_create_menu');

function epdq_payment_create_menu() {
    //create new top-level menu
 //   add_menu_page('ePDQ Settings', 'ePDQ', 'administrator', __FILE__, 'epdq_payment_settings_page');
//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

add_submenu_page( "cpd_settings", "Payment Gateway Setting", "Payment Gateway Setting", "administrator", "payment_settings_page", "epdq_payment_settings_page" );
    //call register settings function
    add_action('admin_init', 'register_epdq_payment_settings');
}

function register_epdq_payment_settings() {
    //register our settings
    register_setting('epdq-settings-group', 'epdq_userid');
    register_setting('epdq-settings-group', 'epdq_key_in');
    register_setting('epdq-settings-group', 'epdq_key_out');
    register_setting('epdq-settings-group', 'epdq_sha_method');
    register_setting('epdq-settings-group', 'epdq_mode');
    register_setting('epdq-settings-group', 'epdq_txtcolor');
    register_setting('epdq-settings-group', 'epdq_tbltxtcolor');    
    register_setting('epdq-settings-group', 'epdq_btntxtcolor');
    register_setting('epdq-settings-group', 'epdq_bgcolor');
    register_setting('epdq-settings-group', 'epdq_tblbgcolor');
    register_setting('epdq-settings-group', 'epdq_btnbgcolor');
    register_setting('epdq-settings-group', 'epdq_title');
    register_setting('epdq-settings-group', 'epdq_logo');
}

function epdq_payment_settings_page() {
    ?>
    <div class="wrap">
        <h2>ePDQ Payment</h2>
        <form method="post" action="options.php">
            <?php settings_fields('epdq-settings-group'); ?>
            <?php do_settings_sections('epdq-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">ePDQ user ID</th>
                    <td><input type="text" name="epdq_userid" value="<?php echo esc_attr(get_option('epdq_userid')); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">SHA-IN</th>
                    <td><input type="text" name="epdq_key_in" value="<?php echo esc_attr(get_option('epdq_key_in')); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">SHA-OUT</th>
                    <td><input type="text" name="epdq_key_out" value="<?php echo esc_attr(get_option('epdq_key_out')); ?>" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">SHA mathod</th>
                       <td>
                        <?php
                        $live_mode = $test_mode = '';
                        $epdq_sha_mode = esc_attr(get_option('epdq_sha_method'));
                        if($epdq_sha_mode == 1){
                            $sha256 = 'selected';
                        } else if($epdq_sha_mode == 2) {
                            $sha512 = 'selected';
                        } else {
			    $sha1 = 'selected';
			}
                        ?>
                        <select name="epdq_sha_method">
                            <option value="0" <?php echo $sha1 ?>>SHA1</option>
                            <option value="1" <?php echo $sha256 ?>>SHA256</option>
			    <option value="2" <?php echo $sha512 ?>>SHA512</option>
                        </select>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Text Color </th>
                    <td><input type="text" name="epdq_txtcolor" value="<?php  $epdq_txtcolor = esc_attr(get_option('epdq_txtcolor')); if($epdq_txtcolor){ echo $epdq_txtcolor; }else { echo $TXTCOLOR = "#005588"; }?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Table Text Color</th>
                    <td><input type="text" name="epdq_tbltxtcolor" value="<?php $epdq_tbltxtcolor = esc_attr(get_option('epdq_tbltxtcolor')); if($epdq_tbltxtcolor) { echo $epdq_tbltxtcolor; }else { echo $TBLTXTCOLOR = "#005588"; } ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Button Text Color </th>
                    <td><input type="text" name="epdq_btntxtcolor" value="<?php  $epdq_btntxtcolor = esc_attr(get_option('epdq_btntxtcolor')); if($epdq_btntxtcolor) { echo $epdq_btntxtcolor; }else { echo $BUTTONTXTCOLOR = "#005588"; } ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td><input type="text" name="epdq_bgcolor" value="<?php $epdq_bgcolor =  esc_attr(get_option('epdq_bgcolor')); if($epdq_bgcolor) { echo $epdq_bgcolor; }else { echo $BGCOLOR = "#d1ecf3"; } ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Table Background Color</th>
                    <td><input type="text" name="epdq_tblbgcolor" value="<?php $epdq_tblbgcolor =  esc_attr(get_option('epdq_tblbgcolor')); if($epdq_tblbgcolor) { echo $epdq_tblbgcolor; }else { echo $TBLBGCOLOR = "#ffffff"; } ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Button Background Color</th>
                    <td><input type="text" name="epdq_btnbgcolor" value="<?php  $epdq_btnbgcolor = esc_attr(get_option('epdq_btnbgcolor')); if($epdq_btnbgcolor) { echo $epdq_btnbgcolor; }else { echo $BUTTONBGCOLOR = "#cccccc"; } ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Title</th>
                    <td><input type="text" name="epdq_title" value="<?php $epdq_title = esc_attr(get_option('epdq_title')); if($epdq_title) { echo $epdq_title; }else { echo $TITLE = "Merchant Shop - Secure Payment Page"; } ?>"  /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Logo</th>
                    <td><input type="text" name="epdq_logo" value="<?php $epdq_logo =  esc_attr(get_option('epdq_logo')); if($epdq_tbltxtcolor) { echo $epdq_logo; }else { echo $LOGO = "https://www.merchantsite.co.uk/images/merchant/bcarddemo/AV_SimpleLogo_BW.JPG"; } ?>" /></td>
                </tr>
        

                <tr valign="top">
                    <th scope="row">Mode (Live/Test)</th>
                    <td>
                        <?php
                        $live_mode = $test_mode = '';
                        $epdq_mode = esc_attr(get_option('epdq_mode'));
                        if($epdq_mode == 1){
                            $live_mode = 'selected';
                        } else {
                            $test_mode = 'selected';
                        }
                        ?>
                        <select name="epdq_mode">
                            <option value="1" <?php echo $live_mode ?>>Live</option>
                            <option value="0" <?php echo $test_mode ?>>Test</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
