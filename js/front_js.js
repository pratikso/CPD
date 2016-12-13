jQuery(document).ready(function(){
    
    jQuery(".prod_detail .plus_box").click(function(){
        var is_popup_contnet_loaded =    jQuery("#popup_content_loaded").val();
        var product_id = jQuery("#prod_id").val();
        jQuery(".device_popup").fadeIn(300);
        
        if (is_popup_contnet_loaded=="no") {           
        
         var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'device_popup_content',
            product_id: product_id
               
           };
	     jQuery.ajax({
             method: "POST",
             url: ajax_url,
             data: data,
             async:true
              }).done(function( response ) {
                jQuery(".device_popup_inner").html(response);
                jQuery("#popup_content_loaded").val("yes");
                jQuery(".device_popup .loading_img").hide();
            });
        }
        else
        {
           // alert("already loaded");
        }
        
    });
    jQuery( document ).on( 'click', '.device', function() { 
       var div_id = jQuery(this).attr("id");
      jQuery(".device_type_list").hide();
      jQuery("."+div_id).fadeIn(400);
    });
    
     jQuery( document ).on( 'click', '.device_type', function() { 
     var device_id =  jQuery(this).find(".device_id").val();
  
    jQuery(".device_type_list").hide();
    jQuery(".device_popup").hide();
    jQuery(".active_box .prod_detail .loading_box").hide();
     
     var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'prod_detail_box_content',
            device_id: device_id               
           };
	     jQuery.ajax({
             method: "POST",
             url: ajax_url,
             data: data,
             async:true
              }).done(function( response ) {
    jQuery(".active_box .prod_detail .loading_box").hide();
    jQuery(".active_box .prod_detail .prod_detail_box").html(response).hide().fadeIn(400);
     var active_box_top = jQuery(".active_box").offset();
     var active_box_index = jQuery(".active_box").index();
     var next_box_index = active_box_index+1;
     var box_count = jQuery(".prod_box").length;
     jQuery("html,body").animate({scrollTop: active_box_top.top-100},500);
     jQuery(".active_box").addClass("filled_box");
     jQuery(".active_box").removeClass("active_box");
     if (next_box_index<box_count) {
        jQuery(".prod_box").eq(next_box_index).addClass("active_box");
     }
     
     var installment = jQuery("#installment").val();
     installment = parseFloat(installment);
     var add_device_price = jQuery("#add_device_price").val();
     add_device_price = parseFloat(add_device_price);
     var device_count = jQuery(".filled_box").length;
     
     var new_installment;
     var lump;
     if (device_count>1) {
     var add_device_total_price = (device_count -1)*add_device_price;
      new_installment = installment + add_device_total_price ;
      new_installment = new_installment.toFixed(2);
      jQuery("#planTotals .installment").text(new_installment);
      lump = new_installment*12;
      lump = lump.toFixed(2);
       jQuery("#planTotals .lump").text(lump);
     }
            });
     
    });
          
     jQuery( document ).on( 'click', '.prod_detail_close', function() {
     jQuery(this).parent().find(".prod_detail_box").html("");
     jQuery(this).parent().removeClass("filled_box");
     jQuery(".prod_box").removeClass("active_box");
     var active_box_index = jQuery(this).parent().index();
   
    var box_count = jQuery(".prod_box").length;
   
    var i,temp_html;
   
    for (i=active_box_index;i<box_count;i++)
    {
               
        if(jQuery(".prod_box").eq(i+1).hasClass("filled_box")) {
            temp_html = jQuery(".prod_box").eq(i+1).find(".prod_detail_box").html();
            jQuery(".prod_box").eq(i).find(".prod_detail_box").html(temp_html);
            jQuery(".prod_box").eq(i).addClass("filled_box");
            jQuery(".prod_box").eq(i+1).removeClass("filled_box");
        }
        else
        {
           jQuery(".prod_box").eq(i).addClass("active_box");
            jQuery(".prod_box").eq(i).removeClass("filled_box");
            jQuery(".prod_box").eq(i).find(".prod_detail_box").html("");
            break;
        }
            
    }
    var installment = jQuery("#installment").val();
     installment = parseFloat(installment);
     var add_device_price = jQuery("#add_device_price").val();
     add_device_price = parseFloat(add_device_price);
     var device_count = jQuery(".filled_box").length;
     
     var new_installment, lump;
     if (device_count>1) {
     var add_device_total_price = (device_count -1)*add_device_price;
      new_installment = installment + add_device_total_price ;
      new_installment = new_installment.toFixed(2);
      jQuery("#planTotals .installment").text(new_installment);
      lump = new_installment*12;
      lump = lump.toFixed(2);
       jQuery("#planTotals .lump").text(lump);
     }
    
    
    var active_box_top = jQuery(".active_box").offset();
     jQuery("html,body").animate({scrollTop: active_box_top.top},500);  
       
}); 
            
     jQuery(".device_popup_close").click(function(){
     jQuery(".device_type_list").hide();
     jQuery(".device_popup").fadeOut(400);
        
    });
    jQuery( document ).on( 'click', '.device_type_popup_close', function() { 
        jQuery(".device_type_list").fadeOut(400);
        
    });
    /////----------------------------------------------
        jQuery( document ).on( 'click', '#planTotals .getQuote', function() {
           
        jQuery(".checkout_loading_img").fadeIn(400);
        var installment = jQuery(" #planTotals #installment").val();
        var product_id = jQuery("#prod_id").val();
        var product_name = jQuery("#prod_name").val();
        var prod_extra_field_count = jQuery(".prod_box_1 .extra_field").length;
        var i,fieldname,fieldval;
                      
        var fieldnameArray = [];
        var fieldvalArray = [];
       if (prod_extra_field_count>0) {      
       
        for(i=0;i<prod_extra_field_count;i++)
        {
           fieldname= jQuery(".prod_box_1 .prod_options .extra_field").eq(i).find(".fieldname").val();           
           fieldval = jQuery(".prod_box_1 .prod_options .extra_field").eq(i).find(".fieldval").val();           
          // fields["'"+fieldname+"'"] = fieldval;
          
          if (fieldname!='' && fieldval!='') {
            fieldnameArray.push(fieldname);
            fieldvalArray.push(fieldval);
          }           
        }
       } 
        Array.prototype.associate = function (keys) {
            var result = {};
            this.forEach(function (el, i) {
            result[keys[i]] = el;
            });
        
            return result;
        };
        
        var product_info = fieldvalArray.associate(fieldnameArray);
         product_info = JSON.stringify(product_info);
       //  alert(product_info);
         var device_count = jQuery(".filled_box").length;
         var j,k;
         var device_info=[];
         var fieldnameArray2 = [];
        var fieldvalArray2 = [];
         if (device_count>0) {            
            for(j=0;j<device_count;j++)
            {
             device_extra_field_count = jQuery(".filled_box").eq(j).find('.extra_field').length;
            
             for(k=0;k<device_extra_field_count;k++)
             {
               fieldname =  jQuery(".filled_box").eq(j).find('.extra_field').eq(k).find(".fieldname").val();
               fieldval = jQuery(".filled_box").eq(j).find('.extra_field').eq(k).find(".fieldval").val();  
                                     
                    if (fieldname!='' && fieldval!='')
                    {
                    fieldnameArray2.push(fieldname);
                    fieldvalArray2.push(fieldval);
                    }             
             }
          device_info[j] = fieldvalArray2.associate(fieldnameArray2);
       
            }       
         
         }
        device_info = JSON.stringify(device_info);
       // alert(device_info);
       //set cookie
     //   var user_cart_id;
     //var user_cart_id = getCookie("user_cart");
     //if (user_cart_id=="") {
       setCookie("user_cart",makeid(),1);
    //    }
    
       
        var user_cart_id = getCookie("user_cart");
        
           var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'checkout_data',
            product_id: product_id,
            product_name:product_name,
            installment:installment,
            product_info: product_info,
            device_info: device_info,
            user_cart_id: user_cart_id
           };
	     jQuery.ajax({
             method: "POST",
             url: ajax_url,
             data: data,
             async:true
              }).done(function( response ) {
            jQuery(".checkout_loading_img").hide();
           // alert(response);
          //alert(front_js_vars.checkout_page_url);
            window.location.replace(front_js_vars.checkout_page_url);
             });
               
    });
    
    function makeid()
    {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 6; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
    }
    
    function setCookie(cname, cvalue, exdays)
    {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    var path = "path=/";
    var domain = front_js_vars.domain_name;
    document.cookie = cname + "=" + cvalue + "; " + expires+"; "+path+"; "+domain;
    }
    
    function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

        
        jQuery('#custdetails').validate({
            rules :           
            {
                address_confirm_email: {
                equalTo : "#address_email"
                },
                address_contact_no:{
                    number : true,
                    required: true,
                    maxlength: 12

                },
                address_home_contact_no:{
                    number : true,
                    required: true,
                    maxlength: 12

                },
              
            }
        });
        
        jQuery(".pro_type").click(function(){
        var box_index = jQuery(this).index();
        box_index = box_index + 1;
        jQuery(".typeselect .type"+box_index).attr('selected',"selected");
        // var pro_type = jQuery(this).attr("id");
        // jQuery(".typeselect ."+pro_type).attr('selected',"selected");
        
        });
    
});