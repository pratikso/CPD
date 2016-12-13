jQuery(document).ready(function(){
    jQuery('.order_table .select_all_order').change(function() {
	
   if(jQuery(this).is(":checked")) {
	
    jQuery('.order_chk').attr('checked', true);
      }
     else{
	jQuery('.order_chk').attr('checked', false);
     }
    });
});