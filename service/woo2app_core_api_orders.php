<?php
if (!defined( 'ABSPATH' )) exit;
class woo2app_core_api_orders{
    public function __construct(){
        add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
        add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
        add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
    }
    function woo2app_api_regular_url(){
        add_rewrite_rule('', 'index.php?POST_order', 'top');
        add_rewrite_rule('', 'index.php?GET_order', 'top');
        add_rewrite_rule('', 'index.php?GET_orderpay', 'top');
    }
    function woo2app_api_query_vars($query_vars){
        $query_vars[] = 'POST_order';
        $query_vars[] = 'GET_order';
        $query_vars[] = 'GET_orderpay';
        return $query_vars;
    }
    function woo2app_api_parse_request(&$wp){
        if ( array_key_exists( 'POST_order', $wp->query_vars ) ) {
            $this->woo2app_POST_order();
            exit();
        }
        if ( array_key_exists( 'GET_order', $wp->query_vars ) ) {
            $this->woo2app_GET_order();
            exit();
        }
        if ( array_key_exists( 'GET_orderpay', $wp->query_vars ) ) {
            $this->woo2app_GET_orderpay();
            exit();
        }
        return;
    }
    function woo2app_POST_order(){
        ob_start();
        header('Content-Type: application/json; charset=utf-8');
        if(isset($_POST['in'])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            $shipping_lines = array();
            //print_r($json);return;
            if($json['customer_id'] != '-1'){
                //wp_logout();
                $user = array(
                    'user_login'    => $json['username'],
                    'user_password' => $json['password'],
                    'remember'      => false
                );
                // login user to browser ...
                wp_signon( $user, false );
            }
            if(isset($json["shipping_lines"]) && !isset($json["shipping_lines"]->method_id) ){
                foreach($json["shipping_lines"] as $x ){
                    $shipping_lines[] = (array) $x;
                }
            }else{
                $shipping_lines = array();
            }
            $customer_id = $json["customer_id"];
            $billing = array();
            $billing_address = (array) $json["billing"];
            if($customer_id == -1 ){
                $customer_id = "";
            }
            else{
                //$x = get_userdata($customer_id);
                $billing['company'] = get_user_meta($customer_id , 'billing_company' , true);
                $billing['address_1'] = get_user_meta($customer_id , 'billing_address_1' , true);
                $billing['address_2'] = get_user_meta($customer_id , 'billing_address_2' , true);
                $billing['city'] = get_user_meta($customer_id , 'billing_city' , true);
                $billing['state'] = get_user_meta($customer_id ,'billing_state' , true);
                $billing['postcode'] = get_user_meta($customer_id ,'billing_postcode' , true);
                $billing['country'] = get_user_meta($customer_id , 'billing_country' , true);
                $billing['email'] = get_user_meta($customer_id , 'billing_email' , true);
                $billing['phone'] = get_user_meta($customer_id ,'billing_phone' , true);
                $billing['last_name'] = get_user_meta($customer_id ,'last_name' , true);
                $billing['first_name'] = get_user_meta($customer_id , 'first_name' , true);
                $billing_address = $billing;
            }
            $note = $json["customer_note"];
            $shiping = (array) $json["shipping"];
            $items = (array) $json["items"];
            if(isset($json["coupon_lines"])){
                $coupons = (array) $json["coupon_lines"];
            }else{
                $coupons = array();
            }
            WC()->cart->empty_cart();
            $wallet_id = get_option('_woo_wallet_recharge_product');
            $wallet_price = 0;
            foreach ($items as $line_item) {
                WC()->cart->add_to_cart( $line_item->product_id , $line_item->quantity , $line_item->variation_id , '');
                if($line_item->product_id == $wallet_id){
                    $wallet_price = $line_item->price;
                }
            }
            $coupon_lines = array();
            if(isset($coupons) && is_array($coupons)){
                foreach($coupons as $i => $x){
                    WC()->cart->add_discount( $x->code );
                    $coupon_lines[] = (array) $x;//array("id" => $json["coupon_id"] ,"code" => $json["coupon_code"] ,"amount" => $json["amount"]);
                }
            }else{
                $coupon_lines = "";
            }

            $cart = wc()->cart->get_cart();
            $it = array();
            foreach($cart as $index => $value){
                if($value['product_id'] == $wallet_id){
                    $value['line_subtotal'] = $wallet_price;
                    $value['line_total'] = $wallet_price;
                }
                $it[] = $value;
            }

            $array = array();
            $array["shipping_address"] =  $shiping;
            $array["line_items"] = $it;
            $array["customer_id"] = $customer_id;
            $array["note"] = $note;
            $array["billing_address"] = $billing_address;

            $payment = array();
            if(isset($json["payment_method"]) && $json["payment_method"] != ""){
                $payment_method = $json["payment_method"];
                $payment_method_title = $json["payment_method_title"];
                $set_paid = $json["set_paid"];
                $payment = array(
                    "method_id" => $payment_method ,
                    "method_title" => $payment_method_title ,
                    "set_paid" => true ,
                );
                $array["payment_details"] = $payment;
            }
            if(isset($json["shipping_lines"]) ){
                $array["shipping_lines"] = $shipping_lines;
            }
            if(isset($json["coupon_lines"])){
                $array["coupon_lines"] = $coupon_lines;
            }
            $arr = array("order" => $array);
            //echo json_encode($arr);return;

            $array = $this->create_order($arr);
            if(isset($array["order"]["id"])){
                $id = $array["order"]["id"];
				
                if(isset($json['dokan']) && $json['dokan'] == 'true'){
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            		if ( is_plugin_active( 'dokan-lite/dokan.php' ) ) {
                		//plugin is activated
                		$d_o = new Dokan_Order_Manager();
                    	$d_o->maybe_split_orders($array["order"]["id"]);  
            		}
                }
                $order = new WC_Order( $id );
                if(isset($json['discount_module']) && $json['discount_module'] == 'true' && $json['customer_id'] != "-1"){
                    $discount = $this->module_calc_discount($json['customer_id'] , $items);
                    if($discount > 0){
                        $total = $order->get_total();
                        update_post_meta($id , '_cart_discount',$discount);
                        update_post_meta($id , '_order_total', $total - $discount);
                    }
                }
                if(isset($json['meta'])){
                    $cf = $json['meta'];
                    foreach ($cf as $k){
                        $order->add_meta_data( $k->key,$k->val);
                    }
                    $order->save_meta_data();
                }
                add_post_meta($id, 'woo_order_type', 'سفارش از طریق اپ');
                add_post_meta($id, '_buyer_sms_notify', 'yes');
                add_post_meta($id, '_allow_buyer_select_pm_type', 'no');
                add_post_meta($id, '_allow_buyer_select_status', 'no');
                add_post_meta($id, '_force_enable_buyer', 'no');
                $status = 'pending';
                if($payment_method == 'cod'){
                    $order->update_status( "processing" );
                    $status = 'processing';
                }
                if($payment_method == 'cod' || $payment_method == 'cheque' || $payment_method == 'bacs'){
                    wc_maybe_reduce_stock_levels($id);
                    WC()->mailer();
                }
                $args = array( 'order_id' => $id );
                do_action_ref_array( 'woocommerce_order_status_pending_to_processing_notification', $args );
                //do_action_ref_array( 'woocommerce_checkout_order_processed', $args );
                $order = $this->get_order($id);
                $array_test = array( "error" => 1 , "order_id" => $id, "order_key" => $array["order"]["order_key"] , "order_status" => $status , "orders" => $order['order']);
            }
            ob_clean();
            echo json_encode($array_test);
        }else{
            echo 0;
        }
    }

    function module_calc_discount($customer_id , $line_items){

        $user = get_user_by('id',$customer_id);
        global $wpdb;
        $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value like '".$user->roles[0] ."'", ARRAY_A );
        if($results){
            $post_id = $results[0]['post_id'];
            $total = round(wc()->cart->subtotal_ex_tax);
            $cats = get_post_meta($post_id,'categories' , true);
            $type = get_post_meta($post_id,'type' , true);
            $max_basket = get_post_meta($post_id,'max_basket' ,true);
            $min_basket = get_post_meta($post_id,'min_basket' ,true);
            $discount_amount = get_post_meta($post_id,'discount_amount',true);
            $products = get_post_meta($post_id,'products' , true);
            if(($total >= $min_basket || $min_basket == "") && ($total <= $max_basket || $max_basket == "")){
                $i = -1;
                $flag_cat = array();
                if($type == 'percent'){
                    foreach ($line_items as $line_item) {
                        $i++;
                        $flag_cat[$i] = 0;
                        if(in_array($line_item->product_id , $products) || $products == ""){
                            $terms = get_the_terms ( $line_item->product_id , 'product_cat' );
                            foreach ($terms as $term) {
                                if(in_array($term->term_id , $cats)){
                                    $flag_cat[$i] = 1;
                                }
                            }
                        }
                    }
                    if(!in_array(0 , $flag_cat) || $cats == ""){
                        return $discount = ($total * (int)$discount_amount) / 100;
                    }
                }
                elseif($type == 'constant'){
                    foreach ($line_items as $line_item) {
                        $i++;
                        $flag_cat[$i] = 0;
                        if(in_array($line_item->product_id , $products) || $products == ""){
                            $terms = get_the_terms ( $line_item->product_id , 'product_cat' );
                            foreach ($terms as $term) {
                                if(in_array($term->term_id , $cats)){
                                    $flag_cat[$i] = 1;
                                }
                            }
                        }
                    }
                    if(!in_array(0 , $flag_cat) || $cats == ""){
                        return $discount = (int)$discount_amount;
                    }
                }
            }
        }
        return 0;
    }

    function woo2app_GET_order(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        if(isset($_POST["in"])){
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            //json_encode(array(filter => array(customer_id => 2)));
            $customer_id = $json["filter"]->customer_id;
            $order_id = $json["filter"]->order_id;
            if($customer_id != ""){
                $array = $this->get_customer_orders($customer_id);
                if(isset($array["orders"])){
                    $array = array( "out" => array( "error" => 1 , "orders" => $array["orders"] ));
                    ob_clean();
                    echo json_encode($array);
                }else{
                    $array = array ( "out" => array( "error" => -2 , "orders" => $array["orders"] ,'msg' => 'خطا سمت ووکامرس !'));
                    ob_clean();
                    echo json_encode($array);
                }
            }else{
                $order_id = wc_get_order_id_by_order_key('wc_order_'.$order_id);
                if(wc_get_order( $order_id )){
                    $array = $this->get_order( $order_id );
                    if(isset($array["order"])){
                        $array = array( "out" => array( "error" => 1 , "orders" => $array["order"] ));
                        ob_clean();
                        echo json_encode($array);
                        return;
                    }
                }
                else{
                    $array = array ( "out" => array( "error" => -2 , "orders" => [] , 'msg' => ' کد رهگیری سفارش اشتباه می باشد.'));
                    ob_clean();
                    echo json_encode($array);
                }
            }
        }
    }

    function woo2app_GET_orderpay(){
        if(isset($_GET["order_id"]) && isset($_GET["order_key"]))	{
            wp_logout();
            $user_pass = base64_decode($_GET['api_key']);
            $user_pass = explode('$',$user_pass);
            $user = array();
            if(!empty($user_pass[0]) && !empty($user_pass[1])){
                $user = array(
                    'user_login'    => $user_pass[0],
                    'user_password' => $user_pass[1],
                    'remember'      => false
                );
                $user = wp_signon( $user, false );
            }

            $order = wc_get_order($_GET['order_id']);
            if($order->order_key == $_GET["order_key"]){
                $url = $order->get_checkout_payment_url();
                $url = str_replace('pay_for_order=true&', '', $url);
                if($order->get_payment_method() == 'wallet' && $user){
                    $wallet = new Woo_Gateway_Wallet_payment();
                    $wallet->process_payment($order->get_id());
                    $url = $order->get_checkout_order_received_url();
                    header("location:".$url);
                }
                header("location:".$url);
            }
        }
    }

    //---------------begin------------------------------------------------------------------------------
    public function create_order( $data ) {
        global $wpdb;
        wc_transaction_query( 'start' );
        //try {
        if ( ! isset( $data['order'] ) ) {
            /*throw new WC_API_Exception( 'woocommerce_api_missing_order_data', sprintf( __( 'No %1$s data specified to create %1$s', 'woocommerce' ), 'order' ), 400 );*/
            //json_encode(array( "error" => -2 , "order_id" => "" , "order_status" => "" ));
            return array( "error" => -2 , "order_id" => "" , "order_status" => "" );
        }
        $data = (array) $data['order'];
        /*			// permission check
                    if ( ! current_user_can( 'publish_shop_orders' ) ) {
                        throw new WC_API_Exception( 'woocommerce_api_user_cannot_create_order', __( 'You do not have permission to create orders', 'woocommerce' ), 401 );
                    }*/
        $data = apply_filters( 'woocommerce_api_create_order_data', $data, $this );
        // default order args, note that status is checked for validity in wc_create_order()
        $default_order_args = array(
            'status'        => isset( $data['status'] ) ? $data['status'] : '',
            'customer_note' => isset( $data['note'] ) ? $data['note'] : null,
        );
        // if creating order for existing customer
        if ( ! empty( $data['customer_id'] ) ) {
            // make sure customer exists
            if ( false === get_user_by( 'id', $data['customer_id'] ) ) {
                //throw new WC_API_Exception( 'woocommerce_api_invalid_customer_id', __( 'Customer ID is invalid', 'woocommerce' ), 400 );
                return array( "error" => -2 , "order_id" => "" , "order_status" => "" );
            }
            $default_order_args['customer_id'] = $data['customer_id'];
        }
        // create the pending order
        $order = $this->create_base_order( $default_order_args, $data );
        if ( is_wp_error( $order ) ) {
            // throw new WC_API_Exception( 'woocommerce_api_cannot_create_order', sprintf( __( 'Cannot create order: %s', 'woocommerce' ), implode( ', ', $order->get_error_messages() ) ), 400 );
            return array( "error" => -2 , "order_id" => "" , "order_status" => "" );
        }
        // billing/shipping addresses
        $this->set_order_addresses( $order, $data );
        $lines = array(
            'line_item' => 'line_items',
            'shipping'  => 'shipping_lines',
            'fee'       => 'fee_lines',
            'coupon'    => 'coupon_lines',
        );
        foreach ( $lines as $line_type => $line ) {
            if ( isset( $data[ $line ] ) && is_array( $data[ $line ] ) ) {
                $set_item = "set_{$line_type}";
                foreach ( $data[ $line ] as $item ) {
                    $this->$set_item( $order, $item, 'create' );
                }
            }
        }
        // set is vat exempt
        if ( isset( $data['is_vat_exempt'] ) ) {
            update_post_meta( $order->id, '_is_vat_exempt', $data['is_vat_exempt'] ? 'yes' : 'no' );
        }
        // calculate totals and set them
        $order->calculate_totals();
        // payment method (and payment_complete() if `paid` == true)
        if ( isset( $data['payment_details'] ) && is_array( $data['payment_details'] ) ) {
            // method ID & title are required
            if ( empty( $data['payment_details']['method_id'] ) || empty( $data['payment_details']['method_title'] ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_payment_details', __( 'Payment method ID and title are required', 'woocommerce' ), 400 );
            }
            update_post_meta( $order->id, '_payment_method', $data['payment_details']['method_id'] );
            update_post_meta( $order->id, '_payment_method_title', $data['payment_details']['method_title'] );
            // mark as paid if set
            if ( isset( $data['payment_details']['paid'] ) && true === $data['payment_details']['paid'] ) {
                $order->payment_complete( isset( $data['payment_details']['transaction_id'] ) ? $data['payment_details']['transaction_id'] : '' );
            }
        }
        // set order currency
        if ( isset( $data['currency'] ) ) {
            if ( ! array_key_exists( $data['currency'], get_woocommerce_currencies() ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_order_currency', __( 'Provided order currency is invalid', 'woocommerce'), 400 );
            }
            update_post_meta( $order->id, '_order_currency', $data['currency'] );
        }
        // set order meta
        if ( isset( $data['order_meta'] ) && is_array( $data['order_meta'] ) ) {
            $this->set_order_meta( $order->id, $data['order_meta'] );
        }
        // HTTP 201 Created
        //$this->server->send_status( 201 );
        wc_delete_shop_order_transients( $order->id );
        do_action( 'woocommerce_api_create_order', $order->id, $data, $this );
        //do_action( 'woocommerce_order_status_changed', $order->id , 10, 3 );
        wc_transaction_query( 'commit' );
        return $this->get_order( $order->id );
        // } catch ( WC_API_Exception $e ) {
        // 	wc_transaction_query( 'rollback' );
        // 	return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
        // }
    }
    protected function create_base_order( $args, $data ) {
        return wc_create_order( $args );
    }
    protected function set_order_addresses( $order, $data ) {
        $address_fields = array(
            'first_name',
            'last_name',
            'company',
            'email',
            'phone',
            'address_1',
            'address_2',
            'city',
            'state',
            'postcode',
            'country',
        );
        $billing_address = $shipping_address = array();
        // billing address
        if ( isset( $data['billing_address'] ) && is_array( $data['billing_address'] ) ) {
            foreach ( $address_fields as $field ) {
                if ( isset( $data['billing_address'][ $field ] ) ) {
                    $billing_address[ $field ] = wc_clean( $data['billing_address'][ $field ] );
                }
            }
            unset( $address_fields['email'] );
            unset( $address_fields['phone'] );
        }
        // shipping address
        if ( isset( $data['shipping_address'] ) && is_array( $data['shipping_address'] ) ) {
            foreach ( $address_fields as $field ) {
                if ( isset( $data['shipping_address'][ $field ] ) ) {
                    $shipping_address[ $field ] = wc_clean( $data['shipping_address'][ $field ] );
                }
            }
        }
        $order->set_address( $billing_address, 'billing' );
        $order->set_address( $shipping_address, 'shipping' );
        // update user meta
        if ( $order->get_user_id() ) {
            foreach ( $billing_address as $key => $value ) {
                update_user_meta( $order->get_user_id(), 'billing_' . $key, $value );
            }
            foreach ( $shipping_address as $key => $value ) {
                update_user_meta( $order->get_user_id(), 'shipping_' . $key, $value );
            }
        }
    }
    protected function set_item( $order, $item_type, $item, $action ) {
        global $wpdb;
        $set_method = "set_{$item_type}";
        // verify provided line item ID is associated with order
        if ( 'update' === $action ) {
            $result = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_id = %d AND order_id = %d",
                    absint( $item['id'] ),
                    absint( $order->id )
                ) );
            if ( is_null( $result ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_item_id', __( 'Order item ID provided is not associated with order', 'woocommerce' ), 400 );
            }
        }
        $this->$set_method( $order, $item, $action );
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
            'order_key'                 => $order->order_key,
            'date_created'                => strtotime($this->format_datetime( $order_post->post_date_gmt )),
            //'updated_at'                => $this->format_datetime( $order_post->post_modified_gmt ),
            //'completed_at'              => $this->format_datetime( $order->completed_date, true ),
            'status'                    => $order->get_status(),
            //'currency'                  => $order->get_order_currency(),
            'total'                     => $order->get_total(),//wc_format_decimal( $order->get_total(), $dp ),
            'subtotal'                  => wc_format_decimal( $order->get_subtotal(), $dp ),
            'total_line_items_quantity' => $order->get_item_count(),
            'total_tax'                 => wc_format_decimal( $order->get_total_tax(), $dp ),
            'total_shipping'            => wc_format_decimal( $order->get_total_shipping(), $dp ),
            'cart_tax'                  => wc_format_decimal( $order->get_cart_tax(), $dp ),
            'shipping_tax'              => wc_format_decimal( $order->get_shipping_tax(), $dp ),
            'total_discount'            => wc_format_decimal( $order->get_total_discount(), $dp ),
            'shipping_methods'          => $order->get_shipping_method(),
            'payment_details' => array(
                'method_id'    => $order->payment_method,
                'method_title' => $order->payment_method_title,
                'paid'         => isset( $order->paid_date ),
            ),
            'shipping' => array(
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
            'customer_note'                      => $order->customer_note,
            //'customer_ip'               => $order->customer_ip_address,
            //'customer_user_agent'       => $order->customer_user_agent,
            'customer_id'               => $order->get_user_id(),
            //'view_order_url'            => $order->get_view_order_url(),
            //'line_items'                => array(),
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
                //'id'           => $item_id,
                'subtotal'     => wc_format_decimal( $order->get_line_subtotal( $item, false, false ), $dp ),
                'subtotal_tax' => wc_format_decimal( $item['line_subtotal_tax'], $dp ),
                'total'        => $order->get_line_total( $item, false, false ),//wc_format_decimal( $order->get_line_total( $item, false, false ), $dp ),
                'total_tax'    => wc_format_decimal( $item['line_tax'], $dp ),
                'price'        => $order->get_item_total( $item, false, false ),//wc_format_decimal( $order->get_item_total( $item, false, false ), $dp ),
                'quantity'     => wc_stock_amount( $item['qty'] ),
                'variation_id' => $product->variation_id,
                //'tax_class'    => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
                'name'         => $item['name'],
                'product_id'   => $product_id,
                //'sku'          => $product_sku,
                //'meta'         => $item_meta,
            );
            if ( in_array( 'products', $expand ) ) {
                $_product_data = WC()->api->WC_API_Products->get_product( $product_id );
                if ( isset( $_product_data['product'] ) ) {
                    $line_item['product_data'] = $_product_data['product'];
                }
            }
            $order_data['items'][] = $line_item;
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
    protected function set_order_meta( $order_id, $order_meta ) {
        foreach ( $order_meta as $meta_key => $meta_value ) {
            if ( is_string( $meta_key) && ! is_protected_meta( $meta_key ) && is_scalar( $meta_value ) ) {
                update_post_meta( $order_id, $meta_key, $meta_value );
            }
        }
    }
    protected function set_line_item( $order, $item, $action ) {
        $creating  = ( 'create' === $action );
        $item_args = array();
        // product is always required
        if ( ! isset( $item['product_id'] ) && ! isset( $item['sku'] ) ) {
            throw new WC_API_Exception( 'woocommerce_api_invalid_product_id', __( 'Product ID or SKU is required', 'woocommerce' ), 400 );
        }
        // when updating, ensure product ID provided matches
        if ( 'update' === $action ) {
            $item_product_id   = wc_get_order_item_meta( $item['id'], '_product_id' );
            $item_variation_id = wc_get_order_item_meta( $item['id'], '_variation_id' );
            if ( $item['product_id'] != $item_product_id && $item['product_id'] != $item_variation_id ) {
                throw new WC_API_Exception( 'woocommerce_api_invalid_product_id', __( 'Product ID provided does not match this line item', 'woocommerce' ), 400 );
            }
        }
        if ( isset( $item['product_id'] ) ) {
            $product_id = $item['product_id'];
        } elseif ( isset( $item['sku'] ) ) {
            $product_id = wc_get_product_id_by_sku( $item['sku'] );
        }
        // variations must each have a key & value
        $variation_id = $item['variation_id'];
        if ( isset( $item['variations'] ) && is_array( $item['variations'] ) ) {
            foreach ( $item['variations'] as $key => $value ) {
                if ( ! $key || ! $value ) {
                    throw new WC_API_Exception( 'woocommerce_api_invalid_product_variation', __( 'The product variation is invalid', 'woocommerce' ), 400 );
                }
            }
            $item_args['variation'] = $item['variations'];
            $variation_id = $this->get_variation_id( wc_get_product( $product_id ), $item_args['variation'] );
        }
        $product = wc_get_product( $variation_id ? $variation_id : $product_id );
        // must be a valid WC_Product
        if ( ! is_object( $product ) ) {
            throw new WC_API_Exception( 'woocommerce_api_invalid_product', __( 'Product is invalid', 'woocommerce' ), 400 );
        }
        // quantity must be positive float
        if ( isset( $item['quantity'] ) && floatval( $item['quantity'] ) <= 0 ) {
            throw new WC_API_Exception( 'woocommerce_api_invalid_product_quantity', __( 'Product quantity must be a positive float', 'woocommerce' ), 400 );
        }
        // quantity is required when creating
        if ( $creating && ! isset( $item['quantity'] ) ) {
            throw new WC_API_Exception( 'woocommerce_api_invalid_product_quantity', __( 'Product quantity is required', 'woocommerce' ), 400 );
        }
        // quantity
        if ( isset( $item['quantity'] ) ) {
            $item_args['qty'] = $item['quantity'];
        }
        // total
        if ( isset( $item['line_total'] ) ) {
            $item_args['totals']['total'] = floatval( $item['line_total'] );
        }
        // total tax
        if ( isset( $item['total_tax'] ) ) {
            $item_args['totals']['tax'] = floatval( $item['total_tax'] );
        }
        // subtotal
        if ( isset( $item['line_subtotal'] ) ) {
            $item_args['totals']['subtotal'] = floatval( $item['line_subtotal'] );
        }
        // subtotal tax
        if ( isset( $item['subtotal_tax'] ) ) {
            $item_args['totals']['subtotal_tax'] = floatval( $item['subtotal_tax'] );
        }
        $item_args = apply_filters( 'woocommerce_api_order_line_item_args', $item_args, $item, $order, $action );
        if ( $creating ) {
            $item_id = $order->add_product( $product, $item_args['qty'], $item_args );
            if ( ! $item_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_create_line_item', __( 'Cannot create line item, try again', 'woocommerce' ), 500 );
            }
        } else {
            $item_id = $order->update_product( $item['id'], $product, $item_args );
            if ( ! $item_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_update_line_item', __( 'Cannot update line item, try again', 'woocommerce' ), 500 );
            }
        }
    }
    public function get_variation_id( $product, $variations = array() ) {
        $variation_id = null;
        $variations_normalized = array();
        if ( $product->is_type( 'variable' ) && $product->has_child() ) {
            if ( isset( $variations ) && is_array( $variations ) ) {
                // start by normalizing the passed variations
                foreach ( $variations as $key => $value ) {
                    $key = str_replace( 'attribute_', '', str_replace( 'pa_', '', $key ) ); // from get_attributes in class-wc-api-products.php
                    $variations_normalized[ $key ] = strtolower( $value );
                }
                // now search through each product child and see if our passed variations match anything
                foreach ( $product->get_children() as $variation ) {
                    $meta = array();
                    foreach ( get_post_meta( $variation ) as $key => $value ) {
                        $value = $value[0];
                        $key = str_replace( 'attribute_', '', str_replace( 'pa_', '', $key ) );
                        $meta[ $key ] = strtolower( $value );
                    }
                    // if the variation array is a part of the $meta array, we found our match
                    if ( $this->array_contains( $variations_normalized, $meta ) ) {
                        $variation_id = $variation;
                        break;
                    }
                }
            }
        }
        return $variation_id;
    }
    protected function set_shipping( $order, $shipping, $action ) {
        // total must be a positive float
        if ( isset( $shipping['total'] ) && floatval( $shipping['total'] ) < 0 ) {
            throw new WC_API_Exception( 'woocommerce_invalid_shipping_total', __( 'Shipping total must be a positive amount', 'woocommerce' ), 400 );
        }
        if ( 'create' === $action ) {
            // method ID is required
            if ( ! isset( $shipping['method_id'] ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_shipping_item', __( 'Shipping method ID is required', 'woocommerce' ), 400 );
            }
            $rate = new WC_Shipping_Rate( $shipping['method_id'], isset( $shipping['method_title'] ) ? $shipping['method_title'] : '', isset( $shipping['total'] ) ? floatval( $shipping['total'] ) : 0, array(), $shipping['method_id'] );
            $shipping_id = $order->add_shipping( $rate );
            if ( ! $shipping_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_create_shipping', __( 'Cannot create shipping method, try again', 'woocommerce' ), 500 );
            }
        } else {
            $shipping_args = array();
            if ( isset( $shipping['method_id'] ) ) {
                $shipping_args['method_id'] = $shipping['method_id'];
            }
            if ( isset( $shipping['method_title'] ) ) {
                $shipping_args['method_title'] = $shipping['method_title'];
            }
            if ( isset( $shipping['total'] ) ) {
                $shipping_args['cost'] = floatval( $shipping['total'] );
            }
            $shipping_id = $order->update_shipping( $shipping['id'], $shipping_args );
            if ( ! $shipping_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_update_shipping', __( 'Cannot update shipping method, try again', 'woocommerce' ), 500 );
            }
        }
    }
    protected function set_fee( $order, $fee, $action ) {
        if ( 'create' === $action ) {
            // fee title is required
            if ( ! isset( $fee['title'] ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_fee_item', __( 'Fee title is required', 'woocommerce' ), 400 );
            }
            $order_fee            = new stdClass();
            $order_fee->id        = sanitize_title( $fee['title'] );
            $order_fee->name      = $fee['title'];
            $order_fee->amount    = isset( $fee['total'] ) ? floatval( $fee['total'] ) : 0;
            $order_fee->taxable   = false;
            $order_fee->tax       = 0;
            $order_fee->tax_data  = array();
            $order_fee->tax_class = '';
            // if taxable, tax class and total are required
            if ( isset( $fee['taxable'] ) && $fee['taxable'] ) {
                if ( ! isset( $fee['tax_class'] ) ) {
                    throw new WC_API_Exception( 'woocommerce_invalid_fee_item', __( 'Fee tax class is required when fee is taxable', 'woocommerce' ), 400 );
                }
                $order_fee->taxable   = true;
                $order_fee->tax_class = $fee['tax_class'];
                if ( isset( $fee['total_tax'] ) ) {
                    $order_fee->tax = isset( $fee['total_tax'] ) ? wc_format_refund_total( $fee['total_tax'] ) : 0;
                }
                if ( isset( $fee['tax_data'] ) ) {
                    $order_fee->tax      = wc_format_refund_total( array_sum( $fee['tax_data'] ) );
                    $order_fee->tax_data = array_map( 'wc_format_refund_total', $fee['tax_data'] );
                }
            }
            $fee_id = $order->add_fee( $order_fee );
            if ( ! $fee_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_create_fee', __( 'Cannot create fee, try again', 'woocommerce' ), 500 );
            }
        } else {
            $fee_args = array();
            if ( isset( $fee['title'] ) ) {
                $fee_args['name'] = $fee['title'];
            }
            if ( isset( $fee['tax_class'] ) ) {
                $fee_args['tax_class'] = $fee['tax_class'];
            }
            if ( isset( $fee['total'] ) ) {
                $fee_args['line_total'] = floatval( $fee['total'] );
            }
            if ( isset( $fee['total_tax'] ) ) {
                $fee_args['line_tax'] = floatval( $fee['total_tax'] );
            }
            $fee_id = $order->update_fee( $fee['id'], $fee_args );
            if ( ! $fee_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_update_fee', __( 'Cannot update fee, try again', 'woocommerce' ), 500 );
            }
        }
    }
    protected function set_coupon( $order, $coupon, $action ) {
        // coupon amount must be positive float
        if ( isset( $coupon['amount'] ) && floatval( $coupon['amount'] ) < 0 ) {
            throw new WC_API_Exception( 'woocommerce_invalid_coupon_total', __( 'Coupon discount total must be a positive amount', 'woocommerce' ), 400 );
        }
        if ( 'create' === $action ) {
            // coupon code is required
            if ( empty( $coupon['code'] ) ) {
                throw new WC_API_Exception( 'woocommerce_invalid_coupon_coupon', __( 'Coupon code is required', 'woocommerce' ), 400 );
            }
            $coupon_id = $order->add_coupon( $coupon['code'], 100 , 0 );
            if ( ! $coupon_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_create_order_coupon', __( 'Cannot create coupon, try again', 'woocommerce' ), 500 );
            }
        } else {
            $coupon_args = array();
            if ( isset( $coupon['code'] ) ) {
                $coupon_args['code'] = $coupon['code'];
            }
            if ( isset( $coupon['amount'] ) ) {
                $coupon_args['discount_amount'] = floatval( $coupon['amount'] );
            }
            $coupon_id = $order->update_coupon( $coupon['id'], $coupon_args );
            if ( ! $coupon_id ) {
                throw new WC_API_Exception( 'woocommerce_cannot_update_order_coupon', __( 'Cannot update coupon, try again', 'woocommerce' ), 500 );
            }
        }
    }
    protected function array_contains( $needles, $haystack ) {
        foreach ( $needles as $key => $value ) {
            if ( $haystack[ $key ] !== $value ) {
                return false;
            }
        }
        return true;
    }
    public function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
    }
//-----------------get order customer-------------------------------------------------------------
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
            'posts_per_page' => -1,
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
    //---------------end--------------------------------------------------------------------------------
}