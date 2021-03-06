<?php
/*
Plugin Name: woo2app
Plugin URI: http://mr2app.com
Description: از طریق این افزونه میتوانید اپلیکیشن فروشگاهی خود را مدیریت کنید ٬ تمام تنظیمات در منوی woo2app هستند و به صورت لایو بر روی اپلیکیشن اعمال میشوند ٬ در صورت نیاز به راهنمایی از طریق  mr2app.com/panel به پشتیبانی تیکت ارسال کنید
Version: 1.14.2
Author: MR2APP.com
Author URI: https://mr2app.com
Text Domain: Mr2app
*/
if (!defined( 'ABSPATH' )) exit;
define('WOO2APP_VERSION' , "1.14.2");
define('WOO2APP_PATH' , plugin_dir_path( __FILE__ ));
define('WOO2APP_URL', plugin_dir_url(__FILE__));
define('WOO2APP_CSS_URL', trailingslashit(WOO2APP_URL.'files/css'));
define('WOO2APP_JS_URL', trailingslashit(WOO2APP_URL.'files/js'));
error_reporting(E_ALL);
ini_set('display_errors', 0);
class service_woo_hami{
    static function config_woo2app(){
        require_once WOO2APP_PATH . 'woo2app_config/config_table_woo2app.php';
        $woo = new config_table_woo2app();
        require_once WOO2APP_PATH . 'woo2app_config/config_table_data.php';
        $woo = new woo2app_config_data();
    }
    static function set_api_hami(){
        require_once WOO2APP_PATH . 'service/woocommerce-services-comment.php';
        $woo = new woocommerce_services_comment();
        require_once WOO2APP_PATH . 'service/api_users_hami.php';
        $user = new api_webservices_hami_user();
        require_once WOO2APP_PATH . 'service/woocommerce-services-new.php';
        $woo = new woocommerce_services_new();
        require_once WOO2APP_PATH . 'service/woocommerce-services-customer_new.php';
        $woo = new woocommerce_services_customer_new();
        require_once WOO2APP_PATH . 'service/woocommerce-services-order_new.php';
        $woo = new woocommerce_services_order_new();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_setting.php';
        $woo = new woo2app_core_api_setting();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_orders.php';
        $woo = new woo2app_core_api_orders();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_customers.php';
        $woo = new woo2app_core_api_customers();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_coupon.php';
        $woo = new woo2app_core_api_coupon();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_pay_methods.php';
        $woo = new woo2app_core_api_pay_methods();
        require_once WOO2APP_PATH . 'service/woo2app_core_api_shipping_methods.php';
        $woo = new woo2app_core_api_shipping_methods();
        require_once WOO2APP_PATH . 'service/woo2app_api_default_blog.php';
        $woo = new woo2app_api_default_blog();
        require_once WOO2APP_PATH . 'service/woo2app_api_primary_blog.php';
        $woo = new woo2app_api_primary_blog();
        require_once WOO2APP_PATH . 'woo2app_config/mr2app_feed.php';
        $woo = new mr2app_feed();
        require_once WOO2APP_PATH . 'service/woo2app_google_sign_in.php';
        $woo = new woo2app_google_sign_in();

    }
    static function config_woo2app_menu(){
        include_once( 'woo2app_config/config_woo2app_menu.php') ;
        $woo2app_config_menu = new woo2app_config_menu();
    }
    static function config_woo2app_ajax(){
        require_once WOO2APP_PATH . 'woo2app_design/config_woo2app_ajax.php';
    }
    static function woo2app_metabox(){
        include_once( 'woo2app_config/config_metabox_woo2app.php') ;
        $woo2app_metabox_notif = new woo2app_metabox_notif();
    }
    static function deactivation_woo2app(){
        delete_option("email_WOO2APP");
        delete_option("password_WOO2APP");
        delete_option("appid_WOO2APP");
        delete_option("exp_time_WOO2APP");
        delete_option("last_android_apk_WOO2APP");
        delete_option("last_android_ver_number_WOO2APP");
        delete_option("last_android_ver_name_WOO2APP");
    }
}
register_activation_hook( __FILE__ ,  array('service_woo_hami' , 'config_woo2app'));
register_deactivation_hook( __FILE__ ,  array('service_woo_hami' , 'deactivation_woo2app'));
add_action('init',array( 'service_woo_hami' , 'set_api_hami' ));
add_action('admin_menu',array( 'service_woo_hami' , 'config_woo2app_menu' ));
add_action('init',array( 'service_woo_hami' , 'config_woo2app_ajax' ));
add_action('init' , array('service_woo_hami' , 'woo2app_metabox'));

register_activation_hook( __FILE__ ,  'activation_woo2app_custom_register');
function activation_woo2app_custom_register(){
    $default_fields = array();
    $default_fields [] = array( 'name' => 'user_login' , 'title' => 'نام کاربری','order' => 1);
    $default_fields [] = array( 'name' => 'user_email' , 'title' => 'ایمیل','order' => 3);
    $default_fields [] = array( 'name' => 'user_pass' , 'title' => 'رمز عبور','order' => 4);
    $default_fields [] = array( 'name' => 'user_url' , 'title' => 'آدرس سایت','order' => 5);
    $default_fields [] = array( 'name' => 'display_name' , 'title' => 'نام نمایشی','order' => 6);
    $default_fields [] = array( 'name' => 'first_name' , 'title' => 'نام','order' => 7);
    $default_fields [] = array( 'name' => 'last_name' , 'title' => 'نام خانوادگی','order' => 8);
    $default_fields [] = array( 'name' => 'description' , 'title' => 'توضیحات','order' => 9);
    $default_fields [] = array( 'name' => 'billing_company' , 'title' => 'شرکت','order' => 11);
    $default_fields [] = array( 'name' => 'billing_address_1' , 'title' => 'آدرس ','order' => 12);
    $default_fields [] = array( 'name' => 'billing_address_2' , 'title' => 'آدرس 2','order' => 13);
    $default_fields [] = array( 'name' => 'billing_city' , 'title' => 'شهر','order' => 14);
    $default_fields [] = array( 'name' => 'billing_state' , 'title' => 'استان','order' => 15);
    $default_fields [] = array( 'name' => 'billing_postcode' , 'title' => 'کدپستی','order' => 16);
    $default_fields [] = array( 'name' => 'billing_country' , 'title' => 'کشور','order' => 17);
    $default_fields [] = array( 'name' => 'billing_email' , 'title' => 'ایمیل','order' => 18);
    $default_fields [] = array( 'name' => 'billing_phone' , 'title' => 'تلفن','order' => 19);
    $array_name_new = array('user_login','user_email','user_pass','user_url','display_name','first_name','last_name','description',
        'billing_company','billing_address_1','billing_address_2','billing_city','billing_state','billing_postcode','billing_country','billing_email' ,'billing_phone');
    $args      = array(
        'post_type'   => 'woo2app_register',
        'post_status' => 'draft',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $the_query = get_posts( $args );
    $array_name_old = array();
    foreach ($the_query as $p){
        $array_name_old[] = $p->post_content;
    }
    $result = array_diff($array_name_new,$array_name_old);
    foreach ($default_fields as $f){
        if(in_array($f['name'] , $result)) {
            $array = array(
                'post_title'    => $f['title'],
                'post_content' => $f['name'],
                'post_type'     => 'woo2app_register',
                'post_status'   => 'draft',
                'menu_order' => $f['order'],
            );
            $post = wp_insert_post( $array );
            add_post_meta($post,'default','');
            add_post_meta($post,'required',1);
            add_post_meta($post,'active',1);
            add_post_meta($post,'display_edit',1);
            add_post_meta($post,'display_register',1);
            add_post_meta($post,'values','');
            add_post_meta($post,'type','text');
            add_post_meta($post,'validate','general');
        }
    }
    $array = array(
        'enable' => 0,
        'field' => '',
        'panel' => '',
        'number' => '',
        'username'=> '',
        'password' => ''
    );
    if(!get_option('mr2app_sms')){
        add_option('mr2app_sms',$array);
    }
}

function woo2app_tm_additional_profile_fields( $user ) {
    $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' ,
        'first_name' , 'last_name' , 'description'  ,'billing_company','billing_address_1',
        'billing_address_2','billing_city','billing_state','billing_postcode','billing_country','billing_email','billing_phone');
    $args      = array(
        'post_type'   => 'woo2app_register',
        'post_status' => 'draft',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $the_query = get_posts( $args );
    ?>
    <table class="form-table">
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1grFb5dYPNOQ5FaDHMkZLmVz3s3OerbI"></script>
        <?php
        foreach ($the_query as $f){
            if(in_array($f->post_content,$default_fields)){
                continue;
            }
            $x = get_user_meta($user->ID,$f->post_content , true);
            if(get_post_meta($f->ID , 'type',true) == 'map'){
                $x = explode(',',$x);
                if($x[0]){ $lat = $x[0];} else{ $lat = '32.2972692';}
                if($x[1]){ $lng = $x[1];} else{ $lng = '54.582283';}
                ?>
                <tr>
                    <th><label >  <?= $f->post_title;?></label></th>
                    <td>
                        <div id="map-canvas_<?= $f->ID;?>" style="width: 300px;height: 150px"></div><!-- #map-canvas -->
                        <script type="text/javascript">
                            google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );
                            var map;
                            var markers = [];
                            function gmaps_results_initialize() {
                                map = new google.maps.Map( document.getElementById( 'map-canvas_' + <?= $f->ID;?> ), {
                                    zoom:           13,
                                    center:         new google.maps.LatLng( <?= $lat ;?>, <?= $lng ;?> ),
                                });
                                var  marker = new google.maps.Marker({
                                    position: new google.maps.LatLng( <?= $lat ;?>, <?= $lng ;?> ),
                                    map:      map,
                                    animation: google.maps.Animation.BOUNCE
                                });
                            }
                        </script>
                    </td>
                </tr>
                <?php
            }
            else{
                ?>
                <tr>
                    <th><label>  <?= $f->post_title;?></label></th>
                    <td>
                        <input type="text" id="<?= $f->post_content; ?>" name="<?= $f->post_content; ?>" class="regular-text" value="<?= $x; ?>">
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
}
add_action( 'edit_user_profile', 'woo2app_tm_additional_profile_fields' );

// Hook is used to save custom fields that have been added to the WordPress profile page (if not current user)

add_action( 'edit_user_profile_update', 'woo2app_update_extra_profile_fields' );
function woo2app_update_extra_profile_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) ){
        $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' ,
            'first_name' , 'last_name' , 'description'  ,'billing_company','billing_address_1',
            'billing_address_2','billing_city','billing_state','billing_postcode','billing_country','billing_email','billing_phone');
        $args      = array(
            'post_type'   => 'woo2app_register',
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $the_query = get_posts( $args );
        foreach ($the_query as $f){
            update_user_meta( $user_id, $f->post_content, $_POST["$f->post_content"] );
        }
    }
}


add_action( 'edit_user_profile', 'woo2app_tm_additional_profile_fields_gps' );
function woo2app_tm_additional_profile_fields_gps($user){
    $args = array(
        //'role'         => 'woo2app_super_viser',
        'role'         => 'shop_manager',
    );
    $users = get_users( $args );
//var_dump($users);
    $x = get_user_meta($user->ID , '_woo2app_super_viser' , true);
    if($user->roles[0] == 'marketer') :
        ?>
        <table class="form-table">
            <tr>
                <th><label> انتخاب سوپروایزر </label></th>
                <td>
                    <select name="_woo2app_super_viser">
                        <option> انتخاب کنید </option>
                        <?php
                        foreach ($users as $u){
                            ?>
                            <option value="<?= $u->ID;?>" <?= selected($x,$u->ID)?> > <?= $u->user_email;?> </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php
    endif;
}

add_action( 'edit_user_profile_update', 'woo2app_update_extra_profile_fields_gps' );
function woo2app_update_extra_profile_fields_gps( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) ){
        update_user_meta( $user_id, '_woo2app_super_viser', $_POST["_woo2app_super_viser"] );
    }

}


require_once  "register/class_custom_register.php";
$class_custom_register = new class_custom_register();

require_once  "order_form/class_custom_order.php";
$class_custom_order = new class_custom_order();

require_once  "category_time/category_time.php";

require_once  "category/service_category.php";
$class_custom_order = new mr2app_custom_category();

require_once "filter/class_filter.php";
$mr2app_filter = new mr2app_filter();

require_once "discount/class_discount.php";
$discount = new class_mr2app_discount();

require_once "gps/class_gps.php";
$mr2app_filter = new class_gps();

require_once "inviter/class_inviter.php";
$mr2app_filter = new class_inviter();



register_activation_hook( __FILE__ ,  'activation_mr2app_custom_order');
function activation_mr2app_custom_order(){
    $default_fields = array();
    $default_fields [] = array( 'name' => 'first_name' , 'title' => 'نام','order' => 1);
    $default_fields [] = array( 'name' => 'last_name' , 'title' => 'نام خانوادگی','order' => 2);
    $default_fields [] = array( 'name' => 'address_1' , 'title' => 'آدرس 1','order' => 3);
    $default_fields [] = array( 'name' => 'address_2' , 'title' => 'آدرس 2','order' => 4);
    $default_fields [] = array( 'name' => 'city' , 'title' => 'شهر','order' => 5);
    $default_fields [] = array( 'name' => 'state' , 'title' => 'استان','order' => 6);
    $default_fields [] = array( 'name' => 'postcode' , 'title' => 'کدپستی','order' => 7);
    $default_fields [] = array( 'name' => 'country' , 'title' => 'کشور','order' => 8);
    $default_fields [] = array( 'name' => 'email' , 'title' => 'ایمیل','order' => 9);
    $default_fields [] = array( 'name' => 'phone' , 'title' => 'تلفن','order' => 10);
    $default_fields [] = array( 'name' => 'customer_note' , 'title' => 'توضیحات مشتری','order' => 11);
    $array_name_new = array('first_name','last_name','address_1','address_2','city','state','postcode','country',
        'email','phone','customer_note');
    $args      = array(
        'post_type'   => 'woo2app_order',
        'post_status' => 'draft',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $the_query = get_posts( $args );
    $array_name_old = array();
    foreach ($the_query as $p){
        $array_name_old[] = $p->post_content;
    }
    $result = array_diff($array_name_new,$array_name_old);
    foreach ($default_fields as $f){
        if(in_array($f['name'] , $result)) {
            $array = array(
                'post_title'    => $f['title'],
                'post_content' => $f['name'],
                'post_type'     => 'woo2app_order',
                'post_status'   => 'draft',
                'menu_order' => $f['order'],
            );
            $post = wp_insert_post( $array );
            add_post_meta($post,'default','');
            add_post_meta($post,'required',1);
            add_post_meta($post,'active',1);
            add_post_meta($post,'display',1);
            add_post_meta($post,'values','');
            add_post_meta($post,'relation','');
            add_post_meta($post,'type','text');
            add_post_meta($post,'validate','general');
        }
    }
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'marketer_gps';
    $sql = "CREATE TABLE $table_name (
		mg_id bigint(20) NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (mg_id),
		mg_uid bigint(20) NOT NULL,
		mg_time bigint(20) NOT NULL,
		mg_latitude varchar(20) NOT NULL,
		mg_longitude varchar(20) NOT NULL
	) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    $table_name = $wpdb->prefix . 'location_gps';
    $sql = "CREATE TABLE $table_name (
		lg_id bigint(20) NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (lg_id),
		lg_lat varchar (20) NOT NULL,
		lg_lng varchar(20) NOT NULL,
		lg_title varchar(20) NOT NULL
	) $charset_collate;";
    dbDelta( $sql );

}


global $wp_roles;
if ( ! isset( $wp_roles ) )
    $wp_roles = new WP_Roles();

$adm = $wp_roles->get_role('seller');
//Adding a 'new_role' with all admin caps
$wp_roles->add_role('marketer', 'بازاریاب', $adm->capabilities);
//add_action( 'admin_init', 'my_remove_menu_pages' );



add_filter('manage_users_columns', 'manage_users_columns_woo2app');
function manage_users_columns_woo2app($columns) {
    if (current_user_can('manage_woocommerce')) {
        $columns['billing_phone'] = __('تلفن همراه', 'mobile');
        $columns['my_invitecode'] = __('<a href="users.php?orderby=my_invitecode&order=asc"> کد معرف من </a>', 'my_invitecode');
        $columns['my_invitor'] = __('معرف ', 'invitor');
        $columns['billing_address'] = __('آدرس ', 'address');
        $columns['created'] = __('<a href="users.php?orderby=user_registered&order=asc"> تاریخ ثبت نام</a>', 'created');
    }
    return $columns;
}

add_filter('manage_users_custom_column', 'woo2app_manage_users_custom_column', 10, 3);

function woo2app_manage_users_custom_column($value, $column_name, $user_id) {
    if ($column_name == 'billing_phone') {
        return get_user_meta($user_id , 'billing_phone' , true)?: '----';
    }
    elseif ($column_name == 'my_invitecode') {
        return get_user_meta($user_id , 'my_invitecode' , true)?: '----';
    }
    elseif ($column_name == 'my_invitor') {
        return get_user_meta($user_id , 'my_invitor' , true) ?: '----';
    }
    elseif ($column_name == 'billing_address') {
        return get_user_meta($user_id , 'billing_address' , true) ?: '----';
    }
    elseif ($column_name == 'created') {
        $user = get_user_by('id', $user_id);
        if ( is_plugin_active( 'wp-jalali/wp-jalali.php' ) ) {
            //plugin is activated
            return jdate('Y-m-d-H:i',$user->user_registered);
        }
        return $user->user_registered;
    }

    return $value;
}

add_action( 'admin_menu','admin_menu');
function admin_menu(){
    add_users_page('کاربران روی نقشه','کاربران روی نقشه','manage_options','user_map','user_map');
}

function user_map(){

    ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>

    <div id="map" style="width:100%;height:600px;margin-bottom: 10px"></div>

    <?php

    $users = get_users(array(
        'meta_key'     => '_order_map',
        'meta_compare' => 'EXISTS' // this should work...
    ));
    foreach ($users as $u){
        $meta = get_user_meta($u->ID , '_order_map' , true);
        if($meta == '') continue;
        ?>
        <input type="hidden" attr-username="<?= $u->user_login?>" attr-email="<?= $u->user_email?>" class="map" value="<?= $meta;?>">
        <?php
    }
    ?>

    <script>

        var mymap = L.map('map').setView([35.70163, 51.39211], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1IjoiaGFuaTg2MiIsImEiOiJjam44eDE0b2wwaTYxM3dwNm9rNDV0MjZsIn0.OsaccDr_jnVzhtXQjzhO3w'
        }).addTo(mymap);

        $( ".map" ).each(function() {
            $s = $(this).val().split(",");
            L.marker([$s[0],$s[1]]).addTo(mymap)
                .bindPopup('username : ' + $(this).attr('attr-username') +'<br> email : ' + $(this).attr('attr-email'));
        });


    </script>
    <?php

}

function woo2app_add_admin_css_js_everywhere( $hook ) {

    if ( 'plugins.php' === $hook ) {
        wp_enqueue_style( 'woo2app-modal', WOO2APP_URL . '/files/feedback/css/woo2app-modal.css', null, WOO2APP_VERSION );
        wp_enqueue_script( 'woo2app-modal', WOO2APP_URL . '/files/feedback/js/woo2app-modal.js', null, WOO2APP_VERSION, true );
        wp_localize_script( 'woo2app-modal', 'woo2app_ajax_data', array( 'nonce' => wp_create_nonce( 'woo2app-ajax' ) ) );
        echo generate();
    }
}
add_action( 'admin_enqueue_scripts', 'woo2app_add_admin_css_js_everywhere', 11 );

//set instructions on how to sort the new column
if(is_admin()) {//prolly not necessary, but I do want to be sure this only runs within the admin
    add_action('pre_user_query', 'my_user_query');
}
function generate() {

    //$url = 'plugins.php?action=deactivate&amp;plugin=' . rawurlencode( 'woo2app/woo2app.php');
    //wp_create_nonce( 'force_deactivation' );

    $data = [
        'deactivation_url' =>  wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . rawurlencode( 'woo2app/woo2app.php' ),'deactivate-plugin_woo2app/woo2app.php'),
    ];

    include "deactivate-form.php";

    return trim( ob_get_clean() );
}

function woo2app_direct_filesystem() {
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    return new WP_Filesystem_Direct( new StdClass() );
}



function my_user_query($userquery){
    if('my_invitecode' == $userquery->query_vars['orderby']) {//check if church is the column being sorted
        global $wpdb;
        $userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS alias ON ($wpdb->users.ID = alias.user_id) ";//note use of alias
        $userquery->query_where .= " AND alias.meta_key = 'my_invitecode' ";//which meta are we sorting with?
        $userquery->query_orderby = " ORDER BY alias.meta_value ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
    }
}


register_deactivation_hook( __FILE__, 'myplugin_deactivate' );

function myplugin_deactivate(){


}

// Run the init function when it's time to load the plugin
add_action( 'plugins_loaded', array( 'Better_User_Search', 'init' ) );

// This is here to prevent redeclaration of the class
if ( ! class_exists( 'Better_User_Search' ) ) {
    // This is where the magic happens!
    class Better_User_Search {
        // Plugin version
        public static $version = '1.1.1';

        // Instance of the class
        protected static $instance;

        // This function is called when loading our plugin
        public static function init() {
            // Check to see if an instance already exists
            if ( is_null( self::$instance ) ) {
                // Create a new instance
                self::$instance = new self;
            }

            // Return the instance
            return self::$instance;
        }

        // Class constructor
        public function __construct() {
            // This plugin is for the backend only
            if ( ! is_admin() ) {
                return;
            }

            // Add the overwrite actions for the search
            add_action( 'pre_user_query', array( $this, 'pre_user_query' ), 100 );

            // Add the backend menu page
            //add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            // Add a link to the Settings page on the Plugins page
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
        }



        // Plugin initialization




        // Add Settings link on Plugins page
        public function plugin_action_links( $actions ) {
            // Define our custom action link
            $custom_actions = array(
                sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=woo2app/woo2app_design/woo2app_show_design.php' ), __( 'Settings' ) ),
            );

            // Merge our custom actions with the existing actions
            return array_merge( $custom_actions, $actions );
        }


        // The actual improvement of the query
        public function pre_user_query( $user_query ) {
            // Must not be searching for email
            //   Commented out because it seems somewhat unnessary
            //   If performance complaints are received, consider making this a setting
            /*
            if ( strpos( $user_query->query_where, '@' ) !== false ) {
                return false;
            }
            */

            // Variable to determine if we are going to intercept or not
            $intercept_query = false;

            // Users page integration
            if ( isset( $_GET['s'] ) ) {
                $term = sanitize_text_field( $_GET['s'] );

                if ( stripos( $_SERVER['REQUEST_URI'], 'users.php' ) !== false && ! empty( $term ) ) {
                    $intercept_query = true;
                }
            }

            // WooCommerce Orders customer search integration
            if ( ! $intercept_query && $this->plugin_active( 'wc' ) && isset( $_GET['action'] ) && isset( $_GET['term'] ) ) {
                $action = $_GET['action'];
                $term   = sanitize_text_field( $_GET['term'] );

                if ( 'woocommerce_json_search_customers' === $action && ! empty( $term ) ) {
                    $intercept_query = true;
                }
            }

            // Bail out if we are not intercepting the query
            if ( ! $intercept_query ) {
                return false;
            }

            // Global DB object
            global $wpdb;

            // Get the data we need from helper methods
            $terms     = $this->get_search_terms();
            $meta_keys = $this->get_meta_keys();

            // Are we performing an AND (default) or an OR?
            $search_with_or = in_array( 'or', $terms );

            if ( $search_with_or ) {
                // Remove the OR keyword(s) from the terms
                $terms = array_diff( $terms, array( 'or', 'and' ) );

                // Reset the array keys
                $terms = array_values( $terms );
            }

            // We use a permanent table because you cannot reference MySQL temporary tables more than once per query
            $mktable = "{$wpdb->prefix}better_user_search_meta_keys";

            // Create our table to store the meta keys
            $wpdb->query( $sql = "CREATE TABLE IF NOT EXISTS {$mktable} (meta_key VARCHAR(255) NOT NULL);" );

            // Empty the table to ensure that we have an accurate set of meta keys
            $wpdb->query( $sql = "TRUNCATE TABLE {$mktable};" );

            // Insert the meta keys into our table
            $prepare_values_array = array_fill( 0, count( $meta_keys ), '(%s)' );
            $prepare_values = implode( ", ", $prepare_values_array ); // Add "\n\t\t\t\t\t\t" after the comma for easier debugging

            $insert_sql = $wpdb->prepare( "
				INSERT INTO {$mktable}
					(meta_key)
				VALUES
					{$prepare_values};
			", $meta_keys );

            $wpdb->query( $insert_sql );

            // Build our data for $wpdb->prepare
            $values = array();

            // Make sure we replicate each term XX number of times (refer to query below for correct number)
            foreach ( $terms as $term ) {
                for ( $i = 0; $i < 6; $i++ ) {
                    $values[] = "%{$term}%";
                }
            }

            // Our last value is for HAVING COUNT(*), so let's add that
            // Note the min count is 1 if we found OR in the terms
            $values[] = ( $search_with_or !== false ? 1 : count( $terms ) );
            // Query for matching users
            $user_ids = $wpdb->get_col( $sql = $wpdb->prepare( "
				SELECT user_id
				FROM (" . implode( 'UNION ALL', array_fill( 0, count( $terms ), "
					SELECT DISTINCT u.ID AS user_id
					FROM {$wpdb->users} u
					INNER JOIN {$wpdb->usermeta} um
					ON um.user_id = u.ID
					INNER JOIN {$mktable} mk
					ON mk.meta_key = um.meta_key
					WHERE LOWER(um.meta_value) LIKE %s
					OR LOWER(u.user_login) LIKE %s
					OR LOWER(u.user_nicename) LIKE %s
					OR LOWER(u.user_email) LIKE %s
					OR LOWER(u.user_url) LIKE %s
					OR LOWER(u.display_name) LIKE %s
				" ) ) . ") AS user_search_union
				GROUP BY user_id 
				HAVING COUNT(*) >= %d;
			", $values ) );

            // Change query to include our new user IDs
            if ( is_array( $user_ids ) && count( $user_ids ) ) {
                // Combine the IDs into a comma separated list
                $id_string = implode( ',', $user_ids );

                // Build the SQL we are adding to the query
                $extra_sql = " OR ID IN ({$id_string})";

                // @dale3h 2016/01/28 21:51:00 - Admin Columns Pro fix
                $add_after    = 'WHERE ';
                $add_position = strpos( $user_query->query_where, $add_after ) + strlen( $add_after );

                // Add the query to the end, after wrapping the rest in parenthesis
                $user_query->query_where = substr( $user_query->query_where, 0, $add_position ) . '(' . substr( $user_query->query_where, $add_position ) . ')' . $extra_sql;
            }
        }

        // Get array of user search terms
        public function get_search_terms() {
            // Get the WordPress search term(s)
            $terms = trim( strtolower( stripslashes( $_GET['s'] ) ) );

            // Get the WooCommerce search term(s)
            if ( empty( $terms ) && $this->plugin_active( 'wc' ) ) {
                $terms = trim( strtolower( stripslashes( $_GET['term'] ) ) );
            }

            // Bail out if we cannot find any search term(s)
            if ( empty( $terms ) ) {
                return array();
            }

            // Split terms by space into an array
            $terms = explode( ' ', $terms );

            // Remove empty terms
            foreach ( $terms as $key => $term ) {
                if ( empty( $term ) ) {
                    unset( $terms[ $key ] );
                }
            }

            // Reset the array keys
            $terms = array_values( $terms );

            // Return the array of terms
            return $terms;
        }

        // Generate an array of default meta keys based on active plugins that are compatible
        public function get_default_meta_keys() {
            // WordPress defaults
            $meta_keys = array(
                'first_name',
                'last_name',
            );

            // WooCommerce defaults
            if ( $this->plugin_active( 'wc' ) ) {
                $meta_keys = array_merge( $meta_keys, array(
                    'billing_address_1',
                    'billing_address_2',
                    'billing_city',
                    'billing_company',
                    'billing_country',
                    'billing_email',
                    'billing_first_name',
                    'billing_last_name',
                    'billing_phone',
                    'billing_postcode',
                    'billing_state',
                    'shipping_address_1',
                    'shipping_address_2',
                    'shipping_city',
                    'shipping_company',
                    'shipping_country',
                    'shipping_first_name',
                    'shipping_last_name',
                    'shipping_postcode',
                    'shipping_state',
                    'my_invitecode',
                    'invitor',

                ) );
            }

            // Return the default meta keys
            return $meta_keys;
        }

        // Get user-defined meta keys
        public function get_meta_keys() {
            // Get the meta keys from the settings
            $meta_keys = get_option( 'bu_search_meta_keys', $this->get_default_meta_keys() );

            // Make it an array if it isn't one already
            if ( ! is_array( $meta_keys ) ) {
                $meta_keys = ! empty( $meta_keys ) ? array( $meta_keys ) : array();
            }

            // Return the meta keys
            return $meta_keys;
        }

        // Get all searchable meta keys from the wp_usermeta table
        public function get_all_meta_keys() {
            // Global DB object
            global $wpdb;

            // Query for all meta keys from the user meta table
            return $wpdb->get_col( $sql = "
				SELECT DISTINCT meta_key
				FROM {$wpdb->usermeta}
				WHERE meta_key IS NOT NULL
				AND meta_key != ''
				ORDER BY meta_key;
			" );
        }

        // Function to detect if a specific plugin is active
        public function plugin_active( $plugin ) {
            // Shorthand definitions for ease-of-use
            $plugins = array(
                'wc' => 'woocommerce/woocommerce.php',
            );

            // If shorthand is used, get the script name from the array
            if ( isset( $plugins[ $plugin ] ) ) {
                $plugin = $plugins[ $plugin ];
            }

            // Return the active status of the plugin
            return in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
        }
    }
}




///گرفتن اکشن تکمیل سفارش  و ارسال پورسانت به حساب معرف
add_action( 'woocommerce_order_status_completed', 'your_function', 10,120);
function your_function($order_id) {

      //
    $order = wc_get_order( $order_id );
    $userv = $order->get_user();
    $userid=$userv->ID;

    if($userv){
       if (class_exists('Referral_Main'))
                    {

             $type= get_post_meta($order_id, 'woo_order_key_type', true );

                       if($type=='app')
                       {

                        global $wpdb;
                        $moaref_name = get_user_meta($userid, 'moaref_name', true );

                        $user = get_user_by( 'login',$moaref_name);




                       $table_name = $wpdb->prefix . 'uap_affiliates';
                       $q = $wpdb->prepare("SELECT id FROM $table_name WHERE uid=%d ;",$user->ID);
                       $data = $wpdb->get_row($q);
                       $refi= $data->id;

                       $get_uap_class=new Referral_Main($userid,$refi);
                       $get_uap_class->insert_app_referral($userid,$order_id);


                      }




                    }

                  }

}


















