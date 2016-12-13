<?php
global $post;

function adding_custom_meta_boxes( $post ) {
    add_meta_box( 
        'product_price',
        __( 'Installment Price' ),
        'my_meta_box',
        'custom_product'
          );
    
     add_meta_box( 
        'other_devices',
        __( 'Other Devices' ),
        'other_device_meta',
        'custom_product'
          );
    
    
    add_meta_box( 
        'product_extra_fields',
        __( 'Product Extra Fields' ),
        'product_extra_field_func',
        'custom_product'
          );
    
}
add_action( 'add_meta_boxes_custom_product', 'adding_custom_meta_boxes' );
function my_meta_box($post)
{
   echo '&pound;<input type="text" name="_product_price" value="'.get_post_meta($post->ID,"_product_price",true).'">';
}
add_action('save_post','my_meta_save');

function my_meta_save($post_id)
{
   $new_data = $_POST['_product_price'];   
   $allow_devices=$_POST['_allow_device'];
   $extra_device_price = $_POST['_extra_device_price'];
   $show_year = $_POST['_show_year'];
   $show_month = $_POST['_show_month'];
   $extra_field_1 = $_POST['_extra_field_1'];
   $extra_field_2 = $_POST['_extra_field_2'];
   $extra_field_3 = $_POST['_extra_field_3'];
    $product_type = $_POST['_product_type'];
   $enabled_devices = $_POST['_enabled_devices'];
   update_post_meta($post_id,'_product_price',$new_data);
   update_post_meta($post_id,'_allow_device',$allow_devices);
   update_post_meta($post_id,'_extra_device_price',$extra_device_price);
   update_post_meta($post_id,'_show_year',$show_year);
   update_post_meta($post_id,'_show_month',$show_month);
   update_post_meta($post_id,'_extra_field_1',$extra_field_1);
   update_post_meta($post_id,'_extra_field_2',$extra_field_2);
   update_post_meta($post_id,'_extra_field_3',$extra_field_3);
   update_post_meta($post_id,'_product_type',$product_type);
   update_post_meta($post_id,'_enabled_devices',$enabled_devices);  

}

function other_device_meta($post)
{
    $product_id = $post->ID;
    $allow_devices = get_post_meta( $product_id ,"_allow_device",true);
    
    echo '<div><input type="checkbox" name="_allow_device" value="yes" '.checked( $allow_devices, "yes",0 ).' >Allow adding other devices</div>';
    echo "<br/><br/>";
    echo '<div>Price for each extra device<br/>&pound;<input type="text" name="_extra_device_price" value="'.get_post_meta($post->ID,"_extra_device_price",true).'"></div>';
    echo "<br/><strong>Enable Devices:</strong><br/>";
	$enabled_device_arr = get_post_meta( $product_id,"_enabled_devices",true);
	
	
    $args = array (
	"post_type" => "custom_device",
	"posts_per_page" => -1,
	"post_parent" => 0
    );
    	
	query_posts($args);
	while(have_posts()):
	the_post();
	$device_id = get_the_ID();
	
	echo '<input type="checkbox" name="_enabled_devices['.$device_id.']" value="on" '.checked( $enabled_device_arr[$device_id], "on",0 ).' >';
	echo the_title();
	echo "&nbsp; &nbsp;";
	endwhile;
	wp_reset_query();
}


function product_extra_field_func($post)
{
	$show_year = get_post_meta($post->ID,"_show_year",true);
	$show_month = get_post_meta($post->ID,"_show_month",true);
	echo '<div><input type="checkbox" name="_show_year" value="yes" '.checked( $show_year, "yes",0 ).' >Year</div>';
	echo '<div><input type="checkbox" name="_show_month" value="yes" '.checked( $show_month, "yes",0 ).' >Month</div>';	
	//echo '<br/><div><i>(Enter comma separated list of product types, example: type1, type2.)</i></div>';
	echo '<br/><div> Product Type <br/><i>(Enter comma separated list of product types, example: type1, type2.)</i><br/><input type="text" class="long_txt" name="_product_type" value="'.get_post_meta($post->ID,"_product_type",true).'"></div>';
	
	
	echo '<br/><div><i>(Enter name of extra fields for this products.)</i></div>';
	echo '<div> Extra field 1<br/><input type="text" name="_extra_field_1" value="'.get_post_meta($post->ID,"_extra_field_1",true).'"></div>';
	echo '<br/><div> Extra field 2<br/><input type="text" name="_extra_field_2" value="'.get_post_meta($post->ID,"_extra_field_2",true).'"></div>';
	echo '<br/><div> Extra field 3<br/><input type="text" name="_extra_field_3" value="'.get_post_meta($post->ID,"_extra_field_3",true).'"></div>';
}




function adding_custom_meta_boxes_device( $post ) 
{
	 add_meta_box( 
        'product_extra_fields',
        __( 'Product Extra Fields' ),
        'device_extra_field_func',
        'custom_device'
          );
}
add_action( 'add_meta_boxes_custom_device', 'adding_custom_meta_boxes_device' );

function device_extra_field_func($post)
{
	$show_year = get_post_meta($post->ID,"_show_year",true);
	$show_month = get_post_meta($post->ID,"_show_month",true);
	echo '<div><input type="checkbox" name="_show_year" value="yes" '.checked( $show_year, "yes",0 ).' >Year</div>';
	echo '<div><input type="checkbox" name="_show_month" value="yes" '.checked( $show_month, "yes",0 ).' >Month</div>';	
	
	echo '<br/><div><i>(Enter name of extra fields for this products.)</i></div>';
	echo '<div> Extra field 1<br/><input type="text" name="_extra_field_1" value="'.get_post_meta($post->ID,"_extra_field_1",true).'"></div>';
	echo '<br/><div> Extra field 2<br/><input type="text" name="_extra_field_2" value="'.get_post_meta($post->ID,"_extra_field_2",true).'"></div>';
	echo '<br/><div> Extra field 3<br/><input type="text" name="_extra_field_3" value="'.get_post_meta($post->ID,"_extra_field_3",true).'"></div>';
}
?>