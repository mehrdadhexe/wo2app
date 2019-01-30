jQuery(document).ready(function(){

    jQuery("#select_notif_hami").change(function(){
        var v = this.value;
        if(v == 1){
            jQuery("#product_list_notif").addClass("hide");
            jQuery("#category_list_notif").addClass("hide");
            jQuery("#link_notif_hami").removeClass("hide");
            jQuery("#post_category_list_notif").addClass("hide");
            jQuery("#post_list_notif").addClass("hide");
        }else if (v == 2) {
            jQuery("#category_list_notif").addClass("hide");
            jQuery("#link_notif_hami").addClass("hide");
            jQuery("#product_list_notif").removeClass("hide");
            jQuery("#post_category_list_notif").addClass("hide");
            jQuery("#post_list_notif").addClass("hide");
        }else if (v == 3) {
            jQuery("#product_list_notif").addClass("hide");
            jQuery("#link_notif_hami").addClass("hide");
            jQuery("#category_list_notif").removeClass("hide");
            jQuery("#post_category_list_notif").addClass("hide");
            jQuery("#post_list_notif").addClass("hide");
        }
        else if (v == 4) {
            jQuery("#product_list_notif").addClass("hide");
            jQuery("#link_notif_hami").addClass("hide");
            jQuery("#post_list_notif").removeClass("hide");
            jQuery("#category_list_notif").addClass("hide");
            jQuery("#post_category_list_notif").addClass("hide");
        }
        else if (v == 5) {
            jQuery("#product_list_notif").addClass("hide");
            jQuery("#category_list_notif").addClass("hide");
            jQuery("#link_notif_hami").addClass("hide");
            jQuery("#post_list_notif").addClass("hide");
            jQuery("#post_category_list_notif").removeClass("hide");
        }
    });

    // jQuery('#search_product_notif').keyup(function(){
    //     var  v = jQuery(this).val();
    //     if(v.length >= 3){
    //         //jQuery("#loading_product2").removeClass("hide");
    //         jQuery.ajax({
    //             url: ajaxurl,
    //             data: {
    //                 action: "load_all_product",
    //                 search_product : v
    //             },
    //             success:function(data) {
    //                 //jQuery("#loading_product2").addClass("hide");
    //                 jQuery("#value_post_notif").html(data);
    //             }
    //         });
    //     }
    // });

});