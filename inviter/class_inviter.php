<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/21/2018
 * Time: 4:51 PM
 */
class class_inviter {
    function __construct() {
        add_action( 'init', array( $this, 'mr2app_inviter_api_regular_url' ) );
        add_filter( 'query_vars', array( $this, 'mr2app_inviter_api_query_vars' ) );
        add_action( 'parse_request', array( $this, 'mr2app_inviter_api_parse_request' ) );
    }
    function mr2app_inviter_api_regular_url() {
        add_rewrite_rule( '^mr2app/make_icode$', 'index.php?make_icode=$matches[1]', 'top' ); //=$matches[1]
        add_rewrite_rule( '^mr2app/apply_icode$', 'index.php?apply_icode=$matches[1]', 'top' ); //=$matches[1]
        add_rewrite_rule( '^mr2app/get_icode$', 'index.php?get_icode=$matches[1]', 'top' ); //=$matches[1]
        flush_rewrite_rules();
    }
    function mr2app_inviter_api_query_vars( $query_vars ) {
        $query_vars[] = 'make_icode';
        $query_vars[] = 'apply_icode';
        $query_vars[] = 'get_icode';
        return $query_vars;
    }
    function mr2app_inviter_api_parse_request( &$wp ) {
        if ( array_key_exists( 'make_icode', $wp->query_vars ) ) {
            $this->make_icode();
            exit();
        }
        if ( array_key_exists( 'apply_icode', $wp->query_vars ) ) {
            $this->apply_icode();
            exit();
        }
        if ( array_key_exists( 'get_icode', $wp->query_vars ) ) {
            $this->get_icode();
            exit();
        }
        return;
    }

    public function make_icode() {
        header('Content-Type: application/json; charset=utf-8');
        $result = array(
            'status' => false,
            'code' => -1,
            'msg' => 'سرویس با خطا مواجه شده است .'
        );
        if(isset($_POST['in'])) {
            $result = array(
                'status' => false,
                'code' => -2,
                'msg' => 'کاربری با این اطلاعات وجود ندارد.'
            );
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            $user = get_user_by('id' , $json['uid']);
            if($user){
                $result = array(
                    'status' => false,
                    'code' => -3,
                    'msg' => 'کد معرف از قبل وجود دارد.',
                    'invite_code' => get_user_meta($user->ID , 'my_invitecode' , true)
                );
                $meta_invite_code = get_user_meta($user->ID,'my_invitecode',true);
                if(!$meta_invite_code){
                    $code = $this->make_code($user->user_login);
                    update_user_meta($user->ID,'my_invitecode' , $code);
                    $result = array(
                        'status' => true,
                        'code' => 1,
                        'msg' => 'کد معرف با موفقیت ایجاد شد.'
                    );
                }
            }
        }
        echo json_encode($result);
    }

    public function make_code($username){
        $code = '';
        $setting_inviter  = get_option('woo2app_inviter_setting');
        $type_code = $setting_inviter['type_make_code'];
        if($type_code == 'username') $code = $username;
        if($type_code == 'random_digit') {
            $code = rand(1000000,9999999);
            if($this->check_code($code)){
                $this->make_code($username);
            }
        }
        if($type_code == 'random_alphabet') {
            $code = $this->generateRandomString(7);
            if($this->check_code($code)){
                $this->make_code($username);
            }
        }

        return $code;
    }
    public function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected function check_code($meta_value){
        $user_query = new WP_User_Query(
            array(
                'meta_key'	  =>	'my_invitecode',
                'meta_value'	=>	$meta_value
            )
        );

        // Get the results from the query, returning the first user
        $users = $user_query->get_results();

        return $users[0];
    }

    public function apply_icode(){
        header('Content-Type: application/json; charset=utf-8');
        $result = array(
            'status' => false,
            'code' => -1,
            'msg' => 'سرویس با خطا مواجه شده است .'
        );
        if(isset($_POST['in'])) {
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            $result = array(
                'status' => false,
                'code' => -5,
                'msg' => 'کد معرف وجود ندارد.'
            );
            if( $invitor = $this->check_code($json['invitor']) && $json['invitor'] != ""){

                $result = array(
                    'status' => false,
                    'code' => -2,
                    'msg' => 'کاربری با این اطلاعات وجود ندارد.'
                );
                $user = get_user_by('login' , $json['username']);
                if($user){
                    $result = array(
                        'status' => false,
                        'code' => -3,
                        'msg' => 'رمز عبور اشتباه می باشد.'
                    );
                    $pass = wp_check_password( $json['password'], $user->data->user_pass, $user->ID );
                    if($pass){
                        $result = array(
                            'status' => false,
                            'code' => -4,
                            'msg' => 'کد معرف قبلا ذخیره شده است.'
                        );
                        $check = get_user_meta($user->ID , 'my_invitor',true);
                        if(!$check){
                            update_user_meta($user->ID , 'my_invitor' , $json['invitor']);
                            $this->set_score_invitor($json['invitor']  , $user->ID);
                            $result = array(
                                'status' => true,
                                'code' => 1,
                                'msg' => 'کد معرف ثبت شد.'
                            );
                        }
                    }
                }
            }
        }

        echo json_encode($result);
    }

    protected function set_score_invitor($invitor , $ID){
        $user = $this->check_code($invitor);
        $setting_inviter  = get_option('woo2app_inviter_setting');
        $counter = $setting_inviter['score_value_for_inviter'];
        $type = $setting_inviter['score_type'];
        if($type == 1){
            $score = get_user_meta($user->ID,'my_score', true);
            $score = (int)$score + (int)$counter;
            update_user_meta($user->ID , 'my_score', $score);
        }
        else if($type == 2){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            if ( is_plugin_active( 'woo-wallet/woo-wallet.php' ) ) {
                //plugin is activated
                woo_wallet()->wallet->credit($user->ID, (int)$counter, 'شارژ هدیه بابت کد معرف : ' . $ID);
            }
        }

    }

    public function get_icode(){
        header('Content-Type: application/json; charset=utf-8');
        $result = array(
            'status' => false,
            'code' => -1,
            'msg' => 'سرویس با خطا مواجه شده است .'
        );
        if(isset($_POST['in'])) {
            $result = array(
                'status' => false,
                'code' => -2,
                'msg' => 'کاربری با این اطلاعات وجود ندارد.'
            );
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            $user = get_user_by('id' , $json['uid']);
            if($user){
                $my_invitecode = get_user_meta($user->ID,'my_invitecode',true);
                if($my_invitecode == "" || $my_invitecode == null){
                    $my_invitecode =  $this->make_code($user->user_login);
                    update_user_meta($user->ID,'my_invitecode' , $my_invitecode);
                }
                $my_score = get_user_meta($user->ID,'my_score',true);
                $result = array(
                    'status' => true,
                    'code' => 1,
                    'my_invitecode' => $my_invitecode,
                    'my_score' => (int)$my_score
                );
            }
        }
        echo json_encode($result);
    }

}