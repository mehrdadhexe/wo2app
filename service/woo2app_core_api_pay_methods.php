<?php
if (!defined( 'ABSPATH' )) exit;
class woo2app_core_api_pay_methods {
    public function __construct(){
        add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
        add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
        add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
    }
    function woo2app_api_regular_url(){
        add_rewrite_rule('', 'index.php?GET_pay_methods', 'top');
    }
    function woo2app_api_query_vars($query_vars){
        $query_vars[] = 'GET_pay_methods';
        return $query_vars;
    }
    function woo2app_api_parse_request(&$wp){
        if ( array_key_exists( 'GET_pay_methods' , $wp->query_vars ) ) {
            $this->woo2app_GET_pay_methods();
            exit();
        }
        return;
    }
    function woo2app_GET_pay_methods(){
        if(isset($_POST['in'])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            $package = array();
            $package['contents'] = array();
            $entity_body = $json;
            if($json['customer_id'] != '-1'){
                wp_logout();
                $user = array(
                    'user_login'    => $json['username'],
                    'user_password' => $json['password'],
                    'remember'      => false
                );
                // login user to browser ...
                $user = get_user_by('login' , $json['username']);
                if($user){
                    wp_set_current_user($user->ID,$json['username']);

                }
            }

            $line_items = $entity_body["items"];

            WC()->cart->empty_cart();

            foreach ($line_items as $line_item) {
                wc()->cart->add_to_cart( $line_item->product_id , $line_item->quantity , $line_item->variation_id  );
            }
            if(isset($json['coupon_lines'])){
                WC()->cart->add_discount( $json['coupon_lines'][0]->code);
            }
            $p = new WC_Payment_Gateways();
            $payment_methods = $p->get_available_payment_gateways();
            $array = array();
            foreach ($payment_methods as $key => $value) {
                $array[] = $value;
            }
            $payment_methods = $array;
            wp_send_json(array('currency' => get_option('woocommerce_currency'), 'payment_methods' => $payment_methods));
        }

    }
}