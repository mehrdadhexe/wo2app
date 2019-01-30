jQuery(document).ready(function(){
	jQuery("#btn_form_color").click(function(){
		//**************general****************************************
				
				var COLOR_GENERAL_ACTIONBAR_BG = jQuery("#COLOR_GENERAL_ACTIONBAR_BG").val();
				
				var COLOR_GENERAL_STATUSBAR_BG = jQuery("#COLOR_GENERAL_STATUSBAR_BG").val();
				
				var COLOR_GENERAL_MAIN_BG = jQuery("#COLOR_GENERAL_MAIN_BG").val();
        		var COLOR_GENERAL_ACTIONBAR_TXT = jQuery("#COLOR_GENERAL_ACTIONBAR_TXT").val();
				//************ pishnehad vizhe *******************************************
				var COLOR_VIZHE_TXT = jQuery("#COLOR_VIZHE_TXT").val();

				var COLOR_VIZHE_SELLOL_ZAMAN = jQuery("#COLOR_VIZHE_SELLOL_ZAMAN").val();

				var COLOR_VIZHE_ZAMAN_TXT = jQuery("#COLOR_VIZHE_ZAMAN_TXT").val();
				//************primary*******************************************
				
				var COLOR_CATLISTBUTTON_BG = jQuery("#COLOR_CATLISTBUTTON_BG").val();
				
				var COLOR_CATLISTBUTTON_TEXT = jQuery("#COLOR_CATLISTBUTTON_TEXT").val();
				
				var COLOR_MOREBUTTON_BG = jQuery("#COLOR_MOREBUTTON_BG").val();
				
				var COLOR_MOREBUTTON_TEXT = jQuery("#COLOR_MOREBUTTON_TEXT").val();
				
				var COLOR_PRODUCTCELL_BG = jQuery("#COLOR_PRODUCTCELL_BG").val();
				
				var COLOR_PRODUCTCELL_TITLE_TEXT = jQuery("#COLOR_PRODUCTCELL_TITLE_TEXT").val();
				
				var COLOR_PRODUCTCELL_PRICE_TEXT = jQuery("#COLOR_PRODUCTCELL_PRICE_TEXT").val();
				
				var COLOR_PRODUCTCELL_OFFPRICE_TEXT = jQuery("#COLOR_PRODUCTCELL_OFFPRICE_TEXT").val();
				
				var COLOR_MENU_BG = jQuery("#COLOR_MENU_BG").val();
				
				var COLOR_MENU_TEXT = jQuery("#COLOR_MENU_TEXT").val();
				
				var COLOR_LIST_PRICE_DEVIDER_BG = jQuery("#COLOR_LIST_PRICE_DEVIDER_BG").val();
				
				var COLOR_MENU_FLOT_BG = jQuery("#COLOR_MENU_FLOT_BG").val();
				
				var COLOR_LIST_TITLE_TEXT = jQuery("#COLOR_LIST_TITLE_TEXT").val();
				//***********bascket***********************************************
				
				var COLOR_BASKET_LIST_BG = jQuery("#COLOR_BASKET_LIST_BG").val();
				
				var COLOR_BASKET_P_TITLE_TEXT = jQuery("#COLOR_BASKET_P_TITLE_TEXT").val();
				
				var COLOR_BASKET_PRICEVAHED_TEXT = jQuery("#COLOR_BASKET_PRICEVAHED_TEXT").val();
				
				var COLOR_BASKET_PRICEVAHED_BG = jQuery("#COLOR_BASKET_PRICEVAHED_BG").val();
				
				var COLOR_BASKET_PRICEKOL_TEXT = jQuery("#COLOR_BASKET_PRICEKOL_TEXT").val();
				
				var COLOR_BASKET_PRICEKOL_BG = jQuery("#COLOR_BASKET_PRICEKOL_BG").val();
				
				var COLOR_BASKET_DEL_TEXT = jQuery("#COLOR_BASKET_DEL_TEXT").val();
				
				var COLOR_BASKET_DEL_BG = jQuery("#COLOR_BASKET_DEL_BG").val();
				
				var COLOR_BASKET_TOTAL_TEXT = jQuery("#COLOR_BASKET_TOTAL_TEXT").val();
				
				var COLOR_BASKET_TOTAL_BG = jQuery("#COLOR_BASKET_TOTAL_BG").val();
				
				var COLOR_COMPLETEORDER_BG = jQuery("#COLOR_COMPLETEORDER_BG").val();
				
				var COLOR_COMPLETEORDER_TEXT = jQuery("#COLOR_COMPLETEORDER_TEXT").val();
				
				var COLOR_BASKET_COUNT_TEXT = jQuery("#COLOR_BASKET_COUNT_TEXT").val();
				//***********product**************************************************
				
				var COLOR_ADDCARD_BG = jQuery("#COLOR_ADDCARD_BG").val();
				
				var COLOR_ADDCARD_TEXT = jQuery("#COLOR_ADDCARD_TEXT").val();
				
				var COLOR_PRODUCTGALLERY_BG = jQuery("#COLOR_PRODUCTGALLERY_BG").val();
				
				var COLOR_PRODUC_TTITLE_TEXT = jQuery("#COLOR_PRODUC_TTITLE_TEXT").val();
				
				var COLOR_PRODUCT_PRICE_TEXT = jQuery("#COLOR_PRODUCT_PRICE_TEXT").val();
				
				var COLOR_PRODUCT_PRICEOFF_TEXT = jQuery("#COLOR_PRODUCT_PRICEOFF_TEXT").val();
				//**********desc order*********************************************
				
				var COLOR_DETAILO_PAYBUTTON_BG = jQuery("#COLOR_DETAILO_PAYBUTTON_BG").val();
				
				var COLOR_DETAILO_PAYBUTTON_TEXT = jQuery("#COLOR_DETAILO_PAYBUTTON_TEXT").val();
				
				var COLOR_DETAILO_PAYDES_TEXT = jQuery("#COLOR_DETAILO_PAYDES_TEXT").val();
				
				var COLOR_DETAILO_PAYDES_BG = jQuery("#COLOR_DETAILO_PAYDES_BG").val();
				
				var COLOR_DETAILO_PAYITEM_BG = jQuery("#COLOR_DETAILO_PAYITEM_BG").val();
				
				var COLOR_DETAILO_PAYITEM_TEXT = jQuery("#COLOR_DETAILO_PAYITEM_TEXT").val();
				//*********submit order************************************
				
				var COLOR_SUBTMIORDER_BG = jQuery("#COLOR_SUBTMIORDER_BG").val();
				
				var COLOR_SUBMITORDER_TEXT = jQuery("#COLOR_SUBMITORDER_TEXT").val();
				
				var COLOR_SUBMITORDER_ACCONT_TEXT = jQuery("#COLOR_SUBMITORDER_ACCONT_TEXT").val();
				
				var COLOR_SUBMITORDER_TOTAL_TEXT = jQuery("#COLOR_SUBMITORDER_TOTAL_TEXT").val();
				//********register*****************************************
				
				var COLOR_REGISTERBUTTON_BG = jQuery("#COLOR_REGISTERBUTTON_BG").val();
				
				var COLOR_REGISTERBUTTON_TEXT = jQuery("#COLOR_REGISTERBUTTON_TEXT").val();
				//*******edit user**************************************
				
				var COLOR_EDITUSERBUTTON_BG = jQuery("#COLOR_EDITUSERBUTTON_BG").val();
				
				var COLOR_EDITUSERBUTTON_TEXT = jQuery("#COLOR_EDITUSERBUTTON_TEXT").val();
				//***********login***************************************
				
				var COLOR_LOGINBUTTON_BG = jQuery("#COLOR_LOGINBUTTON_BG").val();
				
				var COLOR_LOGINBUTTON_TEXT = jQuery("#COLOR_LOGINBUTTON_TEXT").val();
				
				var COLOR_LOGIN_FORGETPPASS_TEXT = jQuery("#COLOR_LOGIN_FORGETPPASS_TEXT").val();
				
				var COLOR_LOGIN_REGISTER_TEXT = jQuery("#COLOR_LOGIN_REGISTER_TEXT").val();
				//*************change pass******************************
				
				var COLOR_CHANGEPASS_BG = jQuery("#COLOR_CHANGEPASS_BG").val();
				
				var COLOR_CHANGEPASS_TEXT = jQuery("#COLOR_CHANGEPASS_TEXT").val();
				//***********forgot pass*****************************
				
				var COLOR_FORGETBUTTON_BG = jQuery("#COLOR_FORGETBUTTON_BG").val();
				
				var COLOR_FORGETBUTTON_TEXT = jQuery("#COLOR_FORGETBUTTON_TEXT").val();
		/*var COLOR_GENERAL_ACTIONBAR_BG = jQuery("#COLOR_GENERAL_ACTIONBAR_BG").val();
		var COLOR_GENERAL_STATUSBAR_BG = jQuery("#COLOR_GENERAL_STATUSBAR_BG").val();
		var COLOR_GENERAL_MAIN_BG = jQuery("#COLOR_GENERAL_MAIN_BG").val();
		var COLOR_CATLISTBUTTON_BG = jQuery("#COLOR_CATLISTBUTTON_BG").val();
		var COLOR_CATLISTBUTTON_TEXT = jQuery("#COLOR_CATLISTBUTTON_TEXT").val();
		var COLOR_MOREBUTTON_BG = jQuery("#COLOR_MOREBUTTON_BG").val();
		var COLOR_MOREBUTTON_TEXT = jQuery("#COLOR_MOREBUTTON_TEXT").val();
		var COLOR_PRODUCTCELL_BG = jQuery("#COLOR_PRODUCTCELL_BG").val();
		var COLOR_PRODUCTCELL_TITLE_TEXT = jQuery("#COLOR_PRODUCTCELL_TITLE_TEXT").val();
		var COLOR_PRODUCTCELL_PRICE_TEXT = jQuery("#COLOR_PRODUCTCELL_PRICE_TEXT").val();
		var COLOR_MENU_BG = jQuery("#COLOR_MENU_BG").val();
		var COLOR_MENU_TEXT = jQuery("#COLOR_MENU_TEXT").val();
		var COLOR_PRODUCTGALLERY_BG = jQuery("#COLOR_PRODUCTGALLERY_BG").val();
		var COLOR_ADDCARD_BG = jQuery("#COLOR_ADDCARD_BG").val();
		var COLOR_ADDCARD_TEXT = jQuery("#COLOR_ADDCARD_TEXT").val();
		var COLOR_COMPLETEORDER_BG = jQuery("#COLOR_COMPLETEORDER_BG").val();
		var COLOR_COMPLETEORDER_TEXT = jQuery("#COLOR_COMPLETEORDER_TEXT").val();*/
		jQuery.ajax({
		    url: ajaxurl,
		    data: {
		        action: "set_color_woo2app",
		       	COLOR_GENERAL_ACTIONBAR_BG : jQuery("#COLOR_GENERAL_ACTIONBAR_BG").val(),
				COLOR_GENERAL_STATUSBAR_BG : jQuery("#COLOR_GENERAL_STATUSBAR_BG").val(),
				COLOR_GENERAL_MAIN_BG : jQuery("#COLOR_GENERAL_MAIN_BG").val(),
                COLOR_GENERAL_ACTIONBAR_TXT : jQuery("#COLOR_GENERAL_ACTIONBAR_TXT").val(),
				//************ pishnehad vizhe*******************************************
				COLOR_VIZHE_TXT : jQuery("#COLOR_VIZHE_TXT").val(),
				COLOR_VIZHE_SELLOL_ZAMAN : jQuery("#COLOR_VIZHE_SELLOL_ZAMAN").val(),
				COLOR_VIZHE_ZAMAN_TXT : jQuery("#COLOR_VIZHE_ZAMAN_TXT").val(),

				//************primary*******************************************
				COLOR_CATLISTBUTTON_BG : jQuery("#COLOR_CATLISTBUTTON_BG").val(),
				COLOR_CATLISTBUTTON_TEXT : jQuery("#COLOR_CATLISTBUTTON_TEXT").val(),
				COLOR_MOREBUTTON_BG : jQuery("#COLOR_MOREBUTTON_BG").val(),
				COLOR_MOREBUTTON_TEXT : jQuery("#COLOR_MOREBUTTON_TEXT").val(),
				COLOR_PRODUCTCELL_BG : jQuery("#COLOR_PRODUCTCELL_BG").val(),
				COLOR_PRODUCTCELL_TITLE_TEXT : jQuery("#COLOR_PRODUCTCELL_TITLE_TEXT").val(),
				COLOR_PRODUCTCELL_PRICE_TEXT : jQuery("#COLOR_PRODUCTCELL_PRICE_TEXT").val(),
				COLOR_PRODUCTCELL_OFFPRICE_TEXT : jQuery("#COLOR_PRODUCTCELL_OFFPRICE_TEXT").val(),
				COLOR_MENU_BG : jQuery("#COLOR_MENU_BG").val(),
				COLOR_MENU_TEXT : jQuery("#COLOR_MENU_TEXT").val(),
				COLOR_LIST_PRICE_DEVIDER_BG : jQuery("#COLOR_LIST_PRICE_DEVIDER_BG").val(),
				COLOR_MENU_FLOT_BG : jQuery("#COLOR_MENU_FLOT_BG").val(),
				COLOR_LIST_TITLE_TEXT : jQuery("#COLOR_LIST_TITLE_TEXT").val(),
				//***********bascket***********************************************
				COLOR_BASKET_LIST_BG : jQuery("#COLOR_BASKET_LIST_BG").val(),
				COLOR_BASKET_P_TITLE_TEXT : jQuery("#COLOR_BASKET_P_TITLE_TEXT").val(),
				COLOR_BASKET_PRICEVAHED_TEXT : jQuery("#COLOR_BASKET_PRICEVAHED_TEXT").val(),
				COLOR_BASKET_PRICEVAHED_BG : jQuery("#COLOR_BASKET_PRICEVAHED_BG").val(),
				COLOR_BASKET_PRICEKOL_TEXT : jQuery("#COLOR_BASKET_PRICEKOL_TEXT").val(),
				COLOR_BASKET_PRICEKOL_BG : jQuery("#COLOR_BASKET_PRICEKOL_BG").val(),
				COLOR_BASKET_DEL_TEXT : jQuery("#COLOR_BASKET_DEL_TEXT").val(),
				COLOR_BASKET_DEL_BG : jQuery("#COLOR_BASKET_DEL_BG").val(),
				COLOR_BASKET_TOTAL_TEXT : jQuery("#COLOR_BASKET_TOTAL_TEXT").val(),
				COLOR_BASKET_TOTAL_BG : jQuery("#COLOR_BASKET_TOTAL_BG").val(),
				COLOR_COMPLETEORDER_BG : jQuery("#COLOR_COMPLETEORDER_BG").val(),
				COLOR_COMPLETEORDER_TEXT : jQuery("#COLOR_COMPLETEORDER_TEXT").val(),
				COLOR_BASKET_COUNT_TEXT : jQuery("#COLOR_BASKET_COUNT_TEXT").val(),
				//***********product*************************************************
				COLOR_ADDCARD_BG : jQuery("#COLOR_ADDCARD_BG").val(),
				COLOR_ADDCARD_TEXT : jQuery("#COLOR_ADDCARD_TEXT").val(),
				COLOR_PRODUCTGALLERY_BG : jQuery("#COLOR_PRODUCTGALLERY_BG").val(),
				COLOR_PRODUC_TTITLE_TEXT : jQuery("#COLOR_PRODUC_TTITLE_TEXT").val(),
				COLOR_PRODUCT_PRICE_TEXT : jQuery("#COLOR_PRODUCT_PRICE_TEXT").val(),
				COLOR_PRODUCT_PRICEOFF_TEXT : jQuery("#COLOR_PRODUCT_PRICEOFF_TEXT").val(),
				//**********desc order*********************************************
				COLOR_DETAILO_PAYBUTTON_BG : jQuery("#COLOR_DETAILO_PAYBUTTON_BG").val(),
				COLOR_DETAILO_PAYBUTTON_TEXT : jQuery("#COLOR_DETAILO_PAYBUTTON_TEXT").val(),
				COLOR_DETAILO_PAYDES_TEXT : jQuery("#COLOR_DETAILO_PAYDES_TEXT").val(),
				COLOR_DETAILO_PAYDES_BG : jQuery("#COLOR_DETAILO_PAYDES_BG").val(),
				COLOR_DETAILO_PAYITEM_BG : jQuery("#COLOR_DETAILO_PAYITEM_BG").val(),
				COLOR_DETAILO_PAYITEM_TEXT : jQuery("#COLOR_DETAILO_PAYITEM_TEXT").val(),
				//*********submit order************************************
				COLOR_SUBTMIORDER_BG : jQuery("#COLOR_SUBTMIORDER_BG").val(),
				COLOR_SUBMITORDER_TEXT : jQuery("#COLOR_SUBMITORDER_TEXT").val(),
				COLOR_SUBMITORDER_ACCONT_TEXT : jQuery("#COLOR_SUBMITORDER_ACCONT_TEXT").val(),
				COLOR_SUBMITORDER_TOTAL_TEXT : jQuery("#COLOR_SUBMITORDER_TOTAL_TEXT").val(),
				//********register*****************************************
				COLOR_REGISTERBUTTON_BG : jQuery("#COLOR_REGISTERBUTTON_BG").val(),
				COLOR_REGISTERBUTTON_TEXT : jQuery("#COLOR_REGISTERBUTTON_TEXT").val(),
				//*******edit user**************************************
				COLOR_EDITUSERBUTTON_BG : jQuery("#COLOR_EDITUSERBUTTON_BG").val(),
				COLOR_EDITUSERBUTTON_TEXT : jQuery("#COLOR_EDITUSERBUTTON_TEXT").val(),
				//***********login***************************************
				COLOR_LOGINBUTTON_BG : jQuery("#COLOR_LOGINBUTTON_BG").val(),
				COLOR_LOGINBUTTON_TEXT : jQuery("#COLOR_LOGINBUTTON_TEXT").val(),
				COLOR_LOGIN_FORGETPPASS_TEXT : jQuery("#COLOR_LOGIN_FORGETPPASS_TEXT").val(),
				COLOR_LOGIN_REGISTER_TEXT : jQuery("#COLOR_LOGIN_REGISTER_TEXT").val(),
				//*************change pass******************************
				COLOR_CHANGEPASS_BG : jQuery("#COLOR_CHANGEPASS_BG").val(),
				COLOR_CHANGEPASS_TEXT : jQuery("#COLOR_CHANGEPASS_TEXT").val(),
				//***********forgot pass*****************************
				COLOR_FORGETBUTTON_BG : jQuery("#COLOR_FORGETBUTTON_BG").val(),
				COLOR_FORGETBUTTON_TEXT : jQuery("#COLOR_FORGETBUTTON_TEXT").val(),
		    },
		    success:function(data) {
		    	data = data.substring(0,data.length -1);
		  		if(data == 1) {
		  			jQuery("#alert_color").removeClass('hide');
		  			jQuery("#alert_color").removeClass('alert-success');
		  			jQuery("#alert_color").removeClass('alert-danger');
		  			jQuery("#alert_color").addClass('alert-success');
		  			jQuery("#alert_color").html("اطلاعات ذخیره شد.");
		  		}
		  		else {
		  			jQuery("#alert_color").removeClass('hide');
		  			jQuery("#alert_color").removeClass('alert-success');
		  			jQuery("#alert_color").removeClass('alert-danger');
		  			jQuery("#alert_color").addClass('alert-danger');
		  			jQuery("#alert_color").html("اطلاعات شما ذخیره نشد.");
		  		}
		    }
    	});
	});
});