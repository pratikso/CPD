<?php

//front end include
include_once PLUGIN_PATH.'front/shortcodes.php';
include_once PLUGIN_PATH.'front/checkout_data.php';
include_once PLUGIN_PATH.'front/return_controller.php';

add_action( 'admin_enqueue_scripts', 'add_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'add_front_scripts' );
       
    function add_front_scripts() {
        $checkout_page_id = get_option('checkout_page_id');
        $checkout_page_url = get_permalink($checkout_page_id);
       wp_enqueue_script('jquery');
       wp_enqueue_script('js_validate', plugins_url('js/jquery.validate.min.js',__FILE__),'jquery');
       wp_enqueue_script('js_frontend', plugins_url('js/front_js.js',__FILE__),'jquery');
     
        wp_localize_script('js_frontend', 'front_js_vars', array(
      //  'video_placeholder' => plugins_url('images/video_placeholder.jpg', __FILE__),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'domain_name' => get_bloginfo('url'),
        'checkout_page_url' => esc_url($checkout_page_url)
      ));
        wp_enqueue_style( 'front_style', plugins_url('css/front_style.css', __FILE__) );
    }
    
       
    //back end include
    include_once PLUGIN_PATH.'admin/custom_post_types.php';
    include_once PLUGIN_PATH.'admin/meta_box.php';
    include_once PLUGIN_PATH.'admin/admin_menu.php';
    
    function add_admin_scripts()
    {
         wp_enqueue_script('jquery');
          wp_enqueue_style( 'admin_style', plugins_url('css/admin_style.css', __FILE__) );
           wp_enqueue_script('js_admin', plugins_url('js/admin_js.js',__FILE__),'jquery');
    }
        
    add_action( 'after_setup_theme', 'theme_setup_func' );
     function theme_setup_func() {
       add_image_size( 'product_thumb', 100, 100, true ); // (cropped)
}
 

?>
