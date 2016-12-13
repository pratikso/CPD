<?php
function show_product_func( $atts )
{
	ob_start();
    $a = shortcode_atts( array(
        'test' => 'test_value',
        'product_id' => $_REQUEST["product_id"]
          ), $atts );
          $test = $a['test'];
          $loading_img = plugins_url('images/loading.gif',dirname(__FILE__));
    
	$product_id = $a["product_id"];
      
         $product = get_post( $product_id );
         $extra_device_price = get_post_meta($product_id,"_extra_device_price",true);
	 $extra_device_price = round($extra_device_price,2);
	 $show_year = get_post_meta($product_id,"_show_year",true);
	 $show_month = get_post_meta($product_id,"_show_month",true);
	 
         $extra_field_1 = get_post_meta($product_id,"_extra_field_1",true);
         $extra_field_2 = get_post_meta($product_id,"_extra_field_2",true);
         $extra_field_3 = get_post_meta($product_id,"_extra_field_3",true);
	 
	 
	 $product_types = get_post_meta($product_id,"_product_type",true);
     $product_types_arr = explode(",",$product_types);
 
    ?>
  
   <?php
          $close_img = plugins_url('images/close_btn.png',dirname(__FILE__));
          $plus_img = plugins_url('images/plus.png',dirname(__FILE__));
	  
	  
	   ?>
    <input type ="hidden" name="prod_id" id="prod_id" value="<?php echo $product_id; ?>">
    <input type ="hidden" name="prod_name" id="prod_name" value="<?php echo $product->post_title; ?>">
    <div class="prod_box_wrap">
   
    <div class="prod_box_1 prod_box">
    <div class="sno">1</div>
    <div class="prod_detail">  
       <div class="prod_img_box">
          <div class="prod_img">
            <?php
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'product_thumb' );
            $thumb_url = $thumb['0'];
            if($thumb_url=="")
            {
            $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__)); 
            }
            ?>
            
            <img src="<?php echo $thumb_url; ?>">
            </div>
          <div class="prod_txt">
            <?php echo $product->post_title;
            //print_r($product);
            ?>
          </div>
       </div>
       
       <div class="prod_options">
	       <?php if($product_types!="") { ?>
         <div class="select_type extra_field">
	    <input type="hidden" class="fieldname" value="Product_Type">
	    <div class="label"> <!--When did you get Sky equipment?-->
             Type:
             </div>
            	    
	     <select class="fieldval typeselect">
            
	    <option value=""> Choose Type</option>
            <?php
	    $opt_index = 1;
	    foreach($product_types_arr as $pro_type)
	    {
		$pro_type = trim($pro_type);
		echo '<option class="type'.$opt_index.'" value="'.$pro_type.'">'.$pro_type.'</option>';
		$opt_index++;
	    }
	    
	    ?>
            </select>
         </div>
         <?php }
        ?>
	
	
                 <?php if($show_year=="yes") { ?>
         <div class="select_year extra_field">
	    <input type="hidden" class="fieldname" value="Year">
	    <div class="label"> <!--When did you get Sky equipment?-->
             Year:
             </div>
             <select class="fieldval">
            <option value=""> Choose year</option>
            <?php $year = date("Y");
            $year_loop_till = $year-10;
            ?>
                  
            <?php while($year>$year_loop_till) { ?>
            <option> <?php echo $year; ?></option>
            <?php
            $year--;
            } ?>
            </select>
         </div>
         <?php }
        ?>
          <?php if($show_month=="yes") { ?>
         <div class="select_month extra_field">
	    <input type="hidden" class="fieldname" value="Month">
            <div class="label"> Month: </div>
            <select class="fieldval">
            <option value="">Choose month</option>
			   <option value="01">January</option>
			   <option value="02">February</option>
			   <option value="03">March</option>
			   <option value="04">April</option>
			   <option value="05">May</option>
			   <option value="06">June</option>
			   <option value="07">July</option>
			   <option value="08">August</option>
			   <option value="09">September</option>
			   <option value="10">October</option>
			   <option value="11">November</option>
			   <option value="12">December</option>
                           </select>
         </div>
         <?php }
         
         ?>
         
         <?php
          if($extra_field_1!=""){ ?>
          <div class="extra_field1 extra_field">
		<input type="hidden" class="fieldname" value="<?php echo $extra_field_1; ?>">
            <div class="label"> <?php echo $extra_field_1; ?> </div>
          <input type="text" class="fieldval" name="extra_field_1_<?php echo $product_id; ?>">
          </div>
         <?php } ?>
         
          <?php if($extra_field_2!=""){ ?>
          <div class="extra_field2 extra_field">
		<input type="hidden" class="fieldname" value="<?php echo $extra_field_2; ?>">
          <div class="label"> <?php echo $extra_field_2; ?> </div>
          <input type="text" class="fieldval" name="extra_field_2_<?php echo $product_id; ?>">
          </div>
         <?php } ?>
         
          <?php if($extra_field_3!=""){ ?>
          <div class="extra_field3 extra_field">
		<input type="hidden" class="fieldname" value="<?php echo $extra_field_3; ?>">
             <div class="label"> <?php echo $extra_field_3; ?> </div>
          <input type="text" class="fieldval" name="extra_field_3_<?php echo $product_id; ?>">
          </div>
         <?php } ?>
         
         
       </div>
      
    </div>
    </div>
    
    
    
    <!---------->
   <?php
   if(get_post_meta($product_id,"_allow_device",true)=="yes")
   {
   for($i=2; $i<=9;$i++){
       $active = ($i==2)?"active_box" :"";
      ?>
      <div class="prod_box_<?php echo $i; ?> prod_box  <?php echo $active; ?>">
      <div class="prod_detail_close">
      <img src="<?php echo $close_img; ?>">
      </div>
      <div class="sno"><?php echo $i; ?></div>
      <div class="prod_detail">
        
       <div class="prod_detail_box">
       </div>
      <div class="loading_box">
              <div class="loading_img">
                     <img src="<?php echo $loading_img; ?>">
               </div>
      </div>
      <div class="plus_box">
        <div class="plus_img">
         <?php
      $plus_img = plugins_url('images/plus.png',dirname(__FILE__));
      ?>
      <img src="<?php echo $plus_img; ?>">
      </div>
        <div class="plus_txt">
        <?php if($i==2){ ?>
         Add another device for no extra cost
        <?php } else { ?>
        Add another device for &pound;<?php echo  $extra_device_price; ?> a month.
        <?php } ?>
        </div>
      </div>
      </div>
      </div>
     <?php } //for loop end
   }
     ?>
      </div> 
       <!---------->
      

      
    <div class="device_popup">
      <div class="device_popup_close">
        
         <img src="<?php echo $close_img;?>">
      </div>
   
      <div class="device_popup_inner"> 
         
         
      </div>
      <input type="hidden" id="popup_content_loaded" value="no">
      
      <div class="loading_img">
         
         <img src="<?php echo $loading_img;?>">
         
      </div>
      
    </div>
    
    <div class="checkout_loading_img">
	<img src="<?php echo $loading_img;?>">
   </div>
    
    <!------footer total----->
    <div class="totals" data-appliance-total="1" id="planTotals" style="display: block;">
		     <div class="totalsAmount">
                                  <?php $installment = get_post_meta($product_id,"_product_price",true);
                                  
                                  ?>
                                  <?php if($installment!="")
                                  {
					$lump = $installment * 12;
					$lump = round($lump,2);
					$installment = round($installment,2);
					
                                  ?>
					<p>                                        
						<strong>&pound;<span class="installment"><?php echo $installment; ?></span></strong> a month for <span class="term">12</span> months or a one-off payment of &pound;<span class="lump"><?php echo $lump; ?></span>
                                                <input type="hidden" id="installment" value="<?php echo $installment; ?>">
                                                <input type="hidden" id="add_device_price" value="<?php echo $extra_device_price; ?>">
					</p>
					<div class="cta">
						<button class="getQuote">
						Get quote
						</button>
					</div>
                                        <?php }else {
                                          echo "<p>Price not set.</p>";
                                        }
                                          ?>
				</div>
			</div>
    
    
    
    <?php  
     
        return ob_get_clean();

}
    
    add_shortcode( 'show_product', 'show_product_func' );
    
    
    //ajax function
    
    function device_popup_content_func()
    {
        ob_start();
        $product_id = $_POST['product_id'];
        $enabled_device_arr = get_post_meta( $product_id,"_enabled_devices",true);
        $device_img = plugins_url('images/device.png',dirname(__FILE__));
        ?>
       <div class="device_popup_txt">Which device would you like to include in your cover?</div>
            
       <div class="device_list">
         <?php
       	 $device_id_arr = array();
      $args = array (
	"post_type" => "custom_device",
	"posts_per_page" => -1,
	"post_parent" => 0
    );
    	
	query_posts($args);
	while(have_posts()){
        the_post();
        $device_id = get_the_ID();
       
        if( $enabled_device_arr[$device_id]=="on")
        {
       ?>
            <div class="device" id="device_<?php echo $device_id; ?>">
                <div class="device_img">
                   <?php
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($device_id), 'product_thumb' );
            $thumb_url = $thumb['0'];
            if($thumb_url=="")
            {
            $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__)); 
            }
            ?>
            
            <img src="<?php echo $thumb_url; ?>">
                </div>
                <div class="device_title">
                    <?php the_title(); ?>
                </div>
            </div>
              <?php
            
              array_push($device_id_arr,$device_id);
             
              
        }
              }
       wp_reset_query();
   
       ?>   
       </div>
      
       
       <?php  $device_type_img = plugins_url('images/device_type.png',dirname(__FILE__)); ?>
      
      
      <?php foreach($device_id_arr as $data_id) {
            ?>
       <div class="device_type_list device_<?php echo $data_id; ?>">
        <div class="device_type_popup_close">
         <?php
          $close_img = plugins_url('images/close_btn.png',dirname(__FILE__));
         ?>
         <img src="<?php echo $close_img;?>">
         </div>
       
       <?php
        $args = array (
	"post_type" => "custom_device",
	"posts_per_page" => -1,
	"post_parent" => $data_id
    );
       query_posts($args);
	while(have_posts()):
	the_post();
	$device_id = get_the_ID();
        ?>
        <div class="device_type" id="device_type_<?php echo $device_id; ?>">
                <div class="device_img">
               <?php
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($device_id), 'product_thumb' );
            $thumb_url = $thumb['0'];
            if($thumb_url=="")
            {
            $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__)); 
            }
            ?>
            
            <img src="<?php echo $thumb_url; ?>">
                </div>
                <div class="device_title">
                    <?php the_title(); ?>
                </div>
                <input type="hidden" class="device_id" value="<?php echo $device_id; ?>">
        </div>
       <?php endwhile;
       wp_reset_query();
       ?>
       
       
        
       </div>
       <?php } ?>
        
       
       <?php
        $response = ob_get_clean();
        echo $response;
        die;        
    }
add_action( 'wp_ajax_device_popup_content', 'device_popup_content_func' );
add_action( 'wp_ajax_nopriv_device_popup_content', 'device_popup_content_func' );

add_action( 'wp_ajax_prod_detail_box_content', 'prod_detail_box_content_func' );
add_action( 'wp_ajax_nopriv_prod_detail_box_content', 'prod_detail_box_content_func' );

function prod_detail_box_content_func()
{
	 $device_id = $_POST['device_id'];
	 $device = get_post( $device_id );
         $show_year = get_post_meta($device_id,"_show_year",true);
	 $show_month = get_post_meta($device_id,"_show_month",true);
         $extra_field_1 = get_post_meta($device_id,"_extra_field_1",true);
         $extra_field_2 = get_post_meta($device_id,"_extra_field_2",true);
         $extra_field_3 = get_post_meta($device_id,"_extra_field_3",true);
   ?>
  
   <div class="prod_img_box">
          <div class="prod_img">
            <?php
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($device_id), 'product_thumb' );
            $thumb_url = $thumb['0'];
            if($thumb_url=="")
            {
            $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__)); 
            }
            ?>
            
            <img src="<?php echo $thumb_url; ?>">
            </div>
           <div class="prod_txt">
            <?php echo $device->post_title;
          
            ?>
          </div>
          
       </div>
   
    <div class="prod_options">
	<div class="extra_field">
		<input type="hidden" class="fieldname" value="Device_id">
		<input type="hidden" class="fieldval" value="<?php echo $device_id; ?>">
	</div>
	<div class="extra_field">
		<input type="hidden" class="fieldname" value="Device_name">
		<input type="hidden" class="fieldval" value="<?php echo $device->post_title; ?>">
	</div>
	
                 <?php if($show_year=="yes") { ?>
         <div class="select_year extra_field">
		<input type="hidden" class="fieldname" value="Year">
             <div class="label"> <!--When did you get Sky equipment?-->
             Year:
             </div>
             <select class="fieldval">
            <option> Choose year</option>
            <?php $year = date("Y");
            $year_loop_till = $year-10;
            ?>
                  
            <?php while($year>$year_loop_till) { ?>
            <option> <?php echo $year; ?></option>
            <?php
            $year--;
            } ?>
            </select>
         </div>
         <?php }
        ?>
          <?php if($show_month=="yes") { ?>
         <div class="select_month extra_field">
		<input type="hidden" class="fieldname" value="Month">
            <div class="label"> Month: </div>
            <select class="fieldval">
            <option value="">Choose month</option>
			   <option value="01">January</option>
			   <option value="02">February</option>
			   <option value="03">March</option>
			   <option value="04">April</option>
			   <option value="05">May</option>
			   <option value="06">June</option>
			   <option value="07">July</option>
			   <option value="08">August</option>
			   <option value="09">September</option>
			   <option value="10">October</option>
			   <option value="11">November</option>
			   <option value="12">December</option>
                           </select>
         </div>
         <?php }
         
         ?>
         
         <?php
          if($extra_field_1!=""){ ?>
          <div class="extra_field1 extra_field">
          <input type="hidden" class="fieldname" value="<?php echo $extra_field_1; ?>">
	    <div class="label"> <?php echo $extra_field_1; ?> </div>
          <input type="text" class="fieldval" name="extra_field_1_<?php echo $device_id; ?>">
	  
          </div>
         <?php } ?>
         
          <?php if($extra_field_2!=""){ ?>
          <div class="extra_field2 extra_field">
		 <input type="hidden" class="fieldname" value="<?php echo $extra_field_2; ?>">
          <div class="label"> <?php echo $extra_field_2; ?> </div>
          <input type="text" class="fieldval" name="extra_field_2_<?php echo $device_id; ?>">
          </div>
         <?php } ?>
         
          <?php if($extra_field_3!=""){ ?>
          <div class="extra_field3 extra_field">
		 <input type="hidden" class="fieldname" value="<?php echo $extra_field_3; ?>">
             <div class="label"> <?php echo $extra_field_3; ?> </div>
          <input type="text" class="fieldval" name="extra_field_3_<?php echo $device_id; ?>">
          </div>
         <?php } ?>
                  
       </div>
   
     <?php   die;
}

?>
