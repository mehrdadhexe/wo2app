jQuery(document).ready(function(){
	jQuery("#img_load_del_sl").removeClass("hide");
	jQuery.ajax({
	    url: ajaxurl,
	    data: {
	        action: "load_list_slider_hami",
	    },
	    success:function(data) {
			data = data.substring(0,data.length -1);
			jQuery('#contain_rows_slider_hami').html(data);
			jQuery("#img_load_del_sl").addClass("hide");
	    }
    }); 
	jQuery('#select_action_slider').change(
    function(){
    	
        if (this.value == '1') {
            jQuery("#get_all_post_for_type").addClass("hide");
            jQuery("#get_all_cat_for_type").addClass("hide");
            jQuery("#get_link_for_type").addClass("hide");
            jQuery("#get_all_post_for_type").removeClass("hide");
        }else if (this.value == '2') {
        	jQuery("#get_all_post_for_type").addClass("hide");
            jQuery("#get_all_cat_for_type").addClass("hide");
            jQuery("#get_link_for_type").addClass("hide");
            jQuery("#get_all_cat_for_type").removeClass("hide");
        }else if (this.value == '3') {
        	jQuery("#get_all_post_for_type").addClass("hide");
            jQuery("#get_all_cat_for_type").addClass("hide");
            jQuery("#get_link_for_type").addClass("hide");
            jQuery("#get_link_for_type").removeClass("hide");
        }
    });
});
function add_slider_hami_shop(){
	jQuery('#txt_title_hami_slider_shop').next().addClass('hide');
	jQuery('#txt_url_slider_hami').next().addClass('hide');
	jQuery('#select_type_slider_hami').next().addClass('hide');
	jQuery('#value_cat_slider').next().addClass('hide');
	jQuery('#value_post_slider').next().addClass('hide');
	title = jQuery('#txt_title_hami_slider_shop').val();
	pic = jQuery('#txt_url_slider_hami').val();
	type = jQuery("#select_action_slider").val();
	value = "";
	ic = 0 ;
	flag = 0;
	if(type == 2){
		/*var foo = []; 
		jQuery('#value_cat_slider :selected').each(function(i, selected){ 
		  foo[i] = jQuery(selected).text(); 
		  ic++;
		});*/
		value = jQuery('#value_cat_slider').val();
	}else if (type == 1) {
		/*var foo = []; 
		jQuery('#value_post_slider :selected').each(function(i, selected){ 
		  foo[i] = jQuery(selected).text(); 
		  ic++;
		});*/
		value = jQuery('#value_post_slider').val();
	}else if (type == 3) {
		/*var foo = []; 
		jQuery('#value_post_slider :selected').each(function(i, selected){ 
		  foo[i] = jQuery(selected).text(); 
		  ic++;
		});*/
		value = jQuery('#value_link_slider').val();
	}

	if(title.length <= 0){
		flag = 1;
		jQuery('#txt_title_hami_slider_shop').next().removeClass('hide');
	}
	if (pic.length <= 0) {
		flag = 2;
		jQuery('#txt_url_slider_hami').next().removeClass('hide');
	}
	if (type != 1 && type != 2 && type != 3) {
		flag = 3;
		jQuery('#select_type_slider_hami').next().removeClass('hide');
	}
	if (value == null || value == "" || value == 0){
		flag = 4;
		if(type == 1){
			jQuery('#value_cat_slider').next().html('لطفا دسته خود را انتخاب کنید.');
			jQuery('#value_cat_slider').next().removeClass('hide');
		}else if (type == 2) {
			jQuery('#value_post_slider').next().html('لطفا پست خود را انتخاب کنید.');
			jQuery('#value_post_slider').next().removeClass('hide');
		}
	}


	if(flag == 0){
		jQuery("#img_load100").removeClass("hide");
		jQuery.ajax({
		    url: ajaxurl,
		    data: {
		        action: "add_slider_hami_shop",
		        security: jQuery( '#woo2app-ajax-nonce' ).val(),
				"title": title ,"pic" : pic , "type" : type , "value" : value
		    },
		    success:function(data) {
		    	jQuery("#img_load100").addClass("hide");
			    jQuery("#img_load_del_sl").removeClass("hide");
				if(data == 10){
					jQuery.ajax({
					    url: ajaxurl,
					    data: {
					        action: "load_list_slider_hami",
					    },
					    success:function(data) {
							data = data.substring(0,data.length -1);
							jQuery('#contain_rows_slider_hami').html(data);
								jQuery("#img_load_del_sl").addClass("hide");
					    }
				    }); 
					jQuery('#alert_all_slider_add').removeClass('alert-danger');
					jQuery('#alert_all_slider_add').removeClass('alert-success');
					jQuery('#alert_all_slider_add').html('اسلایدر شما با موفقیت ثبت شد.');
					jQuery('#alert_all_slider_add').addClass('alert-success');
					jQuery('#alert_all_slider_add').removeClass('hide');
					setTimeout(function(){ jQuery('#alert_all_slider_add').addClass('hide'); }, 3000);
				}else{
					jQuery('#alert_all_slider_add').removeClass('alert-danger');
					jQuery('#alert_all_slider_add').removeClass('alert-success');
					jQuery('#alert_all_slider_add').html('خطا در سرور لطفا دوباره امتحان کنید.');
					jQuery('#alert_all_slider_add').addClass('alert-danger');
					jQuery('#alert_all_slider_add').removeClass('hide');
					setTimeout(function(){ jQuery('#alert_all_slider_add').addClass('hide'); }, 3000);
				}
		    }
	    }); 
	}
}
function delete_slider_hami(id){
	jQuery("#img_load_del_sl").removeClass("hide");
	jQuery.ajax({
	    url: ajaxurl,
	    data: {
	        action: "delete_item_slider_hami",
	        "id" : id
	    },
	    success:function(data) {
	jQuery("#img_load_del_sl").addClass("hide");
			data = data.substring(0,data.length -1);
			if(data == 1){
				jQuery('#row_slider_' + id ).remove();
			}
	    }
    });
}