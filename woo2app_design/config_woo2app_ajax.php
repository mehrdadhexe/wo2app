<?php
if (!defined( 'ABSPATH' )) exit;
add_action( 'wp_ajax_add_slider_hami_shop','add_slider_hami_shop' );
function add_slider_hami_shop(){
    $title =$_REQUEST["title"];
    $pic = $_REQUEST["pic"];
    $type = $_REQUEST["type"];
    $value = $_REQUEST["value"];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_slider";
    $r = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name 
		( sl_title , sl_type , sl_value , sl_pic ) 
		VALUES ( %s, %d, %s, %s )", $title,$type,$value,$pic) );
    if($r){
        echo 1;
    }else{
        echo 2;
    }
}
//-------------------------------------------------------
add_action( 'wp_ajax_load_list_slider_hami','load_list_slider_hami' );
function load_list_slider_hami(){
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_slider";
    $sliders = $wpdb->get_results("select * from $table_name");
    $exp = get_option('exp_time_WOO2APP');
    $i = -1;
    $f = 0;
    
    foreach ($sliders as $slider) {
        $i++;
        ?>
        <tr id="row_slider_<?= $slider->sl_id; ?>" style="<?= ($f == 1  && $i >=  2 )? 'opacity:0.4' : '';?>;">
            <td><?= esc_html($slider->sl_title);  ?></td>
            <td>
                <img height="30px;" src="<?= $slider->sl_pic;  ?>">
            </td>
            <td>
                <button onclick="delete_slider_hami(<?= $slider->sl_id; ?>)" type="button" class="btn btn-danger btn-xs">حذف</button>
            </td>
        </tr>
        <?php
    }
}
//-----------------------------------------------------------------------
add_action( 'wp_ajax_delete_item_slider_hami','delete_item_slider_hami' );
function delete_item_slider_hami(){
    $id = $_REQUEST["id"];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_slider";
    $r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE sl_id = %d" , $id));
    if($r){
        echo "1";
    }else{
        echo "2";
    }
}
//---------------------------------------------------------------
add_action( 'wp_ajax_add_item_hami_woo','add_item_hami_woo' );
function add_item_hami_woo(){
    $title = $_REQUEST["title"];
    $type = $_REQUEST["type"];
    $sort = $_REQUEST["sort"]  ;
    $value = $_REQUEST["value"]  ;
    $showtype = $_REQUEST["showtype"];
    $pic = $_REQUEST["pic"];
    global $wpdb;
    if($showtype == 4){
        $value = $_REQUEST['b0'].$_REQUEST['b1'].$_REQUEST['b2'].$_REQUEST['b3'].$_REQUEST['b4'];
    }
    elseif($showtype == 5){
        $pic = time() + $pic * (60*60);
        $results = get_option("$value");
        $results = json_decode($results);
        foreach ($results as $key => $value) {
            if($key == 'title') continue;
            else{
                $value = json_encode($value) ;
            }
        }
    }
    else{
       $value = $_REQUEST["value"];
    }


    $table_name = $wpdb->prefix . "woo2app_mainpage";
    $res = $wpdb->get_results("select MAX(mp_order) AS max_order from $table_name");

    if(!is_null($res[0]->max_order)){
        foreach ($res as $key) {
            $max = $key->max_order + 1;
        }
    }else{
        $max = 1;
    }

    $r = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name 
		( mp_title , mp_type , mp_value , mp_showtype , mp_pic , mp_order ,mp_sort) 
		VALUES ( %s, %d, %s, %d, %s, %d, %s )", $title,$type,$value,$showtype,$pic,$max, $sort) );
    if($r){
        echo 1;
    }
}
//-------------------------------------------------------------------
add_action( 'wp_ajax_load_item_hami_woo','load_item_hami_woo' );
function load_item_hami_woo(){
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_mainpage";
    $res = $wpdb->get_results("select * from $table_name order by mp_order");
    $exp = get_option('exp_time_WOO2APP');
    $i = -1;
    $f = 0;
   
    foreach ($res as $key) {
        $i++;
        ?>
        <li id="record<?= $key->mp_id; ?>">
            <div class="alert alert-info class_menu_appost_hover" style="<?= ($f == 1  && $i >=  2 )? 'opacity:0.4' : '';?>;padding-right:2px;height:40px;padding-top:5px;background-color:#9C5D90;border-color:#9C5D90;">
                <div class="col-md-8 pull-right text-right " style="color:white;">
                    <?php
                    if($key->mp_showtype == 1) echo 'بنر :';
                    elseif($key->mp_showtype == 2) echo 'لیست افقی :';
                    elseif($key->mp_showtype == 3) echo 'لیست عمودی :';
                    elseif($key->mp_showtype == 5) echo 'پیشنهاد ویژه :';

                    if($key->mp_title == "") echo '[بدون عنوان]';
                    ?>
                    <?= esc_html($key->mp_title); ?>
                    <input type="hidden" name="id[]" value="<?php echo $key->mp_id; ?>">
                </div>
                <div class="col-md-4 pull-right text-left">
                    <button onclick="delete_item_mainepage_woo(<?php echo $key->mp_id; ?>)" type="button" class="btn btn-danger btn-xs" >حذف</button>
                </div>
            </div>
        </li>
        <?php
    }
}
//------------------------------------------------------------------
add_action( 'wp_ajax_save_order_woo_item','save_order_woo_item' );
function save_order_woo_item(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'woo2app_mainpage';
    $r = "";
    $data = (explode("_",$_POST["id"])) ;
    $i = 1;
    foreach($data as $id){
        $r += $wpdb->update( $table_name,
            array( 'mp_order' => $i),
            array( 'mp_id' => $id ), array( '%d' ), array( '%d' ) );
        $i++;
    }
    if($r) echo 1;
}
//------------------------------------------------------------------
add_action( 'wp_ajax_delete_item_mainepage_woo','delete_item_mainepage_woo' );
function delete_item_mainepage_woo(){
    $id = $_REQUEST[id];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_mainpage";
    $r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE mp_id = %d" , $id));
    if($r){
        echo "1";
    }else{
        echo "2";
    }
}
//----------------------------------------------------------------------------------------
add_action( 'wp_ajax_change_show_btn_catlst','change_show_btn_catlst' );
function change_show_btn_catlst(){
    $id = $_REQUEST[id];
    update_option("SHOW_BTN_CATLST",$id);
}
//-------------------------------------------------------------------------------
add_action( 'wp_ajax_woo2app_setsplash','woo2app_setsplash' );
function woo2app_setsplash(){
    $delay = $_REQUEST[delay_splash];
    $url = $_REQUEST[url_splash];
    update_option("URL_SPLASH_PIC" , "");
    update_option("NUM_SPLASH_DELAY" , "");
    if( update_option("URL_SPLASH_PIC",$url) && update_option("NUM_SPLASH_DELAY",$delay)	){
        echo 1;
    }else{
        echo 0;
    }
}
//----------------------------------------------------------------------------------------
add_action( 'wp_ajax_set_color_woo2app','set_color_woo2app' );
function set_color_woo2app(){
    $COLOR_GENERAL_ACTIONBAR_BG = sanitize_text_field($_REQUEST["COLOR_GENERAL_ACTIONBAR_BG"]);
    $COLOR_GENERAL_STATUSBAR_BG = sanitize_text_field($_REQUEST["COLOR_GENERAL_STATUSBAR_BG"]);
    $COLOR_GENERAL_MAIN_BG = sanitize_text_field($_REQUEST["COLOR_GENERAL_MAIN_BG"]);
    $COLOR_GENERAL_ACTIONBAR_TXT = sanitize_text_field($_REQUEST["COLOR_GENERAL_ACTIONBAR_TXT"]);
    //************ pishnehad vizhe*******************************************
    $COLOR_VIZHE_TXT = sanitize_text_field($_REQUEST["COLOR_VIZHE_TXT"]);
    $COLOR_VIZHE_SELLOL_ZAMAN = sanitize_text_field($_REQUEST["COLOR_VIZHE_SELLOL_ZAMAN"]);
    $COLOR_VIZHE_ZAMAN_TXT = sanitize_text_field($_REQUEST["COLOR_VIZHE_ZAMAN_TXT"]);
    //************primary*******************************************
    $COLOR_CATLISTBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_CATLISTBUTTON_BG"]);
    $COLOR_CATLISTBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_CATLISTBUTTON_TEXT"]);
    $COLOR_MOREBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_MOREBUTTON_BG"]);
    $COLOR_MOREBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_MOREBUTTON_TEXT"]);
    $COLOR_PRODUCTCELL_BG = sanitize_text_field($_REQUEST["COLOR_PRODUCTCELL_BG"]);
    $COLOR_PRODUCTCELL_TITLE_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUCTCELL_TITLE_TEXT"]);
    $COLOR_PRODUCTCELL_PRICE_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUCTCELL_PRICE_TEXT"]);
    $COLOR_PRODUCTCELL_OFFPRICE_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUCTCELL_OFFPRICE_TEXT"]);
    $COLOR_MENU_BG = sanitize_text_field($_REQUEST["COLOR_MENU_BG"]);
    $COLOR_MENU_TEXT = sanitize_text_field($_REQUEST["COLOR_MENU_TEXT"]);
    $COLOR_LIST_PRICE_DEVIDER_BG = sanitize_text_field($_REQUEST["COLOR_LIST_PRICE_DEVIDER_BG"]);
    $COLOR_MENU_FLOT_BG = sanitize_text_field($_REQUEST["COLOR_MENU_FLOT_BG"]);
    $COLOR_LIST_TITLE_TEXT = sanitize_text_field($_REQUEST["COLOR_LIST_TITLE_TEXT"]);
    //***********bascket***********************************************
    $COLOR_BASKET_LIST_BG = sanitize_text_field($_REQUEST["COLOR_BASKET_LIST_BG"]);
    $COLOR_BASKET_P_TITLE_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_P_TITLE_TEXT"]);
    $COLOR_BASKET_PRICEVAHED_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_PRICEVAHED_TEXT"]);
    $COLOR_BASKET_PRICEVAHED_BG = sanitize_text_field($_REQUEST["COLOR_BASKET_PRICEVAHED_BG"]);
    $COLOR_BASKET_PRICEKOL_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_PRICEKOL_TEXT"]);
    $COLOR_BASKET_PRICEKOL_BG = sanitize_text_field($_REQUEST["COLOR_BASKET_PRICEKOL_BG"]);
    $COLOR_BASKET_DEL_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_DEL_TEXT"]);
    $COLOR_BASKET_DEL_BG = sanitize_text_field($_REQUEST["COLOR_BASKET_DEL_BG"]);
    $COLOR_BASKET_TOTAL_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_TOTAL_TEXT"]);
    $COLOR_BASKET_TOTAL_BG = sanitize_text_field($_REQUEST["COLOR_BASKET_TOTAL_BG"]);
    $COLOR_COMPLETEORDER_BG = sanitize_text_field($_REQUEST["COLOR_COMPLETEORDER_BG"]);
    $COLOR_COMPLETEORDER_TEXT = sanitize_text_field($_REQUEST["COLOR_COMPLETEORDER_TEXT"]);
    $COLOR_BASKET_COUNT_TEXT = sanitize_text_field($_REQUEST["COLOR_BASKET_COUNT_TEXT"]);
    //***********product*************************************************
    $COLOR_ADDCARD_BG = sanitize_text_field($_REQUEST["COLOR_ADDCARD_BG"]);
    $COLOR_ADDCARD_TEXT = sanitize_text_field($_REQUEST["COLOR_ADDCARD_TEXT"]);
    $COLOR_PRODUCTGALLERY_BG = sanitize_text_field($_REQUEST["COLOR_PRODUCTGALLERY_BG"]);
    $COLOR_PRODUC_TTITLE_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUC_TTITLE_TEXT"]);
    $COLOR_PRODUCT_PRICE_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUCT_PRICE_TEXT"]);
    $COLOR_PRODUCT_PRICEOFF_TEXT = sanitize_text_field($_REQUEST["COLOR_PRODUCT_PRICEOFF_TEXT"]);
    //**********desc order*********************************************
    $COLOR_DETAILO_PAYBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYBUTTON_BG"]);
    $COLOR_DETAILO_PAYBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYBUTTON_TEXT"]);
    $COLOR_DETAILO_PAYDES_TEXT = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYDES_TEXT"]);
    $COLOR_DETAILO_PAYDES_BG = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYDES_BG"]);
    $COLOR_DETAILO_PAYITEM_BG = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYITEM_BG"]);
    $COLOR_DETAILO_PAYITEM_TEXT = sanitize_text_field($_REQUEST["COLOR_DETAILO_PAYITEM_TEXT"]);
    //*********submit order************************************
    $COLOR_SUBTMIORDER_BG = sanitize_text_field($_REQUEST["COLOR_SUBTMIORDER_BG"]);
    $COLOR_SUBMITORDER_TEXT = sanitize_text_field($_REQUEST["COLOR_SUBMITORDER_TEXT"]);
    $COLOR_SUBMITORDER_ACCONT_TEXT = sanitize_text_field($_REQUEST["COLOR_SUBMITORDER_ACCONT_TEXT"]);
    $COLOR_SUBMITORDER_TOTAL_TEXT = sanitize_text_field($_REQUEST["COLOR_SUBMITORDER_TOTAL_TEXT"]);
    //********register*****************************************
    $COLOR_REGISTERBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_REGISTERBUTTON_BG"]);
    $COLOR_REGISTERBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_REGISTERBUTTON_TEXT"]);
    //*******edit user**************************************
    $COLOR_EDITUSERBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_EDITUSERBUTTON_BG"]);
    $COLOR_EDITUSERBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_EDITUSERBUTTON_TEXT"]);
    //***********login***************************************
    $COLOR_LOGINBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_LOGINBUTTON_BG"]);
    $COLOR_LOGINBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_LOGINBUTTON_TEXT"]);
    $COLOR_LOGIN_FORGETPPASS_TEXT = sanitize_text_field($_REQUEST["COLOR_LOGIN_FORGETPPASS_TEXT"]);
    $COLOR_LOGIN_REGISTER_TEXT = sanitize_text_field($_REQUEST["COLOR_LOGIN_REGISTER_TEXT"]);
    //*************change pass******************************
    $COLOR_CHANGEPASS_BG = sanitize_text_field($_REQUEST["COLOR_CHANGEPASS_BG"]);
    $COLOR_CHANGEPASS_TEXT = sanitize_text_field($_REQUEST["COLOR_CHANGEPASS_TEXT"]);
    //***********forgot pass*****************************
    $COLOR_FORGETBUTTON_BG = sanitize_text_field($_REQUEST["COLOR_FORGETBUTTON_BG"]);
    $COLOR_FORGETBUTTON_TEXT = sanitize_text_field($_REQUEST["COLOR_FORGETBUTTON_TEXT"]);

    //**************general****************************************
    update_option("COLOR_GENERAL_ACTIONBAR_BG",$COLOR_GENERAL_ACTIONBAR_BG);// color of actionbar
    update_option("COLOR_GENERAL_STATUSBAR_BG",$COLOR_GENERAL_STATUSBAR_BG);// color of statusbar
    update_option("COLOR_GENERAL_MAIN_BG",$COLOR_GENERAL_MAIN_BG);//background color main page
    update_option("COLOR_GENERAL_ACTIONBAR_TXT",$COLOR_GENERAL_ACTIONBAR_TXT);//COLOR_GENERAL_ACTIONBAR_TXT
    //************ pishnehad vizhe*******************************************
    update_option("COLOR_VIZHE_TXT",$COLOR_VIZHE_TXT);//background color main page
    update_option("COLOR_VIZHE_SELLOL_ZAMAN",$COLOR_VIZHE_SELLOL_ZAMAN);//background color main page
    update_option("COLOR_VIZHE_ZAMAN_TXT",$COLOR_VIZHE_ZAMAN_TXT);//background color main page
    //************primary*******************************************
    update_option("COLOR_CATLISTBUTTON_BG",$COLOR_CATLISTBUTTON_BG);//background button cat
    update_option("COLOR_CATLISTBUTTON_TEXT",$COLOR_CATLISTBUTTON_TEXT);//color button cat
    update_option("COLOR_MOREBUTTON_BG",$COLOR_MOREBUTTON_BG);//background button more
    update_option("COLOR_MOREBUTTON_TEXT",$COLOR_MOREBUTTON_TEXT);//color button more
    update_option("COLOR_PRODUCTCELL_BG",$COLOR_PRODUCTCELL_BG);// background cell product
    update_option("COLOR_PRODUCTCELL_TITLE_TEXT",$COLOR_PRODUCTCELL_TITLE_TEXT);//color title product
    update_option("COLOR_PRODUCTCELL_PRICE_TEXT",$COLOR_PRODUCTCELL_PRICE_TEXT);//color price product
    update_option("COLOR_PRODUCTCELL_OFFPRICE_TEXT",$COLOR_PRODUCTCELL_OFFPRICE_TEXT);//color off price
    update_option("COLOR_MENU_BG",$COLOR_MENU_BG);//background menu
    update_option("COLOR_MENU_TEXT",$COLOR_MENU_TEXT);//color menu
    update_option("COLOR_LIST_PRICE_DEVIDER_BG",$COLOR_LIST_PRICE_DEVIDER_BG);
    update_option("COLOR_MENU_FLOT_BG",$COLOR_MENU_FLOT_BG);
    update_option("COLOR_LIST_TITLE_TEXT",$COLOR_LIST_TITLE_TEXT);
    //***********bascket***********************************************
    update_option("COLOR_BASKET_LIST_BG",$COLOR_BASKET_LIST_BG);
    update_option("COLOR_BASKET_P_TITLE_TEXT",$COLOR_BASKET_P_TITLE_TEXT);
    update_option("COLOR_BASKET_PRICEVAHED_TEXT",$COLOR_BASKET_PRICEVAHED_TEXT);
    update_option("COLOR_BASKET_PRICEVAHED_BG",$COLOR_BASKET_PRICEVAHED_BG);
    update_option("COLOR_BASKET_PRICEKOL_TEXT",$COLOR_BASKET_PRICEKOL_TEXT);
    update_option("COLOR_BASKET_PRICEKOL_BG",$COLOR_BASKET_PRICEKOL_BG);
    update_option("COLOR_BASKET_DEL_TEXT",$COLOR_BASKET_DEL_TEXT);
    update_option("COLOR_BASKET_DEL_BG",$COLOR_BASKET_DEL_BG);
    update_option("COLOR_BASKET_TOTAL_TEXT",$COLOR_BASKET_TOTAL_TEXT);
    update_option("COLOR_BASKET_TOTAL_BG",$COLOR_BASKET_TOTAL_BG);
    update_option("COLOR_COMPLETEORDER_BG",$COLOR_COMPLETEORDER_BG);//background button complete
    update_option("COLOR_COMPLETEORDER_TEXT",$COLOR_COMPLETEORDER_TEXT);//color button complete
    update_option("COLOR_BASKET_COUNT_TEXT",$COLOR_BASKET_COUNT_TEXT);
    //***********product**************************************************
    update_option("COLOR_ADDCARD_BG",$COLOR_ADDCARD_BG);//background button addcart
    update_option("COLOR_ADDCARD_TEXT",$COLOR_ADDCARD_TEXT);//color button addcart
    update_option("COLOR_PRODUCTGALLERY_BG",$COLOR_PRODUCTGALLERY_BG);//background button gallery
    update_option("COLOR_PRODUC_TTITLE_TEXT",$COLOR_PRODUC_TTITLE_TEXT);
    update_option("COLOR_PRODUCT_PRICE_TEXT",$COLOR_PRODUCT_PRICE_TEXT);
    update_option("COLOR_PRODUCT_PRICEOFF_TEXT",$COLOR_PRODUCT_PRICEOFF_TEXT);
    //**********desc order*********************************************
    update_option("COLOR_DETAILO_PAYBUTTON_BG",$COLOR_DETAILO_PAYBUTTON_BG);
    update_option("COLOR_DETAILO_PAYBUTTON_TEXT",$COLOR_DETAILO_PAYBUTTON_TEXT);
    update_option("COLOR_DETAILO_PAYDES_TEXT",$COLOR_DETAILO_PAYDES_TEXT);
    update_option("COLOR_DETAILO_PAYDES_BG",$COLOR_DETAILO_PAYDES_BG);
    update_option("COLOR_DETAILO_PAYITEM_BG",$COLOR_DETAILO_PAYITEM_BG);
    update_option("COLOR_DETAILO_PAYITEM_TEXT",$COLOR_DETAILO_PAYITEM_TEXT);
    //*********submit order************************************
    update_option("COLOR_SUBTMIORDER_BG",$COLOR_SUBTMIORDER_BG);
    update_option("COLOR_SUBMITORDER_TEXT",$COLOR_SUBMITORDER_TEXT);
    update_option("COLOR_SUBMITORDER_ACCONT_TEXT",$COLOR_SUBMITORDER_ACCONT_TEXT);
    update_option("COLOR_SUBMITORDER_TOTAL_TEXT",$COLOR_SUBMITORDER_TOTAL_TEXT);
    //********register*****************************************
    update_option("COLOR_REGISTERBUTTON_BG",$COLOR_REGISTERBUTTON_BG);
    update_option("COLOR_REGISTERBUTTON_TEXT",$COLOR_REGISTERBUTTON_TEXT);
    //*******edit user**************************************
    update_option("COLOR_EDITUSERBUTTON_BG",$COLOR_EDITUSERBUTTON_BG);
    update_option("COLOR_EDITUSERBUTTON_TEXT",$COLOR_EDITUSERBUTTON_TEXT);
    //***********login***************************************
    update_option("COLOR_LOGINBUTTON_BG",$COLOR_LOGINBUTTON_BG);
    update_option("COLOR_LOGINBUTTON_TEXT",$COLOR_LOGINBUTTON_TEXT);
    update_option("COLOR_LOGIN_FORGETPPASS_TEXT",$COLOR_LOGIN_FORGETPPASS_TEXT);
    update_option("COLOR_LOGIN_REGISTER_TEXT",$COLOR_LOGIN_REGISTER_TEXT);
    //*************change pass******************************
    update_option("COLOR_CHANGEPASS_BG",$COLOR_CHANGEPASS_BG);
    update_option("COLOR_CHANGEPASS_TEXT",$COLOR_CHANGEPASS_TEXT);
    //***********forgot pass*****************************
    update_option("COLOR_FORGETBUTTON_BG",$COLOR_FORGETBUTTON_BG);
    update_option("COLOR_FORGETBUTTON_TEXT",$COLOR_FORGETBUTTON_TEXT);

    echo 1;

}
//------------------------------------------------------------------------------
add_action( 'wp_ajax_set_position_menu1','set_position_menu1' );
function set_position_menu1(){
    $pos = sanitize_text_field($_REQUEST["pos"]);
    $menu = sanitize_text_field($_REQUEST["menu"]);
    update_option("NUM_MENU".$menu."_POS",$pos);
}
add_action( 'wp_ajax_set_position_menu2','set_position_menu2' );
function set_position_menu2(){
    $pos = sanitize_text_field($_REQUEST["pos"]);
    update_option("NUM_MENU2_POS",$pos);
}
//-----------------------------------------------------------------------------
add_action( 'wp_ajax_set_menu1_item','set_menu1_item' );
function set_menu1_item(){
    $action =$_REQUEST["action_t"];
    $title = $_REQUEST["title"];
    $pic = $_REQUEST["pic"];
    $menu = $_REQUEST["menu"];
    $action_type = $_REQUEST["action_type"];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";

    $max = $wpdb->get_results("SELECT MAX(	menu_order) as max FROM $table_name where menu_menu = 1");
    $m = 1 ;
    if(!is_null($max[0]->max)){
        $m = $max[0]->max + 1;
    }
    $r = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name 
		( menu_title , menu_action , menu_value , menu_pic , menu_menu , menu_order ) 
		VALUES ( %s , %d , %s , %s , %d , %d )", $title,$action,$action_type,$pic,$menu,$m) );

    if($r){
        echo 1;
    }else{
        echo 2;
    }
}
add_action( 'wp_ajax_set_menu2_item','set_menu2_item' );
function set_menu2_item(){
    $action =$_REQUEST["action_t"];
    $title = $_REQUEST["title"];
    $pic = $_REQUEST["pic"];
    $action_type = $_REQUEST["action_type"];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";

    $max = $wpdb->get_results("SELECT MAX(	menu_order) as max FROM $table_name where menu_menu = 2");
    $m = 1 ;
    if(!is_null($max[0]->max)){
        $m = $max[0]->max + 1;
    }
    $r = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name 
		( menu_title , menu_action , menu_value , menu_pic , menu_menu , menu_order ) 
		VALUES ( %s , %d , %s , %s , %d , %d )", $title,$action,$action_type,$pic,2,$m) );

    if($r){
        echo 1;
    }else{
        echo 2;
    }
}
//--------------------------------------------------------------------------
add_action( 'wp_ajax_list_menu1_item','list_menu1_item' );
function list_menu1_item(){
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";
    $menu = $_REQUEST["menu"];
    $res = $wpdb->get_results("select * from $table_name where menu_menu = $menu order by menu_order");
    $exp = get_option('exp_time_WOO2APP');
    $i = -1;
    $f = 0;
   
    foreach ($res as $key) {
        $i++;
        ?>
        <li id="1menu<?= $key->menu_id; ?>">
            <div class="alert alert-info class_menu_appost_hover" style="<?= ($f == 1  && $i >=  3 )? 'opacity:0.4' : '';?>;padding-right:2px;height:40px;padding-top:5px;background-color:#9C5D90;border-color:#9C5D90;">
                <div class="col-md-8 pull-right text-right " style="color:white;">
                    <?= esc_html($key->menu_title); ?>
                    <input type="hidden" name="id[]" value="<?php echo $key->menu_id; ?>">
                </div>
                <div class="col-md-4 pull-right text-left">
                    <button onclick="delete_item_menu1_woo(<?php echo $key->menu_id; ?>)" type="button" class="btn btn-danger btn-xs" >حذف</button>
                </div>
            </div>
        </li>
        <?php
    }
}
add_action( 'wp_ajax_list_menu2_item','list_menu2_item' );
function list_menu2_item(){
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";
    $res = $wpdb->get_results("select * from $table_name where menu_menu = 2 order by menu_order");
    $exp = get_option('exp_time_WOO2APP');
    $i = -1;
    $f = 0;
   
    foreach ($res as $key) {
        $i++;
        ?>
        <li id="2menu<?= $key->menu_id; ?>">
            <div class="alert alert-info class_menu_appost_hover" style="<?= ($f == 1  && $i >=  3 )? 'opacity:0.4' : '';?>;padding-right:2px;height:40px;padding-top:5px;background-color:#9C5D90;border-color:#9C5D90;">
                <div class="col-md-8 pull-right text-right " style="color:white;">
                    <?= esc_html($key->menu_title); ?>
                    <input type="hidden" name="id[]" value="<?php echo $key->menu_id; ?>">
                </div>
                <div class="col-md-4 pull-right text-left">
                    <button onclick="delete_item_menu2_woo(<?php echo $key->menu_id; ?>)" type="button" class="btn btn-danger btn-xs" >حذف</button>
                </div>
            </div>
        </li>
        <?php
    }
}
//--------------------------------------------------------------------------------------
add_action( 'wp_ajax_save_order_woo_menu1','save_order_woo_menu1' );
function save_order_woo_menu1(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'woo2app_menu';
    $r = "";
    $data = (explode("_",sanitize_text_field($_POST["id"]))) ;
    $menu = sanitize_text_field($_POST["menu"]);
    $i = 1;
    foreach($data as $id){
        $r += $wpdb->update( $table_name,
            array( 'menu_order' => $i),
            array( 'menu_id' => $id ), array( '%d' ), array( '%d' ) );
        $i++;
    }
    if($r) echo 1;
}
add_action( 'wp_ajax_save_order_woo_menu2','save_order_woo_menu2' );
function save_order_woo_menu2(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'woo2app_menu';
    $r = "";
    $data = (explode("_",sanitize_text_field($_POST["id"]))) ;
    $i = 1;
    foreach($data as $id){
        $r += $wpdb->update( $table_name,
            array( 'menu_order' => $i),
            array( 'menu_id' => $id ), array( '%d' ), array( '%d' ) );
        $i++;
    }
    if($r) echo 1;
}
//-------------------------------------------------------------------------------
add_action( 'wp_ajax_delete_item_menu1_woo','delete_item_menu1_woo' );
function delete_item_menu1_woo(){
    $id = $_REQUEST[id];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";
    $r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE menu_id = %d" , $id));
    if($r){
        echo "1";
    }else{
        echo "2";
    }
}
add_action( 'wp_ajax_delete_item_menu2_woo','delete_item_menu2_woo' );
function delete_item_menu2_woo(){
    $id = $_REQUEST[id];
    global $wpdb;
    $table_name = $wpdb->prefix . "woo2app_menu";
    $r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE menu_id = %d" , $id));
    if($r){
        echo "1";
    }else{
        echo "2";
    }
}
//---------------------------------------------------------------------------------------------------
add_action( 'wp_ajax_load_all_product','load_all_product' );
function load_all_product(){
    $s = $_REQUEST["search_product"];
    $args = array(
        'numberposts'       => -1,
        'orderby'          => 'ID',
        'order'            => 'DESC',
        'post_type'        => 'product',
        'post_status'      => 'publish',
        'suppress_filters' => true ,
        's' => $s
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        ?>
        <option style="text-align:right;" value="<?= $post->ID; ?>"><?= $post->post_title; ?></option>
        <?php
    }
}

add_action( 'wp_ajax_woo2app_posts', 'rudr_get_posts_ajax_callback2' ); // wp_ajax_{action}
function rudr_get_posts_ajax_callback2(){

    // we will pass post IDs and titles to this array

    $return = array();

    // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
    $post_type = array('post');
    $search_results = new WP_Query( array(
        's'=> $_GET['q'], // the search query
        'post_status' => 'publish', // if you don't want drafts to be returned
        'ignore_sticky_posts' => 1,
        'posts_per_page' => -1 ,// how much to show at once
        'post_type' =>  $post_type,
    ));

    if( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();
            // shorten the title a little
            $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
        endwhile;
    endif;
    echo  json_encode($return) ;
    die;
}

add_action( 'wp_ajax_woo2app_product', 'rudr_get_posts_ajax_callback3' ); // wp_ajax_{action}
function rudr_get_posts_ajax_callback3(){

    // we will pass post IDs and titles to this array

    $return = array();

    // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
    $post_type = array('product');
    $search_results = new WP_Query( array(
        's'=> $_GET['q'], // the search query
        'post_status' => 'publish', // if you don't want drafts to be returned
        'ignore_sticky_posts' => 1,
        'posts_per_page' => -1 ,// how much to show at once
        'post_type' =>  $post_type,
    ));

    if( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();
            // shorten the title a little
            $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
        endwhile;
    endif;
    echo  json_encode($return) ;
    die;
}

