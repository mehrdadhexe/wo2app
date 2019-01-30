<?php
if (!defined( 'ABSPATH' )) exit;
class woocommerce_services_customer_new
{
	function __construct()
	{
		  add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
		  add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
		  add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
	}
	function woo2app_api_regular_url(){
		add_rewrite_rule( 'my-api.php$', 'index.php?customer_create', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?customer_update', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_user_orders', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?app_slider', 'top' );
	    add_rewrite_rule( 'my-api.php$', 'index.php?app_mainpage', 'top' );
	}
	function woo2app_api_query_vars($query_vars){
		$query_vars[] = 'customer_create';
		$query_vars[] = 'customer_update';
		$query_vars[] = 'woo2app_user_orders';
		$query_vars[] = 'app_slider';
	    $query_vars[] = 'app_mainpage';
		return $query_vars;
	}
	function woo2app_api_parse_request(&$wp){
		if ( array_key_exists( 'customer_create', $wp->query_vars ) ) {
	        $this->woocats_customer_create_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'customer_update', $wp->query_vars ) ) {
	        $this->woocats_customer_update_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'woo2app_user_orders', $wp->query_vars ) ) {
	        $this->woocats_customer_orders_webservice();
	        exit();
	    }
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
	function woocats_customer_create_webservice(){
		ob_start();
		$in = $_POST['in'];
		$slashless = stripcslashes($in);
		$url_json = urldecode($slashless);
		$json = (array)  json_decode($url_json);
		
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
		$data_customer = array( "customer" => array(
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
		        )
	    );
	    $array = $this->create_customer( $data_customer);
	    ob_clean();
	    echo json_encode($array);
	}
	function woocats_customer_update_webservice(){
		ob_start();
		$in = $_POST['in'];
		$slashless = stripcslashes($in);
		$url_json = urldecode($slashless);
		$json = (array)  json_decode($url_json);
		$err =  array( "id" => "10" , "email" => "peyman130@gmail.com" , "password" => "123456" , "first_name" => "asghar" , 
					"last_name" => "farhadi" , "address" => "shom" , "city" => "bal" , "state" => "khar" );
		echo json_encode($err);
		$id = $json[id];
		$email = $json[email];
		$pass = $json[password];
		$first_name = $json[first_name];
		$last_name = $json[last_name];
		//$bill_first_name = $json[billing_address]->first_name;
		//$bill_last_name = $json[billing_address]->last_name;
		$address = $json[address];
		$city = $json[city];
		$state = $json[state];
		//$bill_postcode = $json[billing_address]->postcode;
		//$bill_email = $json[billing_address]->email;
		$phone = $json[phone];
		//$ship_first_name = $json[shipping_address]->first_name;
		//$ship_last_name = $json[shipping_address]->last_name;
		//$ship_address_1 = $json[shipping_address]->address_1;
		//$ship_city = $json[shipping_address]->city;
		//$ship_state = $json[shipping_address]->state;
		//$ship_postcode = $json[shipping_address]->postcode;
		$data_customer = array( "customer" => array(
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
			        )
	    );
		$user = get_user_by( 'id', $id );
		if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ){
			ob_clean();
			echo 1;
			//$array = $this->edit_customer( $id , $data_customer );
			//echo json_encode($array);	
		}else{
			ob_clean();
			echo 0;
		}
		
	}
	function woocats_customer_orders_webservice(){
		ob_start();
		if(isset($_GET["customer_id"])){
			$array = $this->get_customer_orders($_GET["customer_id"]);
			ob_clean();
			echo json_encode($array["orders"]);	
		}
	}
	public function create_customer( $data ) {
		
			if ( ! isset( $data['customer'] ) ) {
				//throw new WC_API_Exception( 'woocommerce_api_missing_customer_data', sprintf( __( 'No %1$s data specified to create %1$s', 'woocommerce' ), 'customer' ), 400 );
			}
			$data = $data['customer'];
			// Checks with can create new users.
			if ( ! current_user_can( 'create_users' ) ) {
				//throw new WC_API_Exception( 'woocommerce_api_user_cannot_create_customer', __( 'You do not have permission to create this customer', 'woocommerce' ), 401 );
			}
			$data = apply_filters( 'woocommerce_api_create_customer_data', $data, $this );
			// Checks with the email is missing.
			if ( ! isset( $data['email'] ) ) {
				//throw new WC_API_Exception( 'woocommerce_api_missing_customer_email', sprintf( __( 'Missing parameter %s', 'woocommerce' ), 'email' ), 400 );
			}
			// Sets the username.
			$data['username'] = ! empty( $data['username'] ) ? $data['username'] : '';
			// Sets the password.
			$data['password'] = ! empty( $data['password'] ) ? $data['password'] : '';
			// Attempts to create the new customer
			$id = wc_create_new_customer( $data['email'], $data['username'], $data['password'] );
			// Checks for an error in the customer creation.
			if ( is_wp_error( $id ) ) {
				//throw new WC_API_Exception( $id->get_error_code(), $id->get_error_message(), 400 );
			}
			// Added customer data.
			$this->update_customer_data( $id, $data );
			do_action( 'woocommerce_api_create_customer', $id, $data );
			//$this->send_status( 201 );
			return $this->get_customer( $id );
		
	}
	protected function update_customer_data( $id, $data ) {
		// Customer first name.
		if ( isset( $data['first_name'] ) ) {
			update_user_meta( $id, 'first_name', wc_clean( $data['first_name'] ) );
		}
		// Customer last name.
		if ( isset( $data['last_name'] ) ) {
			update_user_meta( $id, 'last_name', wc_clean( $data['last_name'] ) );
		}
		// Customer billing address.
		if ( isset( $data['billing_address'] ) ) {
			foreach ( $this->get_customer_billing_address() as $address ) {
				if ( isset( $data['billing_address'][ $address ] ) ) {
					update_user_meta( $id, 'billing_' . $address, wc_clean( $data['billing_address'][ $address ] ) );
				}
			}
		}
		// Customer shipping address.
		if ( isset( $data['shipping_address'] ) ) {
			foreach ( $this->get_customer_shipping_address() as $address ) {
				if ( isset( $data['shipping_address'][ $address ] ) ) {
					update_user_meta( $id, 'shipping_' . $address, wc_clean( $data['shipping_address'][ $address ] ) );
				}
			}
		}
		do_action( 'woocommerce_api_update_customer_data', $id, $data );
	}
	public function send_status( $code ) {
		status_header( $code );
	}
	public function get_customer( $id, $fields = null ) {
		global $wpdb;
		//$id = $this->validate_request( $id, 'customer', 'read' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		$customer = new WP_User( $id );
		// Get info about user's last order
		$last_order = $wpdb->get_row( "SELECT id, post_date_gmt
						FROM $wpdb->posts AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta on posts.ID = meta.post_id
						WHERE meta.meta_key = '_customer_user'
						AND   meta.meta_value = {$customer->ID}
						AND   posts.post_type = 'shop_order'
						AND   posts.post_status IN ( '" . implode( "','", array_keys( wc_get_order_statuses() ) ) . "' )
						ORDER BY posts.ID DESC
					" );
		$roles = array_values( $customer->roles );
		$customer_data = array(
			'id'               => $customer->ID,
			'created_at'       => $this->format_datetime( $customer->user_registered ),
			'last_update'      => $this->format_datetime( get_user_meta( $customer->ID, 'last_update', true ) ),
			'email'            => $customer->user_email,
			'first_name'       => $customer->first_name,
			'last_name'        => $customer->last_name,
			'username'         => $customer->user_login,
			'role'             => $roles[0],
			'last_order_id'    => is_object( $last_order ) ? $last_order->id : null,
			'last_order_date'  => is_object( $last_order ) ? $this->format_datetime( $last_order->post_date_gmt ) : null,
			'orders_count'     => wc_get_customer_order_count( $customer->ID ),
			'total_spent'      => wc_format_decimal( wc_get_customer_total_spent( $customer->ID ), 2 ),
			'avatar_url'       => $this->get_avatar_url( $customer->customer_email ),
			'billing_address'  => array(
				'first_name' => $customer->billing_first_name,
				'last_name'  => $customer->billing_last_name,
				'company'    => $customer->billing_company,
				'address_1'  => $customer->billing_address_1,
				'address_2'  => $customer->billing_address_2,
				'city'       => $customer->billing_city,
				'state'      => $customer->billing_state,
				'postcode'   => $customer->billing_postcode,
				'country'    => $customer->billing_country,
				'email'      => $customer->billing_email,
				'phone'      => $customer->billing_phone,
			),
			'shipping_address' => array(
				'first_name' => $customer->shipping_first_name,
				'last_name'  => $customer->shipping_last_name,
				'company'    => $customer->shipping_company,
				'address_1'  => $customer->shipping_address_1,
				'address_2'  => $customer->shipping_address_2,
				'city'       => $customer->shipping_city,
				'state'      => $customer->shipping_state,
				'postcode'   => $customer->shipping_postcode,
				'country'    => $customer->shipping_country,
			),
		);
		return array( 'customer' => apply_filters( 'woocommerce_api_customer_response', $customer_data, $customer, $fields, ""/*$this->server*/ ) );
	}
	protected function get_customer_billing_address() {
		$billing_address = apply_filters( 'woocommerce_api_customer_billing_address', array(
			'first_name',
			'last_name',
			'company',
			'address_1',
			'address_2',
			'city',
			'state',
			'postcode',
			'country',
			'email',
			'phone',
		) );
		return $billing_address;
	}
	protected function get_customer_shipping_address() {
		$shipping_address = apply_filters( 'woocommerce_api_customer_shipping_address', array(
			'first_name',
			'last_name',
			'company',
			'address_1',
			'address_2',
			'city',
			'state',
			'postcode',
			'country',
		) );
		return $shipping_address;
	}
	public function format_datetime( $timestamp, $convert_to_utc = false ) {
		if ( $convert_to_utc ) {
			$timezone = new DateTimeZone( wc_timezone_string() );
		} else {
			$timezone = new DateTimeZone( 'UTC' );
		}
		try {
			if ( is_numeric( $timestamp ) ) {
				$date = new DateTime( "@{$timestamp}" );
			} else {
				$date = new DateTime( $timestamp, $timezone );
			}
			// convert to UTC by adjusting the time based on the offset of the site's timezone
			if ( $convert_to_utc ) {
				$date->modify( -1 * $date->getOffset() . ' seconds' );
			}
		} catch ( Exception $e ) {
			$date = new DateTime( '@0' );
		}
		return $date->format( 'Y-m-d\TH:i:s\Z' );
	}
	private function get_avatar_url( $email ) {
		$avatar_html = get_avatar( $email );
		// Get the URL of the avatar from the provided HTML
		preg_match( '/src=["|\'](.+)[\&|"|\']/U', $avatar_html, $matches );
		if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
			return esc_url_raw( $matches[1] );
		}
		return null;
	}
	public function edit_customer( $id, $data ) {
		
			if ( ! isset( $data['customer'] ) ) {
				//throw new WC_API_Exception( 'woocommerce_api_missing_customer_data', sprintf( __( 'No %1$s data specified to edit %1$s', 'woocommerce' ), 'customer' ), 400 );
			}
			$data = $data['customer'];
			// Validate the customer ID.
			//$id = $this->validate_request( $id, 'customer', 'edit' );
			// Return the validate error.
			if ( is_wp_error( $id ) ) {
				//throw new WC_API_Exception( $id->get_error_code(), $id->get_error_message(), 400 );
			}
			$data = apply_filters( 'woocommerce_api_edit_customer_data', $data, $this );
			// Customer email.
			if ( isset( $data['email'] ) ) {
				wp_update_user( array( 'ID' => $id, 'user_email' => sanitize_email( $data['email'] ) ) );
			}
			// Customer password.
			if ( isset( $data['password'] ) ) {
				wp_update_user( array( 'ID' => $id, 'user_pass' => wc_clean( $data['password'] ) ) );
			}
			// Update customer data.
			$this->update_customer_data( $id, $data );
			do_action( 'woocommerce_api_edit_customer', $id, $data );
			return $this->get_customer( $id );
		
	}
	public function get_customer_orders( $id, $fields = null, $filter = array() ) {
		//$id = $this->validate_request( $id, 'customer', 'read' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		$filter['customer_id'] = $id;
		$orders = $this->get_orders( $fields, $filter, null, -1 );
		return $orders;
	}
	public function get_orders( $fields = null, $filter = array(), $status = null, $page = 1 ) {
		if ( ! empty( $status ) ) {
			$filter['status'] = $status;
		}
		$filter['page'] = $page;
		$query = $this->query_orders( $filter );
		$orders = array();
		foreach ( $query->posts as $order_id ) {
			/*if ( ! $this->is_readable( $order_id ) ) {
				continue;
			}*/
			$orders[] = current( $this->get_order( $order_id, $fields, $filter ) );
		}
		//$this->server->add_pagination_headers( $query );
		return array( 'orders' => $orders );
	}
	protected function query_orders( $args ) {
		// set base query arguments
		$query_args = array(
			'fields'      => 'ids',
			'post_type'   => 'shop_order',
			'post_status' => array_keys( wc_get_order_statuses() )
		);
		// add status argument
		if ( ! empty( $args['status'] ) ) {
			$statuses                  = 'wc-' . str_replace( ',', ',wc-', $args['status'] );
			$statuses                  = explode( ',', $statuses );
			$query_args['post_status'] = $statuses;
			unset( $args['status'] );
		}
		if ( ! empty( $args['customer_id'] ) ) {
			$query_args['meta_query'] = array(
				array(
					'key'     => '_customer_user',
					'value'   => absint( $args['customer_id'] ),
					'compare' => '='
				)
			);
		}
		$query_args = $this->merge_query_args( $query_args, $args );
		return new WP_Query( $query_args );
	}
	protected function merge_query_args( $base_args, $request_args ) {
		$args = array();
		// date
		if ( ! empty( $request_args['created_at_min'] ) || ! empty( $request_args['created_at_max'] ) || ! empty( $request_args['updated_at_min'] ) || ! empty( $request_args['updated_at_max'] ) ) {
			$args['date_query'] = array();
			// resources created after specified date
			if ( ! empty( $request_args['created_at_min'] ) ) {
				$args['date_query'][] = array( 'column' => 'post_date_gmt', 'after' => $this->server->parse_datetime( $request_args['created_at_min'] ), 'inclusive' => true );
			}
			// resources created before specified date
			if ( ! empty( $request_args['created_at_max'] ) ) {
				$args['date_query'][] = array( 'column' => 'post_date_gmt', 'before' => $this->server->parse_datetime( $request_args['created_at_max'] ), 'inclusive' => true );
			}
			// resources updated after specified date
			if ( ! empty( $request_args['updated_at_min'] ) ) {
				$args['date_query'][] = array( 'column' => 'post_modified_gmt', 'after' => $this->server->parse_datetime( $request_args['updated_at_min'] ), 'inclusive' => true );
			}
			// resources updated before specified date
			if ( ! empty( $request_args['updated_at_max'] ) ) {
				$args['date_query'][] = array( 'column' => 'post_modified_gmt', 'before' => $this->server->parse_datetime( $request_args['updated_at_max'] ), 'inclusive' => true );
			}
		}
		// search
		if ( ! empty( $request_args['q'] ) ) {
			$args['s'] = $request_args['q'];
		}
		// resources per response
		if ( ! empty( $request_args['limit'] ) ) {
			$args['posts_per_page'] = $request_args['limit'];
		}
		// resource offset
		if ( ! empty( $request_args['offset'] ) ) {
			$args['offset'] = $request_args['offset'];
		}
		// order (ASC or DESC, ASC by default)
		if ( ! empty( $request_args['order'] ) ) {
			$args['order'] = $request_args['order'];
		}
		// orderby
		if ( ! empty( $request_args['orderby'] ) ) {
			$args['orderby'] = $request_args['orderby'];
			// allow sorting by meta value
			if ( ! empty( $request_args['orderby_meta_key'] ) ) {
				$args['meta_key'] = $request_args['orderby_meta_key'];
			}
		}
		// allow post status change
		if ( ! empty( $request_args['post_status'] ) ) {
			$args['post_status'] = $request_args['post_status'];
			unset( $request_args['post_status'] );
		}
		// filter by a list of post id
		if ( ! empty( $request_args['in'] ) ) {
			$args['post__in'] = explode( ',', $request_args['in'] );
			unset( $request_args['in'] );
		}
		// exclude by a list of post id
		if ( ! empty( $request_args['not_in'] ) ) {
			$args['post__not_in'] = explode( ',', $request_args['not_in'] );
			unset( $request_args['not_in'] );
		}
		// resource page
		$args['paged'] = ( isset( $request_args['page'] ) ) ? absint( $request_args['page'] ) : 1;
		$args = apply_filters( 'woocommerce_api_query_args', $args, $request_args );
		return array_merge( $base_args, $args );
	}
	public function get_order( $id, $fields = null, $filter = array() ) {
		// Ensure order ID is valid & user has permission to read.
		//$id = $this->validate_request( $id, $this->post_type, 'read' );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		// Get the decimal precession.
		$dp         = ( isset( $filter['dp'] ) ? intval( $filter['dp'] ) : 2 );
		$order      = wc_get_order( $id );
		$order_post = get_post( $id );
		$expand     = array();
		if ( ! empty( $filter['expand'] ) ) {
			$expand = explode( ',', $filter['expand'] );
		}
		$order_data = array(
			'id'                        => $order->id,
			//'order_number'              => $order->get_order_number(),
			//'order_key'                 => $order->order_key,
			'order_date'                => $this->format_datetime( $order_post->post_date_gmt ),
			//'updated_at'                => $this->format_datetime( $order_post->post_modified_gmt ),
			//'completed_at'              => $this->format_datetime( $order->completed_date, true ),
			'order_status'                    => $order->get_status(),
			//'currency'                  => $order->get_order_currency(),
			'price'                     => wc_format_decimal( $order->get_total(), $dp ),
			//'subtotal'                  => wc_format_decimal( $order->get_subtotal(), $dp ),
			//'total_line_items_quantity' => $order->get_item_count(),
			//'total_tax'                 => wc_format_decimal( $order->get_total_tax(), $dp ),
			//'total_shipping'            => wc_format_decimal( $order->get_total_shipping(), $dp ),
			//'cart_tax'                  => wc_format_decimal( $order->get_cart_tax(), $dp ),
			//'shipping_tax'              => wc_format_decimal( $order->get_shipping_tax(), $dp ),
			//'total_discount'            => wc_format_decimal( $order->get_total_discount(), $dp ),
			//'shipping_methods'          => $order->get_shipping_method(),
			'payment_details' => array(
				'method_id'    => $order->payment_method,
				'method_title' => $order->payment_method_title,
				'paid'         => isset( $order->paid_date ),
			),
			'billing_address' => array(
				'first_name' => $order->billing_first_name,
				'last_name'  => $order->billing_last_name,
				'company'    => $order->billing_company,
				'address_1'  => $order->billing_address_1,
				'address_2'  => $order->billing_address_2,
				'city'       => $order->billing_city,
				'state'      => $order->billing_state,
				'postcode'   => $order->billing_postcode,
				'country'    => $order->billing_country,
				'email'      => $order->billing_email,
				'phone'      => $order->billing_phone,
			),
			'shipping_address' => array(
				'first_name' => $order->shipping_first_name,
				'last_name'  => $order->shipping_last_name,
				'company'    => $order->shipping_company,
				'address_1'  => $order->shipping_address_1,
				'address_2'  => $order->shipping_address_2,
				'city'       => $order->shipping_city,
				'state'      => $order->shipping_state,
				'postcode'   => $order->shipping_postcode,
				'country'    => $order->shipping_country,
			),
	        //'note'                      => $order->customer_note,
			//'customer_ip'               => $order->customer_ip_address,
			//'customer_user_agent'       => $order->customer_user_agent,
			'customer_id'               => $order->get_user_id(),
			//'view_order_url'            => $order->get_view_order_url(),
			'line_items'                => array(),
			//'shipping_lines'            => array(),
			//'tax_lines'                 => array(),
			//'fee_lines'                 => array(),
			//'coupon_lines'              => array(),
			//'is_vat_exempt'             => $order->is_vat_exempt === 'yes' ? true : false,
		);
		// Add line items.
		foreach ( $order->get_items() as $item_id => $item ) {
			$product     = $order->get_product_from_item( $item );
			$product_id  = null;
			$product_sku = null;
			// Check if the product exists.
			if ( is_object( $product ) ) {
				$product_id  = ( isset( $product->variation_id ) ) ? $product->variation_id : $product->id;
				$product_sku = $product->get_sku();
			}
			$meta = new WC_Order_Item_Meta( $item, $product );
			$item_meta = array();
			$hideprefix = ( isset( $filter['all_item_meta'] ) && 'true' === $filter['all_item_meta'] ) ? null : '_';
			foreach ( $meta->get_formatted( $hideprefix ) as $meta_key => $formatted_meta ) {
				$item_meta[] = array(
					'key'   => $formatted_meta['key'],
					'label' => $formatted_meta['label'],
					'value' => $formatted_meta['value'],
				);
			}
			$line_item = array(
				'id'           => $item_id,
				'subtotal'     => wc_format_decimal( $order->get_line_subtotal( $item, false, false ), $dp ),
				'subtotal_tax' => wc_format_decimal( $item['line_subtotal_tax'], $dp ),
				'total'        => wc_format_decimal( $order->get_line_total( $item, false, false ), $dp ),
				'total_tax'    => wc_format_decimal( $item['line_tax'], $dp ),
				'price'        => wc_format_decimal( $order->get_item_total( $item, false, false ), $dp ),
				'quantity'     => wc_stock_amount( $item['qty'] ),
				'tax_class'    => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
				'name'         => $item['name'],
				'product_id'   => $product_id,
				'sku'          => $product_sku,
				'meta'         => $item_meta,
			);
			if ( in_array( 'products', $expand ) ) {
				$_product_data = WC()->api->WC_API_Products->get_product( $product_id );
				if ( isset( $_product_data['product'] ) ) {
					$line_item['product_data'] = $_product_data['product'];
				}
			}
			$order_data['line_items'][] = $line_item;
		}
		// Add shipping.
		foreach ( $order->get_shipping_methods() as $shipping_item_id => $shipping_item ) {
			$order_data['shipping_lines'][] = array(
				'id'           => $shipping_item_id,
				'method_id'    => $shipping_item['method_id'],
				'method_title' => $shipping_item['name'],
				'total'        => wc_format_decimal( $shipping_item['cost'], $dp ),
			);
		}
		// Add taxes.
		foreach ( $order->get_tax_totals() as $tax_code => $tax ) {
			$tax_line = array(
				'id'       => $tax->id,
				'rate_id'  => $tax->rate_id,
				'code'     => $tax_code,
				'title'    => $tax->label,
				'total'    => wc_format_decimal( $tax->amount, $dp ),
				'compound' => (bool) $tax->is_compound,
			);
			if ( in_array( 'taxes', $expand ) ) {
				$_rate_data = WC()->api->WC_API_Taxes->get_tax( $tax->rate_id );
				if ( isset( $_rate_data['tax'] ) ) {
					$tax_line['rate_data'] = $_rate_data['tax'];
				}
			}
			$order_data['tax_lines'][] = $tax_line;
		}
		// Add fees.
		foreach ( $order->get_fees() as $fee_item_id => $fee_item ) {
			$order_data['fee_lines'][] = array(
				'id'        => $fee_item_id,
				'title'     => $fee_item['name'],
				'tax_class' => ( ! empty( $fee_item['tax_class'] ) ) ? $fee_item['tax_class'] : null,
				'total'     => wc_format_decimal( $order->get_line_total( $fee_item ), $dp ),
				'total_tax' => wc_format_decimal( $order->get_line_tax( $fee_item ), $dp ),
			);
		}
		// Add coupons.
		foreach ( $order->get_items( 'coupon' ) as $coupon_item_id => $coupon_item ) {
			$coupon_line = array(
				'id'     => $coupon_item_id,
				'code'   => $coupon_item['name'],
				'amount' => wc_format_decimal( $coupon_item['discount_amount'], $dp ),
			);
			if ( in_array( 'coupons', $expand ) ) {
				$_coupon_data = WC()->api->WC_API_Coupons->get_coupon_by_code( $coupon_item['name'] );
				if ( ! is_wp_error( $_coupon_data ) && isset( $_coupon_data['coupon'] ) ) {
					$coupon_line['coupon_data'] = $_coupon_data['coupon'];
				}
			}
			$order_data['coupon_lines'][] = $coupon_line;
		}
		return array( 'order' => apply_filters( 'woocommerce_api_order_response', $order_data, $order, $fields, $this->server ) );
		//return apply_filters( 'woocommerce_api_order_response', $order_data, $order, $fields, $this->server );
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
							"mp_order" => $key->mp_order,
							"mp_sort" => $key->mp_sort
						);
		}
		echo json_encode($array);
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
}