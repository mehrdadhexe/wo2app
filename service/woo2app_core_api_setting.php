<?php
if (!defined( 'ABSPATH' )) exit;
class woo2app_core_api_setting {
    public function __construct(){
        add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
        add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
        add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
    }
    function woo2app_api_regular_url(){
        add_rewrite_rule('', 'index.php?GET_setting', 'top');
        add_rewrite_rule('', 'index.php?wp_get_a_post', 'top');
        add_rewrite_rule('', 'index.php?woo2app_version', 'top');
    }
    function woo2app_api_query_vars($query_vars){
        $query_vars[] = 'GET_setting';
        $query_vars[] = 'wp_get_a_post';
        $query_vars[] = 'woo2app_version';
        return $query_vars;
    }
    function woo2app_api_parse_request(&$wp){
        if ( array_key_exists( 'GET_setting', $wp->query_vars ) ) {
            $this->woo2app_GET_setting();
            exit();
        }
        if ( array_key_exists( 'woo2app_version', $wp->query_vars ) ) {
            $arr = array();
            $arr['version'] = WOO2APP_VERSION;
            echo json_encode($arr);
            exit();
        }
        if ( array_key_exists( 'wp_get_a_post', $wp->query_vars ) ) {
            $this->wp_get_a_post();
            exit();
        }
        return;
    }
    function wp_get_a_post(){
        ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php wp_head(); ?>
        <div  style="width: 96%;padding: 0 10px">
            <?php
            $post_id = $_GET['post_id'];
            //echo $post_url = get_permalink($post_id);
            $queried_post = get_post($post_id);
            $content = $queried_post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            echo $content;
            ?>
        </div>
        <?php
    }

    function woo2app_GET_setting(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        $mainpage_json = $this->get_mainpage();
        $slider_json = $this->get_slider();
        $menu_json = $this->get_menu();
        $setting_json = $this->get_setting();
        $data_zone = $this->get_default_data();
        $pool = array();//$this->get_woo2app_pool();
        $period =$this->period();
        $update = get_option('woo2app_update');
        $subdomains = get_option('mr2app_shop');
        $inviter = get_option('woo2app_inviter_setting');
        $wallet = array();

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'woo-wallet/woo-wallet.php' ) ) {
            //plugin is activated
            $wallet = get_option('_wallet_settings_general');
            $wallet['product_id'] = get_option('_woo_wallet_recharge_product');
        }

        if(!is_array($subdomains)) $subdomains = array();
        if(!is_array($update)) {
            $update = array();
            $update['android_ver_code'] = '';
            $update['android_update_url'] = '';
            $update['android_update_req'] = false ;
            $update['ios_ver_code'] = '' ;
            $update['ios_update_url'] = '' ;
            $update['ios_update_req'] = false ;
        }
        if(!is_array($inviter)) {
            $inviter = array();
            $inviter['enable_after_register'] = false ;
            $inviter['inviter_title'] = '' ;
            $inviter['inviter_description'] = '' ;
            $inviter['display_score_in_menu'] = false ;
            $inviter['score_value_for_inviter'] = '' ;
            $inviter['score_type'] = '0' ;
            $inviter['score_user_title'] = '' ;
            $inviter['display_marketer_code'] = false ;
            $inviter['marketer_title'] = '' ;
            $inviter['type_make_code'] = 'username' ;
        }
        $array_all_setting =  array(
            'main_page' => $mainpage_json,
            'slider' => $slider_json,
            'menu' => $menu_json,
            'shop_setting' => $setting_json ,
            'states' => $data_zone,
            'poll' => $pool,
            'update' => $update,
            'subdomains' => $subdomains,
            'wallet' => $wallet,
            'period' => $period,
            'inviter' => $inviter,

        );
        ob_clean();
        echo json_encode($array_all_setting);
    }

    public function period(){
        $p = array();
        $p = get_option('mr2app_period');
        return $p;
    }

    private function get_woo2app_pool(){
        global $wpdb;
        $table_name = $wpdb->prefix . "woo2app_nazar";
        $rec = $wpdb->get_results("select * from $table_name where disable = 0");
        $array = array();
        foreach ($rec as $key ) {
            $array[] = array(
                "id" => $key->id,
                "title" => $key->title,
                "type" => $key->type,
                "value" => json_decode($key->value)
            );
        }
        return $array;
    }
    private function get_mainpage(){
        global $wpdb;
        $table_name = $wpdb->prefix . "woo2app_mainpage";
        $rec = $wpdb->get_results("select * from $table_name ORDER BY mp_order ASC");
        $array = array();
        foreach ($rec as $key ) {
            $action = $key->mp_type;
            if ($key->mp_showtype == 4) {
                $slashless = $key->mp_value;
            }
            elseif ($key->mp_showtype == 5 || $key->mp_showtype == 9) {
                $slashless = $this->vizhe($key->mp_value);
                $action = time();
            }
            else{
                $slashless = $key->mp_value;
            }
            $array[] = array(
                "type" => $key->mp_showtype,
                "action" => $action,
                "value" => $slashless,
                "title" => $key->mp_title,
                "pic" => $key->mp_pic,
                "order" => $key->mp_order,
                "sort" => $key->mp_sort
            );
        }
        return $array;
    }
    public function vizhe($value){
        require_once "woo2app_class_product.php";
        $x = new woocommerce_services_new();
        $results = json_decode($value);
        $array = array();
        foreach ($results as $key) {
            $y = $x->get_product($key);
            if($y == false) continue;
            foreach ($y as $p) {
                $array['products'][] = $p;
            }
            //$array[] = $y;
        }
        return $array;
    }
    private function get_slider(){
        global $wpdb;
        $table_name = $wpdb->prefix . "woo2app_slider";
        $rec = $wpdb->get_results("select * from $table_name");
        $array = array();
        foreach ($rec as $key ) {
            $array[] = array(
                "action" => $key->sl_type,
                "value" => $key->sl_value,
                "title" => $key->sl_title,
                "pic" => $key->sl_pic
            );
        }
        return $array;
    }
    private function get_menu(){
        global $wpdb;
        $table_name = $wpdb->prefix . "woo2app_menu";
        $rec = $wpdb->get_results("select * from $table_name where menu_menu = 1 ORDER BY menu_order ASC");
        $array = array();
        foreach ($rec as $key) {
            $array[] = array(
                "action" => $key->menu_action,
                "value" => $key->menu_value,
                "title" => $key->menu_title,
                "pic" => $key->menu_pic,
                "order" => $key->menu_order
            );
        }
        $rec1 = $wpdb->get_results("select * from $table_name where menu_menu = 2 ORDER BY menu_order ASC");
        $array1 = array();
        foreach ($rec1 as $key) {
            $array1[] = array(
                "action" => $key->menu_action,
                "value" => $key->menu_value,
                "title" => $key->menu_title,
                "pic" => $key->menu_pic,
                "order" => $key->menu_order
            );
        }
        $rec1 = $wpdb->get_results("select * from $table_name where menu_menu = 3 ORDER BY menu_order ASC");
        $array3 = array();
        foreach ($rec1 as $key) {
            $array3[] = array(
                "action" => $key->menu_action,
                "value" => $key->menu_value,
                "title" => $key->menu_title,
                "pic" => $key->menu_pic,
                "order" => $key->menu_order
            );
        }
        $arraymenu1 = array(
            'position' => get_option("NUM_MENU1_POS"),
            'items'    => $array
        );
        $arraymenu2 = array(
            'position' => get_option("NUM_MENU2_POS"),
            'items'    => $array1
        );
        $arraymenu3 = array(
            'position' => (get_option("NUM_MENU3_POS") != false) ? get_option("NUM_MENU3_POS") : '-1',
            'items'    => $array3
        );
        return $arrayName = array( $arraymenu1 , $arraymenu2 ,$arraymenu3);
    }
    private function get_setting(){
        $update = get_option('woo2app_update');
        if(!is_array($update)) {
            $update = array();
            $update['android_ver_code'] = '';
            $update['android_update_url'] = '';
            $update['android_update_req'] = false ;
            $update['ios_ver_code'] = '' ;
            $update['ios_update_url'] = '' ;
            $update['ios_update_req'] = false ;
        }
        $arraysetting = array(
            //**************general****************************************
            $arrayName = array('key' => 'URL_SPLASH_PIC' , 'value' => get_option("URL_SPLASH_PIC") ? get_option("URL_SPLASH_PIC") : '0') ,
            $arrayName = array('key' => 'NUM_SPLASH_DELAY' , 'value' => get_option("NUM_SPLASH_DELAY") ? get_option("NUM_SPLASH_DELAY") : '0') ,
            $arrayName = array('key' => 'COLOR_GENERAL_ACTIONBAR_BG' , 'value' => get_option("COLOR_GENERAL_ACTIONBAR_BG")? get_option("COLOR_GENERAL_ACTIONBAR_BG") : 'f53600' ) ,
            $arrayName = array('key' => 'COLOR_GENERAL_ACTIONBAR_TXT' , 'value' => get_option("COLOR_GENERAL_ACTIONBAR_TXT") ? get_option("COLOR_GENERAL_ACTIONBAR_TXT") : 'f53600') ,
            $arrayName = array('key' => 'COLOR_GENERAL_STATUSBAR_BG' , 'value' => get_option("COLOR_GENERAL_STATUSBAR_BG") ? get_option("COLOR_GENERAL_STATUSBAR_BG") : 'eeeeee') ,
            $arrayName = array('key' => 'COLOR_GENERAL_MAIN_BG' , 'value' => get_option("COLOR_GENERAL_MAIN_BG")? get_option("COLOR_GENERAL_MAIN_BG") : 'ffffff' ) ,
            $arrayName = array('key' => 'NUM_MENU1_POS' , 'value' => get_option("NUM_MENU1_POS") ? get_option("NUM_MENU1_POS") : '-1' ) ,
            $arrayName = array('key' => 'NUM_MENU2_POS' , 'value' => get_option("NUM_MENU2_POS") ? get_option("NUM_MENU2_POS") : '-1') ,
            //************primary*******************************************
            $arrayName = array('key' => 'COLOR_CATLISTBUTTON_BG' , 'value' => get_option("COLOR_CATLISTBUTTON_BG")? get_option("COLOR_CATLISTBUTTON_BG") : '1aac1a' ) ,
            $arrayName = array('key' => 'COLOR_CATLISTBUTTON_TEXT' , 'value' => get_option("COLOR_CATLISTBUTTON_TEXT") ? get_option("COLOR_CATLISTBUTTON_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_MOREBUTTON_BG' , 'value' => get_option("COLOR_MOREBUTTON_BG") ? get_option("COLOR_MOREBUTTON_BG") : 'f5363e') ,
            $arrayName = array('key' => 'COLOR_MOREBUTTON_TEXT' , 'value' => get_option("COLOR_MOREBUTTON_TEXT")? get_option("COLOR_MOREBUTTON_TEXT") : 'ffffff' ) ,
            $arrayName = array('key' => 'COLOR_PRODUCTCELL_BG' , 'value' => get_option("COLOR_PRODUCTCELL_BG") ? get_option("COLOR_PRODUCTCELL_BG") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_PRODUCTCELL_TITLE_TEXT' , 'value' => get_option("COLOR_PRODUCTCELL_TITLE_TEXT") ? get_option("COLOR_PRODUCTCELL_TITLE_TEXT") : '000000') ,
            $arrayName = array('key' => 'COLOR_PRODUCTCELL_PRICE_TEXT' , 'value' => get_option("COLOR_PRODUCTCELL_PRICE_TEXT") ? get_option("COLOR_PRODUCTCELL_PRICE_TEXT") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_PRODUCTCELL_OFFPRICE_TEXT' , 'value' => get_option("COLOR_PRODUCTCELL_OFFPRICE_TEXT") ? get_option("COLOR_PRODUCTCELL_OFFPRICE_TEXT") : 'ff0000') ,
            $arrayName = array('key' => 'COLOR_MENU_BG' , 'value' => get_option("COLOR_MENU_BG") ? get_option("COLOR_MENU_BG") : 'eeeeee') ,
            $arrayName = array('key' => 'COLOR_MENU_TEXT' , 'value' => get_option("COLOR_MENU_TEXT") ? get_option("COLOR_MENU_TEXT") : '000000') ,
            $arrayName = array('key' => 'COLOR_LIST_PRICE_DEVIDER_BG' , 'value' => get_option("COLOR_LIST_PRICE_DEVIDER_BG") ? get_option("COLOR_LIST_PRICE_DEVIDER_BG") : 'cfcdcd') ,
            $arrayName = array('key' => 'COLOR_MENU_FLOT_BG' , 'value' => get_option("COLOR_MENU_FLOT_BG")? get_option("COLOR_MENU_FLOT_BG") : 'f5363e' ) ,
            $arrayName = array('key' => 'COLOR_LIST_TITLE_TEXT' , 'value' => get_option("COLOR_LIST_TITLE_TEXT")? get_option("COLOR_LIST_TITLE_TEXT") : '000000' ) ,
            //***********bascket***********************************************
            $arrayName = array('key' => 'COLOR_BASKET_LIST_BG' , 'value' => get_option("COLOR_BASKET_LIST_BG") ? get_option("COLOR_BASKET_LIST_BG") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_BASKET_P_TITLE_TEXT' , 'value' => get_option("COLOR_BASKET_P_TITLE_TEXT")? get_option("COLOR_BASKET_P_TITLE_TEXT") : '000000' ) ,
            $arrayName = array('key' => 'COLOR_BASKET_PRICEVAHED_TEXT' , 'value' => get_option("COLOR_BASKET_PRICEVAHED_TEXT") ? get_option("COLOR_BASKET_PRICEVAHED_TEXT") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_BASKET_PRICEVAHED_BG' , 'value' => get_option("COLOR_BASKET_PRICEVAHED_BG") ? get_option("COLOR_BASKET_PRICEVAHED_BG") : 'e7e7e7') ,
            $arrayName = array('key' => 'COLOR_BASKET_PRICEKOL_TEXT' , 'value' => get_option("COLOR_BASKET_PRICEKOL_TEXT") ? get_option("COLOR_BASKET_PRICEKOL_TEXT") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_BASKET_PRICEKOL_BG' , 'value' => get_option("COLOR_BASKET_PRICEKOL_BG") ? get_option("COLOR_BASKET_PRICEKOL_BG") : 'e7e7e7') ,
            $arrayName = array('key' => 'COLOR_BASKET_DEL_TEXT' , 'value' => get_option("COLOR_BASKET_DEL_TEXT") ? get_option("COLOR_BASKET_DEL_TEXT") : 'f5363e') ,
            $arrayName = array('key' => 'COLOR_BASKET_DEL_BG' , 'value' => get_option("COLOR_BASKET_DEL_BG")? get_option("COLOR_BASKET_DEL_BG") : 'e7e7e7' ) ,
            $arrayName = array('key' => 'COLOR_BASKET_TOTAL_TEXT' , 'value' => get_option("COLOR_BASKET_TOTAL_TEXT") ? get_option("COLOR_BASKET_TOTAL_TEXT") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_BASKET_TOTAL_BG' , 'value' => get_option("COLOR_BASKET_TOTAL_BG")? get_option("COLOR_BASKET_TOTAL_BG") : 'ffffff' ) ,
            $arrayName = array('key' => 'COLOR_COMPLETEORDER_BG' , 'value' => get_option("COLOR_COMPLETEORDER_BG") ? get_option("COLOR_COMPLETEORDER_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_COMPLETEORDER_TEXT' , 'value' => get_option("COLOR_COMPLETEORDER_TEXT") ? get_option("COLOR_COMPLETEORDER_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_BASKET_COUNT_TEXT' , 'value' => get_option("COLOR_BASKET_COUNT_TEXT") ? get_option("COLOR_BASKET_COUNT_TEXT") : '000000') ,
            //***********product**************************************************
            $arrayName = array('key' => 'COLOR_ADDCARD_BG' , 'value' => get_option("COLOR_ADDCARD_BG") ? get_option("COLOR_ADDCARD_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_ADDCARD_TEXT' , 'value' => get_option("COLOR_ADDCARD_TEXT") ? get_option("COLOR_ADDCARD_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_PRODUCTGALLERY_BG' , 'value' => get_option("COLOR_PRODUCTGALLERY_BG") ? get_option("COLOR_PRODUCTGALLERY_BG") : 'eeeeee') ,
            $arrayName = array('key' => 'COLOR_PRODUC_TTITLE_TEXT' , 'value' => get_option("COLOR_PRODUC_TTITLE_TEXT")? get_option("COLOR_PRODUC_TTITLE_TEXT") : '000000' ) ,
            $arrayName = array('key' => 'COLOR_PRODUCT_PRICE_TEXT' , 'value' => get_option("COLOR_PRODUCT_PRICE_TEXT")? get_option("COLOR_PRODUCT_PRICE_TEXT") : '1aac1a' ) ,
            $arrayName = array('key' => 'COLOR_PRODUCT_PRICEOFF_TEXT' , 'value' => get_option("COLOR_PRODUCT_PRICEOFF_TEXT") ? get_option("COLOR_PRODUCT_PRICEOFF_TEXT") : 'f5363e') ,
            //**********desc order*********************************************
            $arrayName = array('key' => 'COLOR_DETAILO_PAYBUTTON_BG' , 'value' => get_option("COLOR_DETAILO_PAYBUTTON_BG") ? get_option("COLOR_DETAILO_PAYBUTTON_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_DETAILO_PAYBUTTON_TEXT' , 'value' => get_option("COLOR_DETAILO_PAYBUTTON_TEXT") ? get_option("COLOR_DETAILO_PAYBUTTON_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_DETAILO_PAYDES_TEXT' , 'value' => get_option("COLOR_DETAILO_PAYDES_TEXT") ? get_option("COLOR_DETAILO_PAYDES_TEXT") : '000000') ,
            $arrayName = array('key' => 'COLOR_DETAILO_PAYDES_BG' , 'value' => get_option("COLOR_DETAILO_PAYDES_BG")? get_option("COLOR_DETAILO_PAYDES_BG") : 'ffffff' ) ,
            $arrayName = array('key' => 'COLOR_DETAILO_PAYITEM_BG' , 'value' => get_option("COLOR_DETAILO_PAYITEM_BG") ? get_option("COLOR_DETAILO_PAYITEM_BG") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_DETAILO_PAYITEM_TEXT' , 'value' => get_option("COLOR_DETAILO_PAYITEM_TEXT") ? get_option("COLOR_DETAILO_PAYITEM_TEXT") : '000000') ,
            //*********submit order************************************
            $arrayName = array('key' => 'COLOR_SUBTMIORDER_BG' , 'value' => get_option("COLOR_SUBTMIORDER_BG") ? get_option("COLOR_SUBTMIORDER_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_SUBMITORDER_TEXT' , 'value' => get_option("COLOR_SUBMITORDER_TEXT") ? get_option("COLOR_SUBMITORDER_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'COLOR_SUBMITORDER_ACCONT_TEXT' , 'value' => get_option("COLOR_SUBMITORDER_ACCONT_TEXT")? get_option("COLOR_SUBMITORDER_ACCONT_TEXT") : 'f5363e' ) ,
            $arrayName = array('key' => 'COLOR_SUBMITORDER_TOTAL_TEXT' , 'value' => get_option("COLOR_SUBMITORDER_TOTAL_TEXT") ? get_option("COLOR_SUBMITORDER_TOTAL_TEXT") : '1d89e4') ,
            //********register*****************************************
            $arrayName = array('key' => 'COLOR_REGISTERBUTTON_BG' , 'value' => get_option("COLOR_REGISTERBUTTON_BG") ? get_option("COLOR_REGISTERBUTTON_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_REGISTERBUTTON_TEXT' , 'value' => get_option("COLOR_REGISTERBUTTON_TEXT")? get_option("COLOR_REGISTERBUTTON_TEXT") : 'ffffff' ) ,
            //*******edit user**************************************
            $arrayName = array('key' => 'COLOR_EDITUSERBUTTON_BG' , 'value' => get_option("COLOR_EDITUSERBUTTON_BG") ? get_option("COLOR_EDITUSERBUTTON_BG") : '1aac1a') ,
            $arrayName = array('key' => 'COLOR_EDITUSERBUTTON_TEXT' , 'value' => get_option("COLOR_EDITUSERBUTTON_TEXT")? get_option("COLOR_EDITUSERBUTTON_TEXT") : 'ffffff' ) ,
            //***********login***************************************
            $arrayName = array('key' => 'COLOR_LOGINBUTTON_BG' , 'value' => get_option("COLOR_LOGINBUTTON_BG") ? get_option("COLOR_LOGINBUTTON_BG") : '1aac1a' ) ,
            $arrayName = array('key' => 'COLOR_LOGINBUTTON_TEXT' , 'value' => get_option("COLOR_LOGINBUTTON_TEXT") ? get_option("COLOR_LOGINBUTTON_TEXT") : 'ffffff' ) ,
            $arrayName = array('key' => 'COLOR_LOGIN_FORGETPPASS_TEXT' , 'value' => get_option("COLOR_LOGIN_FORGETPPASS_TEXT") ? get_option("COLOR_LOGIN_FORGETPPASS_TEXT") : 'COLOR_LOGIN_FORGETPPASS_TEXT' ) ,
            $arrayName = array('key' => 'COLOR_LOGIN_REGISTER_TEXT' , 'value' => get_option("COLOR_LOGIN_REGISTER_TEXT")  ? get_option("COLOR_LOGIN_REGISTER_TEXT") : '1d89e4') ,
            //*************change pass******************************
            $arrayName = array('key' => 'COLOR_CHANGEPASS_BG' , 'value' => get_option("COLOR_CHANGEPASS_BG") ? get_option("COLOR_CHANGEPASS_BG") : '1aac1a' ) ,
            $arrayName = array('key' => 'COLOR_CHANGEPASS_TEXT' , 'value' => get_option("COLOR_CHANGEPASS_TEXT")  ? get_option("COLOR_CHANGEPASS_TEXT") : 'ffffff') ,
            //***********forgot pass*****************************
            $arrayName = array('key' => 'COLOR_FORGETBUTTON_BG' , 'value' => get_option("COLOR_FORGETBUTTON_BG") ? get_option("COLOR_FORGETBUTTON_BG") : '1aac1a' ) ,
            $arrayName = array('key' => 'COLOR_FORGETBUTTON_TEXT' , 'value' => get_option("COLOR_FORGETBUTTON_TEXT") ? get_option("COLOR_FORGETBUTTON_TEXT") : 'ffffff') ,
            $arrayName = array('key' => 'SHOW_BTN_CATLST' , 'value' => get_option("SHOW_BTN_CATLST") ? get_option("SHOW_BTN_CATLST") : '0' ) ,
            //*********** blog color setting*******************************************************************
            $arrayName = array('key' => 'COLOR_BLOG_SELLOL_BG' , 'value' => get_option("COLOR_BLOG_SELLOL_BG")? get_option("COLOR_BLOG_SELLOL_BG") : 'ffffff'  ) ,
            $arrayName = array('key' => 'COLOR_BLOG_SELLOL_TXT' , 'value' => get_option("COLOR_BLOG_SELLOL_TXT") ? get_option("COLOR_BLOG_SELLOL_TXT") : '000000' ) ,
            $arrayName = array('key' => 'COLOR_BLOG_HEADER_BG' , 'value' => get_option("COLOR_BLOG_HEADER_BG") ? get_option("COLOR_BLOG_HEADER_BG") : 'F5363E' ) ,
            $arrayName = array('key' => 'COLOR_BLOG_HEADER_TXT' , 'value' => get_option("COLOR_BLOG_HEADER_TXT") ? get_option("COLOR_BLOG_HEADER_TXT") : 'f7f7f7' ) ,
            $arrayName = array('key' => 'COLOR_BLOG_FOOTER_BG' , 'value' => get_option("COLOR_BLOG_FOOTER_BG") ? get_option("COLOR_BLOG_FOOTER_BG") : 'D17777'  ) ,
            $arrayName = array('key' => 'COLOR_BLOG_FOOTER_TXT' , 'value' => get_option("COLOR_BLOG_FOOTER_TXT") ? get_option("COLOR_BLOG_FOOTER_TXT") : 'ffffff' ) ,
            //*********** pishnehad vizhe color setting*******************************************************************
            $arrayName = array('key' => 'COLOR_VIZHE_TXT' , 'value' => get_option("COLOR_VIZHE_TXT") ? get_option("COLOR_VIZHE_TXT") : 'ff0000' ) ,
            $arrayName = array('key' => 'COLOR_VIZHE_SELLOL_ZAMAN' , 'value' => get_option("COLOR_VIZHE_SELLOL_ZAMAN") ? get_option("COLOR_VIZHE_SELLOL_ZAMAN") : '666666' ) ,
            $arrayName = array('key' => 'COLOR_VIZHE_ZAMAN_TXT' , 'value' => get_option("COLOR_VIZHE_ZAMAN_TXT") ? get_option("COLOR_VIZHE_ZAMAN_TXT") : 'ffffff') ,
            //***********primary setting*******************************************************************
            $arrayName = array('key' => 'DEFAULT_BROWSER_IN' , 'value' => 'NO' ) ,
            $arrayName = array('key' => 'DEFAULT_FONT_APP' , 'value' => get_option("DEFAULT_FONT_APP") ?  get_option("DEFAULT_FONT_APP") : '1' ) ,
            $arrayName = array('key' => 'DEFAULT_PRODUCT_CELL' , 'value' => (int) get_option("DEFAULT_PRODUCT_CELL") ?(int) get_option("DEFAULT_PRODUCT_CELL") : 1  ) ,
            $arrayName = array('key' => 'DEFAULT_UNIT_APP' , 'value' => get_option("DEFAULT_UNIT_APP") ? get_option("DEFAULT_UNIT_APP") : 'تومان' ) ,

            $arrayName = array('key' => 'DEFAULT_LNK_APP' , 'value' => $update['android_update_url']) ,
            $arrayName = array('key' => 'DEFAULT_VER_APP' , 'value' => (int)$update['android_ver_code'] ) ,
            $arrayName = array('key' => 'UNAVAILABLE_PRODUCT_COLOR' , 'value' => get_option("UNAVAILABLE_PRODUCT_COLOR") ? get_option("UNAVAILABLE_PRODUCT_COLOR") : '000000' ) ,
            $arrayName = array('key' => 'BG_BTN_BASKET_CELL' , 'value' => get_option("BG_BTN_BASKET_CELL") ? get_option("BG_BTN_BASKET_CELL") : '1aac1a' ) ,
            $arrayName = array('key' => 'TXT_BTN_BASKET_CELL' , 'value' => get_option("TXT_BTN_BASKET_CELL") ? get_option("TXT_BTN_BASKET_CELL") : 'ffffff'  ) ,
            $arrayName = array('key' => 'BG_BTN_INCREASE_CELL' , 'value' => get_option("BG_BTN_INCREASE_CELL") ? get_option("BG_BTN_INCREASE_CELL") : 'f5363e' ) ,
            $arrayName = array('key' => 'TXT_INCREASE_CELL' , 'value' => get_option("TXT_INCREASE_CELL") ? get_option("TXT_INCREASE_CELL") : 'ffffff' ) ,
            $arrayName = array('key' => 'minimum_purchase_amount' , 'value' => get_option("minimum_purchase_amount") ? get_option("minimum_purchase_amount"): '0' ) ,
            $arrayName = array('key' => 'category_them' , 'value' => get_option("category_them") ? get_option("category_them") : '1' ) ,
            $arrayName = array('key' => 'category_select_btn' , 'value' => get_option("category_select_btn")? get_option("category_select_btn") : '1'  ) ,
            $arrayName = array('key' => 'BUY_WITH_LOGIN' , 'value' => get_option("BUY_WITH_LOGIN")? (bool)get_option("BUY_WITH_LOGIN") : false  ) ,
            $arrayName = array('key' => 'ENTER_WITH_LOGIN' , 'value' => get_option("ENTER_WITH_LOGIN")? (bool)get_option("ENTER_WITH_LOGIN") : false  ) ,
            $arrayName = array('key' => 'NAVIGATION_BUTTON' , 'value' => get_option("NAVIGATION_BUTTON")? get_option("NAVIGATION_BUTTON") : '0'  ) ,
            $arrayName = array('key' => 'COLOR_CELL_CAT_BG' , 'value' => get_option("COLOR_CELL_CAT_BG")? get_option("COLOR_CELL_CAT_BG") : 'ffffff'  ) ,
            $arrayName = array('key' => 'COLOR_CELL_CAT_TXT' , 'value' => get_option("COLOR_CELL_CAT_TXT")? get_option("COLOR_CELL_CAT_TXT") : '000000'  ) ,
            $arrayName = array('key' => 'COLOR_GENERAL_TABBAR_BG' , 'value' => get_option("COLOR_GENERAL_TABBAR_BG")? get_option("COLOR_GENERAL_TABBAR_BG") : 'FF0000'  ) ,
            $arrayName = array('key' => 'COLOR_GENERAL_TABBAR_TXT' , 'value' => get_option("COLOR_GENERAL_TABBAR_TXT")? get_option("COLOR_GENERAL_TABBAR_TXT") : 'ffffff'  ) ,
            $arrayName = array('key' => 'COLOR_GENERAL_TABBAR_SEL' , 'value' => get_option("COLOR_GENERAL_TABBAR_SEL")? get_option("COLOR_GENERAL_TABBAR_SEL") : 'F5363E'  ) ,
            $arrayName = array('key' => 'woo2app_googleloginkey' , 'value' => get_option("woo2app_googleloginkey")? get_option("woo2app_googleloginkey") : ''  ) ,
            $arrayName = array('key' => 'calltoprice_tell' , 'value' => get_option("calltoprice_tell")? get_option("calltoprice_tell") : ''  ) ,
            $arrayName = array('key' => 'calltoprice_price' , 'value' => get_option("calltoprice_price")? get_option("calltoprice_price") : ''  ) ,
            $arrayName = array('key' => 'default_product_images' , 'value' => get_option("default_product_images")? get_option("default_product_images") : ''  ) ,
        );
        return $arraysetting;
    }
    function get_default_data(){
        global $woocommerce;
        $countries_obj   = new WC_Countries();
        //$countries   = $countries_obj->__get('countries');
        $default_country = $countries_obj->get_base_country();
        $default_county_states = $countries_obj->get_states( $default_country );
        $states = array();
        foreach ($default_county_states as $key=>$state) {
            $states[]= array(
                'id'    => $key,
                'name'  => $state
            );
        }
        return $json['states'] = $states;
    }
}