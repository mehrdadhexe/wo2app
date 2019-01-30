<?php
class woocommerce_services_customer{
	public function __construct(){
		
		//hami cats
		  add_action( 'init', array( $this , 'webservice_hami_all' ));
		  add_filter( 'query_vars', array( $this , 'wp_hami_all_query_vars' ));
		  add_action( 'parse_request', array( $this , 'wp_hami_all_parse_request' ));
		//end hami cats
		
	}
	
				
	function webservice_hami_all(){
	  //  add_rewrite_rule( 'my-api.php$', 'index.php?customer_create', 'top' );
	  //  add_rewrite_rule( 'my-api.php$', 'index.php?customer_update', 'top' );
	    add_rewrite_rule( 'my-api.php$', 'index.php?app_slider', 'top' );
	    add_rewrite_rule( 'my-api.php$', 'index.php?app_mainpage', 'top' );
	}
	function wp_hami_all_query_vars( $query_vars ){
	   // $query_vars[] = 'customer_create';
	   // $query_vars[] = 'customer_update';
	    $query_vars[] = 'app_slider';
	    $query_vars[] = 'app_mainpage';
	    return $query_vars;
	}
	function wp_hami_all_parse_request( &$wp ){
/*
	    if ( array_key_exists( 'customer_create', $wp->query_vars ) ) {
	        $this->customer_create_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'customer_update', $wp->query_vars ) ) {
	        $this->customer_update_webservice();
	        exit();
	    }
*/
	    if ( array_key_exists( 'app_slider', $wp->query_vars ) ) {
	        $this->app_slider_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'app_mainpage', $wp->query_vars ) ) {
	        $this->app_mainpage_webservice();
	        exit();
	    }
	    return;
	}
	function customer_create_webservice(){
		$in = $_POST['in'];
		$slashless = stripcslashes($in);
		$url_json = urldecode($slashless);
		$json = (array)  json_decode($slashless);
		
		$email = $json[email];
		$first_name = $json[first_name];
		$last_name = $json[last_name];
		$username = $json[username];
		$password = $json[password];
		$bill_first_name = $json[billing_address]->first_name;
		$bill_last_name = $json[billing_address]->last_name;
		$bill_address_1 = $json[billing_address]->address_1;
		$bill_city = $json[billing_address]->city;
		$bill_state = $json[billing_address]->state;
		$bill_postcode = $json[billing_address]->postcode;
		$bill_email = $json[billing_address]->email;
		$bill_phone = $json[billing_address]->phone;
		$ship_first_name = $json[shipping_address]->first_name;
		$ship_last_name = $json[shipping_address]->last_name;
		$ship_address_1 = $json[shipping_address]->address_1;
		$ship_city = $json[shipping_address]->city;
		$ship_state = $json[shipping_address]->state;
		$ship_postcode = $json[shipping_address]->postcode;
		//header('Content-Type: application/json; charset=utf-8');
	    $options = array(
			'debug'           => true,
			'return_as_array' => false,
			'validate_url'    => false,
			'timeout'         => 30,
			'ssl_verify'      => false,
		);
		try {
			global $wpdb;
			$table_name = $wpdb->prefix . "woo2app_setting";
			$rec = $wpdb->get_results("select * from $table_name where st_name = 'cs_key' OR st_name = 'ck_key' ");
			foreach ($rec as $key) {
				if($key->st_name == "ck_key") $ck = $key->st_value; 
				if($key->st_name == "cs_key") $cs = $key->st_value; 
			}
			$site = get_site_url();
			$client = new WC_API_Client( $site , $ck, $cs, $options );
			$data_customer = array(
				        'email' => $email,
				        'first_name' => $first_name,
				        'last_name' => $last_name,
				        'username' => $username,
				        'password' => $password,
				        'billing_address' => array(
				            'first_name' => $bill_first_name,
				            'last_name' => $bill_last_name,
				            'address_1' => $bill_address_1,
				            'city' => $bill_city,
				            'state' => $bill_state,
				            'postcode' => $bill_postcode,
				            'email' => $bill_email,
				            'phone' => $bill_phone
				        ),
				        'shipping_address' => array(
				            'first_name' => $ship_first_name,
				            'last_name' => $ship_last_name,
				            'address_1' => $ship_address_1,
				            'city' => $ship_city,
				            'state' => $ship_state,
				            'postcode' => $ship_postcode,
				        )
				    );
				
			$customer = $client->customers->create($data_customer);
			unset($customer->http);
			$json = json_encode( $customer);
			echo $json;
		} catch ( WC_API_Client_Exception $e ) {
			echo $e->getMessage() . PHP_EOL;
			echo $e->getCode() . PHP_EOL;
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				print_r( $e->get_request() );
				print_r( $e->get_response() );
			}
		}
	}
	function customer_update_webservice(){
		$in = $_POST['in'];
		$slashless = stripcslashes($in);
		$url_json = urldecode($slashless);
		$json = (array)  json_decode($url_json);
		$id = $json[id];
		$email = $json[email];
		$first_name = $json[first_name];
		$last_name = $json[last_name];
		$bill_first_name = $json[billing_address]->first_name;
		$bill_last_name = $json[billing_address]->last_name;
		$bill_address_1 = $json[billing_address]->address_1;
		$bill_city = $json[billing_address]->city;
		$bill_state = $json[billing_address]->state;
		$bill_postcode = $json[billing_address]->postcode;
		$bill_email = $json[billing_address]->email;
		$bill_phone = $json[billing_address]->phone;
		$ship_first_name = $json[shipping_address]->first_name;
		$ship_last_name = $json[shipping_address]->last_name;
		$ship_address_1 = $json[shipping_address]->address_1;
		$ship_city = $json[shipping_address]->city;
		$ship_state = $json[shipping_address]->state;
		$ship_postcode = $json[shipping_address]->postcode;
		//header('Content-Type: application/json; charset=utf-8');
	    $options = array(
			'debug'           => true,
			'return_as_array' => false,
			'validate_url'    => false,
			'timeout'         => 30,
			'ssl_verify'      => false,
		);
		try {
			global $wpdb;
			$table_name = $wpdb->prefix . "woo2app_setting";
			$rec = $wpdb->get_results("select * from $table_name where st_name = 'cs_key' OR st_name = 'ck_key' ");
			foreach ($rec as $key) {
				if($key->st_name == "ck_key") $ck = $key->st_value; 
				if($key->st_name == "cs_key") $cs = $key->st_value; 
			}
			$site = get_site_url();
			$client = new WC_API_Client( $site , $ck, $cs, $options );
			$data_customer = array(
				        'email' => $email,
				        'first_name' => $first_name,
				        'last_name' => $last_name,
				        'billing_address' => array(
				            'first_name' => $bill_first_name,
				            'last_name' => $bill_last_name,
				            'address_1' => $bill_address_1,
				            'city' => $bill_city,
				            'state' => $bill_state,
				            'postcode' => $bill_postcode,
				            'email' => $bill_email,
				            'phone' => $bill_phone
				        ),
				        'shipping_address' => array(
				            'first_name' => $ship_first_name,
				            'last_name' => $ship_last_name,
				            'address_1' => $ship_address_1,
				            'city' => $ship_city,
				            'state' => $ship_state,
				            'postcode' => $ship_postcode,
				        )
				    );
				
			//var_dump($data_customer);
			$customer = $client->customers->update( $id , $data_customer );
			//$customer = $client->customers->get();
			unset($customer->http);
			$json = json_encode( $customer );
			echo $json;
		} catch ( WC_API_Client_Exception $e ) {
			echo $e->getMessage() . PHP_EOL;
			echo $e->getCode() . PHP_EOL;
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				print_r( $e->get_request() );
				print_r( $e->get_response() );
			}
		}
	}
	function app_slider_webservice(){
		header('Content-Type: application/json; charset=utf-8');
	    
		global $wpdb;
		$table_name = $wpdb->prefix . "woo2app_slider";
		$rec = $wpdb->get_results("select * from $table_name");
		foreach ($rec as $key ) {
			$array[] = array(
							"sl_title" => $key->sl_title,
							"sl_type" => $key->sl_type,
							"sl_value" => $key->sl_value,
							"sl_pic" => $key->sl_pic
							);
		}
		echo json_encode($array);
			
	}
	function app_mainpage_webservice(){
		header('Content-Type: application/json; charset=utf-8');
	    
		global $wpdb;
		$table_name = $wpdb->prefix . "woo2app_mainpage";
		$rec = $wpdb->get_results("select * from $table_name");
		foreach ($rec as $key ) {
if ($key->mp_showtype == 4) {
				$slashless = stripcslashes($key->mp_value);
}else{
				$slashless = $key->mp_value;
}
			$array[] = array(
							"mp_title" => $key->mp_title,
							"mp_type" => $key->mp_type,
							"mp_value" => $slashless,
							"mp_showtype" => $key->mp_showtype,
							"mp_pic" => $key->mp_pic,
							"mp_order" => $key->mp_order
 							);
		}
		echo json_encode($array);
			
	}
	
}