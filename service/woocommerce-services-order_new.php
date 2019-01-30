<?php
if (!defined( 'ABSPATH' )) exit;
class woocommerce_services_order_new
{
	function __construct()
	{
		  add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
		  add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
		  add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
	}
	function woo2app_api_regular_url(){
		
		add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_create_order', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_url_order', 'top' );
	}
	function woo2app_api_query_vars($query_vars){
		$query_vars[] = 'woo2app_create_order';
		$query_vars[] = 'woo2app_url_order';
		return $query_vars;
	}
	function woo2app_api_parse_request(&$wp){
		if ( array_key_exists( 'woo2app_create_order', $wp->query_vars ) ) {
	        $this->woocats_order_new_create_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'woo2app_url_order', $wp->query_vars ) ) {
	        $this->woo2app_url_order_webservice();
	        exit();
	    }
	    return;
	}
	function woocats_order_new_create_webservice(){
		$in = $_POST['in'];
		$slashless = stripcslashes($in);
		$url_json = urldecode($slashless);
		$json = (array) json_decode($url_json);
		$customer_id = $json["order"]->customer_id;
		$status = $json["order"]->status;
		$note = $json["order"]->note;
		$shiping = (array) $json["order"]->shipping_address;
		$items = (array) $json["order"]->line_items;
		$payment_details = (array) $json["order"]->payment_details;
		$billing_address = (array) $json["order"]->billing_address;
		
		$i=0;
		foreach ($items as $key) {
			$items[$i++] = (array) $key;	
		}
		$arr = array(
				"order" => array(
					 "shipping_address" => $shiping,
					  "line_items"=> $items,
  					  "payment_details"=> $payment_details,
  					  "customer_id"=> $customer_id,
  					  "status"=> $status,
  					  "note"=> $note,
  					  "billing_address"=> $billing_address 
  					  	)		
	  			);
		$array = $this->create_order($arr);
		$id = $array["order"]["id"];
		$order = new WC_Order( $id );
		$order->update_status( $status );
		$array["order"]["status"] = $status;
		echo json_encode($array);
		
	}
	public function create_order( $data ) {
		global $wpdb;
		wc_transaction_query( 'start' );
		//try {
			if ( ! isset( $data['order'] ) ) {
				throw new WC_API_Exception( 'woocommerce_api_missing_order_data', sprintf( __( 'No %1$s data specified to create %1$s', 'woocommerce' ), 'order' ), 400 );
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
					throw new WC_API_Exception( 'woocommerce_api_invalid_customer_id', __( 'Customer ID is invalid', 'woocommerce' ), 400 );
				}
				$default_order_args['customer_id'] = $data['customer_id'];
			}
			// create the pending order
			$order = $this->create_base_order( $default_order_args, $data );
			if ( is_wp_error( $order ) ) {
				throw new WC_API_Exception( 'woocommerce_api_cannot_create_order', sprintf( __( 'Cannot create order: %s', 'woocommerce' ), implode( ', ', $order->get_error_messages() ) ), 400 );
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
			'order_number'              => $order->get_order_number(),
			'order_key'                 => $order->order_key,
			'created_at'                => $this->format_datetime( $order_post->post_date_gmt ),
			'updated_at'                => $this->format_datetime( $order_post->post_modified_gmt ),
			'completed_at'              => $this->format_datetime( $order->completed_date, true ),
			'status'                    => $order->get_status(),
			'currency'                  => $order->get_order_currency(),
			'total'                     => wc_format_decimal( $order->get_total(), $dp ),
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
			'note'                      => $order->customer_note,
			'customer_ip'               => $order->customer_ip_address,
			'customer_user_agent'       => $order->customer_user_agent,
			'customer_id'               => $order->get_user_id(),
			'view_order_url'            => $order->get_view_order_url(),
			'line_items'                => array(),
			'shipping_lines'            => array(),
			'tax_lines'                 => array(),
			'fee_lines'                 => array(),
			'coupon_lines'              => array(),
			'is_vat_exempt'             => $order->is_vat_exempt === 'yes' ? true : false,
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
		$variation_id = 0;
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
		if ( isset( $item['total'] ) ) {
			$item_args['totals']['total'] = floatval( $item['total'] );
		}
		// total tax
		if ( isset( $item['total_tax'] ) ) {
			$item_args['totals']['tax'] = floatval( $item['total_tax'] );
		}
		// subtotal
		if ( isset( $item['subtotal'] ) ) {
			$item_args['totals']['subtotal'] = floatval( $item['subtotal'] );
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
			$coupon_id = $order->add_coupon( $coupon['code'], isset( $coupon['amount'] ) ? floatval( $coupon['amount'] ) : 0 );
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
    function woo2app_url_order_webservice(){
		global $woocommerce;
        try {
            $order = wc_get_order($_GET['order_id']);
            wp_send_json_success(array('pay_url' => $order->get_checkout_payment_url()));
        } catch (\Exception $e) {
            wp_send_json_error(array('errors' => array($e->getMessage())));
        }
	}
	
}