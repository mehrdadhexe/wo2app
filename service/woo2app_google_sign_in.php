<?php
if (!defined( 'ABSPATH' )) exit;
class woo2app_google_sign_in {
	public function __construct() {
		add_action( 'init', array( $this, 'woo2app_api_regular_url' ) );
		add_filter( 'query_vars', array( $this, 'woo2app_api_query_vars' ) );
		add_action( 'parse_request', array( $this, 'woo2app_api_parse_request' ) );
	}
	function woo2app_api_regular_url() {
		add_rewrite_rule( '', 'index.php?woo2app_google_sign_in', 'top' );
	}

	function woo2app_api_query_vars( $query_vars ) {
		$query_vars[] = 'woo2app_google_sign_in';
		return $query_vars;
	}
	function woo2app_api_parse_request( &$wp ) {
		if ( array_key_exists( 'woo2app_google_sign_in', $wp->query_vars ) ) {
			$this->woo2app_google_sign_in();
			exit();
		}
	}

	public function woo2app_google_sign_in(){
		header('Content-Type: application/json; charset=utf-8');
		//https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=
		$result = array();
		$result['error'] = -2;
		$result['msg'] = "سرویس اجرا نشد.";
		$result['customer'] = "";
		if(isset($_POST['in'])) {
			$in        = $_POST["in"];
			$slashless = stripcslashes( $in );
			$url_json  = urldecode( $slashless );
			$json      = (array) json_decode( $url_json );
			$token      = $json["id_token"];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$token);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($ch);
			curl_close($ch);
			//$response->sub; // email id = password
			//$response->email; // email = username & email
			//$response->given_name; //
			//$response->family_name; //
			$response = json_decode($response);
			$new_registered = -1;
			$result['msg'] = $response->email;
			if(isset($response->email) && isset($response->sub)){
				$result['msg'] = "is email.";
				if($user_id = $this->registerd($response->email,$response->sub)){
					$new_registered = 0;
				}
				elseif($user_id = $this->register($response)){
					$new_registered = 1;
				}
				if($new_registered != -1){
					$result['error'] = 1;
					$result['msg'] = 'ورود با موفقیت انجام شد.';
					$result['customer'] = $this->mr2app_get_customer( $user_id);
					$result['meta'] = $this->mr2app_get_user_meta($user_id);
					$result['new_registered'] = $new_registered;
				}
			}
		}
		echo json_encode($result);
		return;
	}
	public function registerd($email,$gid){
		if(get_user_by( 'login', $email ) == true){
			$user = get_user_by( 'login', $email );
			update_user_meta($user->ID,'gid',$gid);
			return $user->ID;
		}
		elseif(get_user_by( 'email', $email ) == true ){
			$user = get_user_by( 'email', $email );
			update_user_meta($user->ID,'gid',$gid);
			return $user->ID;
		}
		return 0;
	}
	public function register($response){
		$new_customer_data =  array(
			'user_login' => $response->email,
			'first_name'  => $response->given_name,
			'last_name'  => $response->family_name,
			'user_email' => $response->email,
			'user_pass' => $response->sub,
			'role'       => 'customer',
		);
		$user = wp_insert_user( $new_customer_data );
		add_user_meta($user,'gid',$response->sub);
		if($user->errors){
			return 0;
		}
		else{
			return $user;
		}
		return 0;
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
		);
		return $customer;
	}
	public function mr2app_get_user_meta($user){
		$user = get_user_by('id' , $user);
		//return $user;

		$array = array();
		$default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' );
		$array['user_login'] = $user->user_login;
		$array['user_email'] = $user->user_email;
		$array['user_url'] = $user->user_url;
		$array['display_name'] = $user->display_name;
		foreach (get_user_meta($user->ID) as $key => $val ){
			if(in_array( $key ,$default_fields)){
				continue;
			}
			$array[$key] = $val[0];
		}
		return $array;
	}
}
