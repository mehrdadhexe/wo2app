<?phpif ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directlyclass woo2app_config_data{	public function __construct() {		$this->insert_into_woo2app_set();	}	function insert_into_woo2app_set(){		//**************general****************************************		if(!get_option('URL_SPLASH_PIC')) add_option("URL_SPLASH_PIC","0"); //adress photo splash		if(!get_option('NUM_SPLASH_DELAY'))	add_option("NUM_SPLASH_DELAY","0");//delay splash		if(!get_option('COLOR_GENERAL_ACTIONBAR_BG'))	add_option("COLOR_GENERAL_ACTIONBAR_BG","f5363e");// color of actionbar		if(!get_option('COLOR_GENERAL_STATUSBAR_BG'))	add_option("COLOR_GENERAL_STATUSBAR_BG","f53600");// color of statusbar		if(!get_option('COLOR_GENERAL_MAIN_BG'))  add_option("COLOR_GENERAL_MAIN_BG","eeeeee");//background color main page		if(!get_option('COLOR_GENERAL_ACTIONBAR_TXT'))	add_option("COLOR_GENERAL_ACTIONBAR_TXT","ffffff");//background color main page		if(!get_option('NUM_MENU1_POS'))	add_option("NUM_MENU1_POS","-1");//position menu1		if(!get_option('NUM_MENU2_POS'))	add_option("NUM_MENU2_POS","-1");//position menu2		//************primary*******************************************		if(!get_option('COLOR_CATLISTBUTTON_BG'))	add_option("COLOR_CATLISTBUTTON_BG","1aac1a");//background button cat		if(!get_option('COLOR_CATLISTBUTTON_TEXT'))	add_option("COLOR_CATLISTBUTTON_TEXT","ffffff");//color button cat		if(!get_option('COLOR_MOREBUTTON_BG'))	add_option("COLOR_MOREBUTTON_BG","f5363e");//background button more		if(!get_option('COLOR_MOREBUTTON_TEXT'))	add_option("COLOR_MOREBUTTON_TEXT","ffffff");//color button more		if(!get_option('COLOR_PRODUCTCELL_BG'))	add_option("COLOR_PRODUCTCELL_BG","ffffff");// background cell product		if(!get_option('COLOR_PRODUCTCELL_TITLE_TEXT'))	add_option("COLOR_PRODUCTCELL_TITLE_TEXT","000000");//color title product		if(!get_option('COLOR_PRODUCTCELL_PRICE_TEXT'))	add_option("COLOR_PRODUCTCELL_PRICE_TEXT","1aac1a");//color price product		if(!get_option('COLOR_PRODUCTCELL_OFFPRICE_TEXT'))	add_option("COLOR_PRODUCTCELL_OFFPRICE_TEXT","ff0000");//color off price		if(!get_option('COLOR_MENU_BG'))	add_option("COLOR_MENU_BG","eeeeee");//background menu		if(!get_option('COLOR_MENU_TEXT'))	add_option("COLOR_MENU_TEXT","000000");//color menu		if(!get_option('COLOR_LIST_PRICE_DEVIDER_BG'))	add_option("COLOR_LIST_PRICE_DEVIDER_BG","cfcdcd");		if(!get_option('COLOR_MENU_FLOT_BG'))	add_option("COLOR_MENU_FLOT_BG","f5363e");		if(!get_option('COLOR_LIST_TITLE_TEXT'))	add_option("COLOR_LIST_TITLE_TEXT","000000");		//***********bascket***********************************************		if(!get_option('COLOR_BASKET_LIST_BG'))	add_option("COLOR_BASKET_LIST_BG","ffffff");		if(!get_option('COLOR_BASKET_P_TITLE_TEXT'))	add_option("COLOR_BASKET_P_TITLE_TEXT","000000");		if(!get_option('COLOR_BASKET_PRICEVAHED_TEXT'))	add_option("COLOR_BASKET_PRICEVAHED_TEXT","1aac1a");		if(!get_option('COLOR_BASKET_PRICEVAHED_BG'))	add_option("COLOR_BASKET_PRICEVAHED_BG","e7e7e7");		if(!get_option('COLOR_BASKET_PRICEKOL_TEXT'))	add_option("COLOR_BASKET_PRICEKOL_TEXT","1aac1a");		if(!get_option('COLOR_BASKET_PRICEKOL_BG'))	add_option("COLOR_BASKET_PRICEKOL_BG","e7e7e7");		if(!get_option('COLOR_BASKET_DEL_TEXT'))	add_option("COLOR_BASKET_DEL_TEXT","f5363e");		if(!get_option('COLOR_BASKET_DEL_BG'))	add_option("COLOR_BASKET_DEL_BG","e7e7e7");		if(!get_option('COLOR_BASKET_TOTAL_TEXT'))	add_option("COLOR_BASKET_TOTAL_TEXT","1aac1a");		if(!get_option('COLOR_BASKET_TOTAL_BG'))	add_option("COLOR_BASKET_TOTAL_BG","ffffff");		if(!get_option('COLOR_COMPLETEORDER_BG'))	add_option("COLOR_COMPLETEORDER_BG","1aac1a");//background button complete		if(!get_option('COLOR_COMPLETEORDER_TEXT'))	add_option("COLOR_COMPLETEORDER_TEXT","ffffff");//color button complete		if(!get_option('COLOR_BASKET_COUNT_TEXT'))	add_option("COLOR_BASKET_COUNT_TEXT","000000");		//***********product**************************************************		if(!get_option('COLOR_ADDCARD_BG'))	add_option("COLOR_ADDCARD_BG","1aac1a");//background button addcart		if(!get_option('COLOR_ADDCARD_TEXT'))	add_option("COLOR_ADDCARD_TEXT","ffffff");//color button addcart		if(!get_option('COLOR_PRODUCTGALLERY_BG'))	add_option("COLOR_PRODUCTGALLERY_BG","eeeeee");//background button gallery		if(!get_option('COLOR_PRODUC_TTITLE_TEXT'))	add_option("COLOR_PRODUC_TTITLE_TEXT","000000");		if(!get_option('COLOR_PRODUCT_PRICE_TEXT'))	add_option("COLOR_PRODUCT_PRICE_TEXT","1aac1a");		if(!get_option('COLOR_PRODUCT_PRICEOFF_TEXT'))	add_option("COLOR_PRODUCT_PRICEOFF_TEXT","f5363e");		if(!get_option('BG_BTN_BASKET_CELL'))	add_option("BG_BTN_BASKET_CELL","1aac1a");//background button gallery		if(!get_option('TXT_BTN_BASKET_CELL'))	add_option("TXT_BTN_BASKET_CELL","ffffff");		if(!get_option('BG_BTN_INCREASE_CELL'))	add_option("BG_BTN_INCREASE_CELL","f5363e");		if(!get_option('TXT_INCREASE_CELL'))	add_option("TXT_INCREASE_CELL","ffffff");		//**********desc order*********************************************		if(!get_option('COLOR_DETAILO_PAYBUTTON_BG'))	add_option("COLOR_DETAILO_PAYBUTTON_BG","1aac1a");		if(!get_option('COLOR_DETAILO_PAYBUTTON_TEXT'))	add_option("COLOR_DETAILO_PAYBUTTON_TEXT","ffffff");		if(!get_option('COLOR_DETAILO_PAYDES_TEXT'))	add_option("COLOR_DETAILO_PAYDES_TEXT","000000");		if(!get_option('COLOR_DETAILO_PAYDES_BG'))	add_option("COLOR_DETAILO_PAYDES_BG","ffffff");		if(!get_option('COLOR_DETAILO_PAYITEM_BG'))	add_option("COLOR_DETAILO_PAYITEM_BG","ffffff");		if(!get_option('COLOR_DETAILO_PAYITEM_TEXT'))	add_option("COLOR_DETAILO_PAYITEM_TEXT","000000");		//*********submit order************************************		if(!get_option('COLOR_SUBTMIORDER_BG'))	add_option("COLOR_SUBTMIORDER_BG","1aac1a");		if(!get_option('COLOR_SUBMITORDER_TEXT'))	add_option("COLOR_SUBMITORDER_TEXT","ffffff");		if(!get_option('COLOR_SUBMITORDER_ACCONT_TEXT'))	add_option("COLOR_SUBMITORDER_ACCONT_TEXT","f5363e");		if(!get_option('COLOR_SUBMITORDER_TOTAL_TEXT'))	add_option("COLOR_SUBMITORDER_TOTAL_TEXT","1d89e4");		//********register*****************************************		if(!get_option('COLOR_REGISTERBUTTON_BG'))	add_option("COLOR_REGISTERBUTTON_BG","1aac1a");		if(!get_option('COLOR_REGISTERBUTTON_TEXT'))	add_option("COLOR_REGISTERBUTTON_TEXT","ffffff");		//*******edit user**************************************		if(!get_option('COLOR_EDITUSERBUTTON_BG'))	add_option("COLOR_EDITUSERBUTTON_BG","1aac1a");		if(!get_option('COLOR_EDITUSERBUTTON_TEXT'))	add_option("COLOR_EDITUSERBUTTON_TEXT","ffffff");		//***********login***************************************		if(!get_option('COLOR_LOGINBUTTON_BG'))	add_option("COLOR_LOGINBUTTON_BG","1aac1a");		if(!get_option('COLOR_LOGINBUTTON_TEXT'))	add_option("COLOR_LOGINBUTTON_TEXT","ffffff");		if(!get_option('COLOR_LOGIN_FORGETPPASS_TEXT'))	add_option("COLOR_LOGIN_FORGETPPASS_TEXT","000000");		if(!get_option('COLOR_LOGIN_REGISTER_TEXT'))	add_option("COLOR_LOGIN_REGISTER_TEXT","1d89e4");		if(!get_option('COLOR_BLOG_SELLOL_BG'))	add_option("COLOR_BLOG_SELLOL_BG","ffffff");		if(!get_option('COLOR_BLOG_SELLOL_TXT'))	add_option("COLOR_BLOG_SELLOL_TXT","000000");		if(!get_option('COLOR_BLOG_HEADER_BG'))	add_option("COLOR_BLOG_HEADER_BG","F5363E");		if(!get_option('COLOR_BLOG_HEADER_TXT'))	add_option("COLOR_BLOG_HEADER_TXT","f7f7f7");		if(!get_option('COLOR_BLOG_FOOTER_BG'))	add_option("COLOR_BLOG_FOOTER_BG","D17777");		if(!get_option('COLOR_BLOG_FOOTER_TXT'))	add_option("COLOR_BLOG_FOOTER_TXT","ffffff");		if(!get_option('COLOR_VIZHE_TXT'))	add_option("COLOR_VIZHE_TXT","ff0000");		if(!get_option('COLOR_VIZHE_SELLOL_ZAMAN'))	add_option("COLOR_VIZHE_SELLOL_ZAMAN","666666");		if(!get_option('COLOR_VIZHE_ZAMAN_TXT'))	add_option("COLOR_VIZHE_ZAMAN_TXT","ffffff");		//*************change pass******************************		if(!get_option('COLOR_CHANGEPASS_BG'))	add_option("COLOR_CHANGEPASS_BG","1aac1a");		if(!get_option('COLOR_CHANGEPASS_TEXT'))	add_option("COLOR_CHANGEPASS_TEXT","ffffff");		//***********forgot pass*****************************		if(!get_option('COLOR_FORGETBUTTON_BG'))	add_option("COLOR_FORGETBUTTON_BG","1aac1a");		if(!get_option('COLOR_FORGETBUTTON_TEXT'))	add_option("COLOR_FORGETBUTTON_TEXT","ffffff");		if(!get_option('SHOW_BTN_CATLST'))	add_option("SHOW_BTN_CATLST","0");//position menu2		//*********primary setting***************************		if(!get_option('DEFAULT_BROWSER_IN'))	add_option("DEFAULT_BROWSER_IN" , "YES");		if(!get_option('DEFAULT_FONT_APP'))	add_option("DEFAULT_FONT_APP" , 1);		if(!get_option('DEFAULT_PRODUCT_CELL'))	add_option("DEFAULT_PRODUCT_CELL" , 1);		if(!get_option('DEFAULT_UNIT_APP'))	add_option("DEFAULT_UNIT_APP" , "تومان");		if(!get_option('DEFAULT_VER_APP'))	add_option("DEFAULT_VER_APP" , 1);		if(!get_option('DEFAULT_LNK_APP'))	add_option("DEFAULT_LNK_APP" , "");		if(!get_option('minimum_purchase_amount'))	add_option("minimum_purchase_amount" , 0);		if(!get_option('UNAVAILABLE_PRODUCT_COLOR'))	add_option("UNAVAILABLE_PRODUCT_COLOR" , "000000");		if(!get_option('category_them'))	add_option("category_them" , 1);		if(!get_option('category_select_btn'))	add_option("category_select_btn" , 1);		if(!get_option('BUY_WITH_LOGIN'))	add_option("BUY_WITH_LOGIN" , false);		if(!get_option('ENTER_WITH_LOGIN'))	add_option("ENTER_WITH_LOGIN" , false);		if(!get_option('display_btn_share'))	add_option("display_btn_share" , false);		if(!get_option('NAVIGATION_BUTTON'))	add_option("NAVIGATION_BUTTON" , 0);		if(!get_option('COLOR_CELL_CAT_BG'))	add_option("COLOR_CELL_CAT_BG" , 'ffffff');		if(!get_option('COLOR_CELL_CAT_TXT'))	add_option("COLOR_CELL_CAT_TXT" , '000000');		if(!get_option('COLOR_GENERAL_TABBAR_BG'))	add_option("COLOR_GENERAL_TABBAR_BG" , 'FF0000');		if(!get_option('COLOR_GENERAL_TABBAR_TXT'))	add_option("COLOR_GENERAL_TABBAR_TXT" , 'ffffff');		if(!get_option('COLOR_GENERAL_TABBAR_SEL'))	add_option("COLOR_GENERAL_TABBAR_SEL" , 'F5363E');	}}