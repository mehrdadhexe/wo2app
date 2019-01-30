<?php
/**
 * Created by mr2app.
 * User: hani
 * Date: 12/13/17
 * Time: 6:07 PM
 */
class class_custom_register {
    function __construct(){
//		echo $this->getBrowser();
//		exit();
        add_action( 'init', array( $this , 'mr2app_custom_register_api_regular_url' ));
        add_filter( 'query_vars', array( $this , 'mr2app_custom_register_api_query_vars' ));
        add_action( 'parse_request', array( $this , 'mr2app_custom_register_api_parse_request' ));
    }
    function getBrowser() {
        global $user_agent;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'unknown';
        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );
        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;
        return $browser;
    }
    function mr2app_custom_register_api_regular_url(){
        add_rewrite_rule('^mr2app/login_register_form$', 'index.php?login_register_form=$matches[1]', 'top');
        add_rewrite_rule('^mr2app/get_register_form$', 'index.php?get_register_form=$matches[1]', 'top');
        add_rewrite_rule('^mr2app/register_sms$', 'index.php?mr2app_register_sms=$matches[1]', 'top');
        add_rewrite_rule('^mr2app/generate_password$', 'index.php?generate_password=$matches[1]', 'top');
        add_rewrite_rule('^mr2app/register', 'index.php?register=$matches[1]', 'top');
        add_rewrite_rule('^mr2app/user_edit', 'index.php?user_edit=$matches[1]', 'top');
        flush_rewrite_rules();
    }
    function mr2app_custom_register_api_query_vars($query_vars) {
        $query_vars[] = 'login_register_form';
        $query_vars[] = 'get_register_form';
        $query_vars[] = 'mr2app_register_sms';
        $query_vars[] = 'generate_password';
        $query_vars[] = 'register';
        $query_vars[] = 'user_edit';
        return $query_vars;
    }
    function mr2app_custom_register_api_parse_request(&$wp){
        if ( array_key_exists( 'login_register_form', $wp->query_vars ) ) {
            $this->login_register_form();
            exit();
        }
        if ( array_key_exists( 'get_register_form', $wp->query_vars ) ) {
            $this->get_register_form();
            exit();
        }
        if ( array_key_exists( 'mr2app_register_sms', $wp->query_vars ) ) {
            $this->send_sms();
            exit();
        }
        if ( array_key_exists( 'generate_password', $wp->query_vars ) ) {
            $this->generate_password();
            exit();
        }
        if ( array_key_exists( 'register', $wp->query_vars ) ) {
            $this->register();
            exit();
        }
        if ( array_key_exists( 'user_edit', $wp->query_vars ) ) {
            $this->user_edit();
            exit();
        }
        return;
    }
    /*
     * http://localhost/wordpress/mr2app/login_register_form?in={"user_name":"root","password":"123456"}
     */
    public function login_register_form(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        $result = array();
        $result['error'] = -2;
        $result['msg'] = "نام کاربری وجود ندارد.";
        $result['customer'] = "";
        if(isset($_POST['in'])){
            $in = $_POST["in"];
            $slashless = stripcslashes( $in );
            $url_json = urldecode( $slashless );
            $json = (array) json_decode( $url_json );
            $user = $json["user_name"];
            $pass =  $json["password"];
            $flag = -2;
            $s = 0;
            $hami_check = "";
            if(get_user_by( 'login', $user ) == true){
                $hami_check = get_user_by( 'login', $user );
                $s = 1;
            }
            elseif(get_user_by( 'email', $user ) == true ){
                $hami_check = get_user_by( 'email', $user );
                $s = 1;
            }
            if($s == 1 &&  ! wp_check_password( $pass, $hami_check->data->user_pass, $hami_check->ID )){
                $flag = -3;
                $result['error'] = -3;
                $result['msg'] = "رمز عبور اشتباه می باشد.";
                $result['customer'] = array();
            }
            if ( $s == 1 && wp_check_password( $pass, $hami_check->data->user_pass, $hami_check->ID )) {
                $flag = 1;
                $result['error'] = 1;
                $result['msg'] = 'ورود با موفقیت انجام شد.';
                $result['customer'] = $this->mr2app_get_customer( $hami_check->ID);
                $result['meta'] = $this->mr2app_get_user_meta($hami_check->ID);
            }
        }
        ob_clean();
        echo json_encode($result);
        return;
    }
    /*
     * mr2app/register/?in={"user_login":"haniasd","user_nicename":"hani","last_name":"ghasemi","user_email":"ghasemy2.haniasd@gmail.com","user_pass":"1122","phone":"09155103877","address":"address","city":"city","postcode":"499455","user_url":"mr2app.com","display_name":"hanghas"}
     */
    public function register(){
        header('Content-Type: application/json; charset=utf-8');
        $result = array();
        $result['status'] = false;
        $result['user_id'] = "";
        $result['msg'] = "";
        if(isset($_POST['in'])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = json_decode($url_json);
            $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' ,'first_name' , 'last_name' , 'description' );
            $new_customer_data =  array(
                'user_login' => $json->user_login,
                'first_name'  => isset($json->first_name)? $json->first_name : '',
                'last_name'  => isset($json->last_name)? $json->last_name : '',
                'description'  => isset($json->description)? $json->description : '',
                'user_email' => $json->user_email,
                'user_pass' => $json->user_pass,
                'user_url' => $json->user_url,
                'display_name' => $json->display_name,
                'role'       => 'customer',
            );
            $user = wp_insert_user( $new_customer_data );
            if($user->errors){
                foreach ($user->errors as $key ) {
                    $result['status'] = false;
                    $result['user_id'] = '';
                    $result['msg'] = $key[0];
                }
            }
            elseif($user){
                foreach ($json as $key => $value){
                    if(in_array($key ,$default_fields)){
                        continue;
                    }
                    add_user_meta($user,$key,$value);
                }

                $result['status'] = true;
                $result['user_id'] = $user;
                $result['msg'] = 'ثبت نام انجام شد.';
                $result['customer'] = $this->mr2app_get_customer($user);
                $result['meta'] = $this->mr2app_get_user_meta($user);



//////ارسال پورسانت به حساب معرف بعد از ثبت نام کاربر معرفی شده توسط معرف ر 
                 if (class_exists('Referral_Main'))
                    {

                         global $wpdb;
                        $moaref_name = get_user_meta( $user, 'moaref_name', true );

                        $userv = get_user_by( 'login',$moaref_name);




                       $table_name = $wpdb->prefix . 'uap_affiliates';
                       $q = $wpdb->prepare("SELECT id FROM $table_name WHERE uid=%d ;",$userv->ID);
                       $data = $wpdb->get_row($q);
                       $refi= $data->id;
                       //Referral_Main($user,$refi);


                        $get_uap_class=new Referral_Main($user,$refi);
                        $get_uap_class->insert_signup_referral($user);





                    }





            }
            else{
                $result['status'] = false;
                $result['user_id'] = '';
                $result['msg'] = 'مشکلی رخ داده است.';
            }
        }
        $array = array();
        $array['result'] = $result;
        echo json_encode($array);
        return;
    }
    public function mr2app_get_customer($user){
        $user = get_user_by( 'id', $user);
        $customer = array(
            "id" => $user->ID,
            "first_name" => get_user_meta($user->ID,'first_name',true),
            "last_name" => get_user_meta($user->ID,'last_name',true),
            "phone" => get_user_meta($user->ID,'billing_phone',true),
            "address" => get_user_meta($user->ID,'billing_address_1',true),
            "city" =>get_user_meta($user->ID,'billing_city',true) ,
            "email" => $user->user_email,
            "state" => get_user_meta($user->ID,'billing_state',true),
            "postcode" => get_user_meta($user->ID,'billing_postcode',true),
            "role" =>  $user->roles[0],
        );
        return $customer;
    }
    public function mr2app_get_user_meta($user){
        $user = get_user_by('id' , $user);
        //return $user;
        $args = array(
            'post_type'   => 'woo2app_register',
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $the_query = get_posts( $args );
        $array = array();
        $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' );
        $array['user_login'] = $user->user_login;
        $array['user_email'] = $user->user_email;
        $array['user_url'] = $user->user_url;
        $array['display_name'] = $user->display_name;
        foreach ($the_query as $f){
            if(in_array($f->post_content ,$default_fields)){
                continue;
            }
            else{
                $array[$f->post_content] = get_user_meta($user->ID,$f->post_content , true);
            }
        }
        return $array;
    }
//ویرایش کاربر :
//ورودی ----> پست
//ورودی فیلدهای زیر
//ID (ضروری)
//user_pass (ضروری)
//و هر فیلدی که نیاز به ویرایش دارد
//
//http://shop.wp2app.ir/mr2app/user_edit
    public  function user_edit(){
        header('Content-Type: application/json; charset=utf-8');
        $result = array();
        $result['status'] = false;
        $result['user_id'] = "";
        $result['msg'] = "";
        if(isset($_POST['in'])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = json_decode($url_json);
            $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' ,'first_name' , 'last_name' , 'description' );
            $edit_customer_data['ID'] = $json->ID;
            (isset($json->first_name)) ? $edit_customer_data['first_name'] = $json->first_name : '';
            (isset($json->last_name)) ? $edit_customer_data['last_name'] = $json->last_name : '';
            (isset($json->description)) ? $edit_customer_data['description'] = $json->description : '';
            (isset($json->user_url)) ? $edit_customer_data['user_url'] = $json->user_url : '';
            (isset($json->display_name)) ? $edit_customer_data['display_name'] = $json->display_name : '';
            $user = get_user_by( 'id', $json->ID );
            if ( $user &&
                (
                    wp_check_password( $json->user_pass, $user->data->user_pass, $user->ID )
                    || get_user_meta($user->ID , 'gid' , true) == $json->user_pass
                )
            )
            {
                $user = wp_update_user( $edit_customer_data );
                if($user->errors){
                    foreach ($user->errors as $key ) {
                        $result['status'] = false;
                        $result['user_id'] = '';
                        $result['msg'] = $key[0];
                    }
                }
                elseif($user){
                    foreach ($json as $key => $value){
                        if(in_array($key ,$default_fields)){
                            continue;
                        }
                        update_user_meta($user,$key,$value);
                    }
                    $result['status'] = true;
                    $result['user_id'] = $user;
                    $result['msg'] = 'مشخصات شما ویرایش شد.';
                    $result['customer'] = $this->mr2app_get_customer($user);
                    $result['meta'] = $this->mr2app_get_user_meta($user);
                }
                else{
                    $result['status'] = false;
                    $result['user_id'] = '';
                    $result['user_id'] = 'مشکلی رخ داده است.';
                }
            }
            else{
                $result['status'] = false;
                $result['user_id'] = '';
                $result['user_id'] = 'رمز عبور یا نام کاربری نامعتبر می باشد..';
            }
        }
        $array = array();
        $array['result'] = $result;
        echo json_encode($array);
        return;
    }
    /*
     * /http://localhost/wordpress/mr2app/get_register_form
     */
    public function get_register_form(){
        header('Content-Type: application/json; charset=utf-8');
        $args      = array(
            'post_type'   => 'woo2app_register',
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $the_query = get_posts( $args );
        $array = array();
        foreach ($the_query as $f){
            $array['register_form'][] = array(
                'title' => $f->post_title,
                'name' => $f->post_content,
                'default' => get_post_meta($f->ID , 'default',true),
                'required' => get_post_meta($f->ID , 'required',true),
                'active' => get_post_meta($f->ID , 'active',true),
                'display_register' => get_post_meta($f->ID , 'display_register',true),
                'display_edit' => get_post_meta($f->ID , 'display_edit',true),
                'order' => $f->menu_order,
                'values' => (get_post_meta($f->ID , 'type',true) == 'list' || get_post_meta($f->ID , 'type',true) == 'radio_button') ? explode(',',get_post_meta($f->ID , 'values',true)): array(),
                'type' => get_post_meta($f->ID , 'type',true),
                'validate' => get_post_meta($f->ID , 'validate',true) ? get_post_meta($f->ID , 'validate',true) : 'general',
            );
        }
        $sms =  get_option('mr2app_sms');
        $array['mr2app_sms']['enable'] = $sms['enable'];
        $array['mr2app_sms']['field'] = $sms['field'];
        echo json_encode($array);
        return;
    }
    /*
     * http://localhost/wordpress/mr2app/generate_password/?in={"username":"hamid"}
     */
    public  function generate_password(){
        header('Content-Type: application/json; charset=utf-8');
        $result = array();
        $result['status'] = false;
        $result['password'] = "";
        if(isset($_GET['in'])){
            $in = $_GET['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = json_decode($url_json);
            $user = $json->username;
            $s = 0;
            if(get_user_by( 'login', $user ) == true){
                $user = get_user_by( 'login', $user );
                $s = 1;
            }
            elseif(get_user_by( 'email', $user ) == true ){
                $user = get_user_by( 'email', $user );
                $s = 1;
            }
            else{
                $result['msg'] = " نام کاربری وجود ندارد.";
            }
            if($s == 1){
                $password =  rand(1000000,9999999);
                $to = array();
                //var_dump($this->get_to($user));
                $to [] = $this->get_to($user);
                $msg = get_option('blogname');
                $msg .= "\n";
                $msg .= 'رمز عبور جدید :';
                $msg .= "\n";
                $msg .= $password;
                $func = $this->get_detail_sms();
                $func = $func['panel'];
                $x = $this->$func($to,$msg);
                if($x){
                    wp_set_password( $password, $user->ID );
                    $result['status'] = true;
                    $result['msg'] = " رمز عبور شما تغییر کرد و پیامک شد.";
                }
                else{
                    $result['status'] = false;
                    $result['password'] = '' ;
                    $result['msg'] = "ارسال پیامک با مشکل روبرو شد.";
                }
            }
        }
        $array = array();
        $array['result'] = $result;
        echo json_encode($array);
        return;
    }
    /*
     * Send Sms
     * url => example.com/mr2app/register_sms
     * smaple input  example.com/mr2app/register_sms?in={"mobile":"0915XXXXXXX"}
     * output {"result":{"status":true,"rand":XXXX}} OR {"result":{"status":false,"rand":""}}
     * method type => POST
     */
    public function send_sms(){
        header('Content-Type: application/json; charset=utf-8');

        $result = array();
        $result['status'] = false;
        $result['rand'] = "";
        if(isset($_POST['in'])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = json_decode($url_json);
            if(get_user_by( 'login', $json->mobile ) == true || get_user_by( 'email', $json->mobile ) == true){
                $result['status'] = false;
                $result['error'] = -1;
                $result['msg'] = 'کاربر از قبل وجود دارد.';
            }
            else{
                $rand = rand(1000,9999);
                $msg = "کد فعالسازی : ";
                $msg .= "\n";
                $msg .= "$rand";
                $msg .= "\n";
                $msg .= get_option('blogname');
                if(isset($json->mobile)){
                    $to = array();
                    $to [] = $json->mobile;
                    //$to  = $json->mobile;
                    $func = $this->get_detail_sms();
                    $func = $func['panel'];
                    $r = $this->$func($to,$msg);
                    //var_dump($r);return;
                    $result = array();
                    if($r){
                        $result['status'] = true;
                        $result['rand'] = $rand;
                    }
                }
            }
        }
        $array = array();
        $array['result'] = $result;
        echo json_encode($array);
        return;
    }
    function parsgreen( $to , $msg ) {
        $setting_sms = get_option('mr2app_sms');
        if(!$setting_sms){
            return 0;
        }
        $username = $setting_sms['username'];
        $password = $setting_sms['password'];
        $from = $setting_sms['number'];
        $response = false;
        //  $password = $this->password['SMS'];
        if ( empty( $username ) ) {
            return $response;
        }
        try {
            $parameters['signature'] = $username;
            $parameters['from']      = $from;
            $parameters['to']        = $to;
            $parameters['text']      = $msg;
            $parameters['isFlash']   = false;
            $parameters['udh']       = "";
            $parameters['retStr']    = array( 0 );
            $parameters['success']   = 0;
            $client                  = new SoapClient( "http://login.parsgreen.com/Api/SendSMS.asmx?wsdl" );
            $status                  = (array) $client->SendGroupSMS( $parameters )->SendGroupSMSResult;
        } catch ( SoapFault $ex ) {
            $sms_response = $ex->faultstring;
        }
        if ( empty( $sms_response ) && $status[0] == 1 ) {
            $response = true;
        }
        return $response;
    }
    public function farazsms($to , $msg) {
        $setting_sms = get_option('mr2app_sms');
        if(!$setting_sms){
            return 0;
        }
        $username = $setting_sms['username'];
        $password = $setting_sms['password'];
        $from = $setting_sms['number'];
        $response = false;
        $massage  = $msg;
        if ( empty( $username ) || empty( $password ) ) {
            return $response;
        }
        $url = "37.130.202.188/services.jspd";
        $rcpt_nm = $to;
        $param = array
        (
            'uname'=> $username ,
            'pass'=> $password,
            'from'=> $from,
            'message'=> "$massage",
            'to'=>json_encode($rcpt_nm),
            'op'=>'send'
        );
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];
        return $res_data;
        //return;
    }
    public function payamresan($to , $msg) {
        $setting_sms = get_option('mr2app_sms');
        if(!$setting_sms){
            return 0;
        }
        $username = $setting_sms['username'];
        $password = $setting_sms['password'];
        $from = $setting_sms['number'];
        $response = false;
        $massage  = $msg;
        if ( empty( $username ) || empty( $password ) ) {
            return $response;
        }
        $to = implode( ',', $to );
        $content = 'http://www.payam-resan.com/APISend.aspx?UserName=' . rawurlencode( $username ) .
            '&Password=' . rawurlencode( $password ) .
            '&To=' . rawurlencode( $to ) .
            '&From=' . rawurlencode( $from ) .
            '&Text=' . rawurlencode( $massage );
        if ( extension_loaded( 'curl' ) ) {
            $curlSession = curl_init();
            curl_setopt( $curlSession, CURLOPT_URL, $content );
            curl_setopt( $curlSession, CURLOPT_BINARYTRANSFER, true );
            curl_setopt( $curlSession, CURLOPT_RETURNTRANSFER, true );
            $payamresan_response = curl_exec( $curlSession );
            curl_close( $curlSession );
        } else {
            $payamresan_response = file_get_contents( $content );
        }
        if ( strtolower( $payamresan_response ) == '1' || $payamresan_response == 1 ) {
            $response = true;
        }
        return  $response;
        //return;
    }
    public function sms_mida_co($to , $msg){
        ini_set('soap.wsdl_cache_enabled', false);
        $client = new SoapClient("http://sms.mida-co.ir/webservice/soap/xml.php?wsdl");
        $user = 3681;
        $pass = 'hamedio4oio4o';
        $num = "5000201004";
        $to_s = "";
        foreach ($to as $t){
            $to_s .= "<recipient>$t</recipient>";
        }
        $param=array('requestData'=>'<xmsrequest>
<userid>'.$user.'</userid>
<password>'.$pass.'</password>
<action>smssend</action>
<body>
<type>otm</type>
<message originator="'.$num.'">'. $msg .'</message>'. "$to_s".'</body></xmsrequest>');
        $result = $client->XmsRequest($param);
//echo $result->XmsRequestResult;
        if($result->XmsRequestResult == '3' || $result->XmsRequestResult == '2' || $result->XmsRequestResult== '1' ){
            //echo 0;
            return 0;
        }
        else{
            //echo 1;
            return 1;
        }
    }
    public function melipayamak($to , $msg){
        ini_set("soap.wsdl_cache_enabled", "0");
        $setting_sms = get_option('mr2app_sms');
        if(!$setting_sms){
            return 0;
        }
        $username = $setting_sms['username'];
        $password = $setting_sms['password'];
        $from = $setting_sms['number'];
        try {
            $client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding'=>'UTF-8'));
            $parameters['username'] = $username;
            $parameters['password'] = $password;
            $parameters['from'] = $from;
            $parameters['to'] = $to;
            $parameters['text'] = $msg;
            $parameters['isflash'] = false;
            $parameters['udh'] = "";
            $parameters['recId'] = array(0);
            $parameters['status'] = 0x0;
            //$client->GetCredit(array("username"=>"wsdemo","password"=>"wsdemo"))->GetCreditResult;
            return $client->SendSms($parameters)->SendSmsResult;
        } catch (SoapFault $ex) {
            return $ex->faultstring;
        }
    }
    public function rangine($to , $msg){
        $setting_sms = get_option('mr2app_sms');
        if(!$setting_sms){
            return 0;
        }
        $username = $setting_sms['username'];
        $password = $setting_sms['password'];
        $from = $setting_sms['number'];
        $url = "37.130.202.188/services.jspd";
        $rcpt_nm = $to;
        $param = array
        (
            'uname'=> $username,
            'pass'=> $password,
            'from'=> $from,
            'message'=> $msg,
            'to'=>json_encode($rcpt_nm),
            'op'=>'send'
        );
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];
        if($res_code == 0) return 1;
    }
    protected function get_detail_sms(){
        return get_option('mr2app_sms');
    }
    protected function get_to($user){
        $sms = $this->get_detail_sms();
        $filed_for_to = $sms['field'];
        $default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' );
        if(in_array($filed_for_to , $default_fields)){
            return $user->$filed_for_to;
        }
        else{
            return get_user_meta($user->ID,$filed_for_to,true);
        }
    }
}