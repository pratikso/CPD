<?php
function register_settings_page() {
    //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    add_menu_page( 'Product designer settings', 'Product designer settings', 'publish_posts', 'cpd_settings', 'cpd_settings_func',  plugins_url( 'custom_product_designer/images/settings.png' ), 33.3 );
}
add_action( 'admin_menu', 'register_settings_page' );
function cpd_settings_func()
{
     include_once PLUGIN_PATH.'admin/main_settings.php';
}
add_action( 'admin_init', 'register_cpd_settings' );
function register_cpd_settings() {
	register_setting( 'cpd-settings-group', 'checkout_page_id' );
        register_setting( 'cpd-settings-group', 'confirm_page_id' );
        register_setting( 'cpd-settings-group', 'return_page_id' );
	
}


 include_once PLUGIN_PATH.'admin/payment_option.php';
 include_once PLUGIN_PATH.'admin/order_page.php';


?>