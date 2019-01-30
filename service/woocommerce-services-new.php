<?php
if (!defined( 'ABSPATH' )) exit;
class woocommerce_services_new
{
    function __construct()
    {
        add_action( 'init', array( $this , 'woo2app_api_regular_url' ));
        add_filter( 'query_vars', array( $this , 'woo2app_api_query_vars' ));
        add_action( 'parse_request', array( $this , 'woo2app_api_parse_request' ));
    }
    function woo2app_api_regular_url(){
        add_rewrite_rule( 'my-api.php$', 'index.php?woocats', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_product_all', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_product_one', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_product_cat', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_product_search', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_related', 'top' );
        add_rewrite_rule( 'my-api.php$', 'index.php?woo2app_checkout', 'top' );
    }
    function woo2app_api_query_vars($query_vars){
        $query_vars[] = 'woocats';
        $query_vars[] = 'woo2app_product_all';
        $query_vars[] = 'woo2app_product_one';
        $query_vars[] = 'woo2app_product_cat';
        $query_vars[] = 'woo2app_product_search';
        $query_vars[] = 'woo2app_related';
        $query_vars[] = 'woo2app_checkout';
        return $query_vars;
    }
    function woo2app_api_parse_request(&$wp){
        if ( array_key_exists( 'woocats', $wp->query_vars ) ) {
            $this->woocats_new_webservice();
            exit();
        }
        if(array_key_exists( 'woo2app_product_all', $wp->query_vars )){
            $this->woo2app_product_all_new_webservice();
            exit();
        }
        if(array_key_exists( 'woo2app_product_one', $wp->query_vars )){
            $this->woo2app_product_one_new_webservice();
            exit();
        }
        if(array_key_exists( 'woo2app_product_cat', $wp->query_vars )){
            $this->woo2app_product_cats_new_webservice();
            exit();
        }
        if(array_key_exists( 'woo2app_product_search', $wp->query_vars )){
            $this->woo2app_product_search_new_webservice();
            exit();
        }
        if ( array_key_exists( 'woo2app_related', $wp->query_vars ) ) {
            $this->related_ids_webservice();
            exit();
        }
        if ( array_key_exists( 'woo2app_checkout', $wp->query_vars ) ) {
            $this->woo2app_checkout_webservice();
            exit();
        }
        return;
    }
    function woocats_new_webservice(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        $product_categories = array();
        $terms = get_terms(  'product_cat', array( 'hide_empty' => false, 'fields' => 'ids' ) );
        foreach ( $terms as $term_id ) {
            $product_categories[] = current( $this->get_product_category( $term_id, $fields ) );
        }
        $array = array( 'product_categories' => apply_filters( 'woocommerce_api_product_categories_response', $product_categories, $terms, $fields, $this ) );
        ob_clean();
        echo json_encode($array);
    }
    public function get_product_category( $id, $fields = null ) {
        $term = get_term( $id, 'product_cat' );
        if ( is_wp_error( $term ) || is_null( $term ) ) {
            throw new WC_API_Exception( 'woocommerce_api_invalid_product_category_id', __( 'A product category with the provided ID could not be found', 'woocommerce' ), 404 );
        }
        $term_id = intval( $term->term_id );
        // Get category display type
        $display_type = get_woocommerce_term_meta( $term_id, 'display_type' );
        // Get category image
        $image = '';
        if ( $image_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id' ) ) {
            //$image = wp_get_attachment_url( $image_id );
            $image2 = wp_get_attachment_image_src( $image_id ,'medium' );
            $image=$image2['0'];
        }
        $product_category = array(
            'id'          => $term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'parent'      => $term->parent,
            'description' => $term->description,
            'display'     => $display_type ? $display_type : 'default',
            'image'       => $image ? esc_url( $image ) : '',
            'count'       => intval( $term->count )
        );
        return array( 'product_category' => apply_filters( 'woocommerce_api_product_category_response', $product_category, $id, $fields, $term, $this ) );
    }
    function woo2app_product_all_new_webservice(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        if(isset($_GET["page"]) &&  isset($_GET["count"])){
            $filter = array('limit' => $_GET["count"]);
            $array = $this->get_products(null,null,$filter , $_GET["page"] ,$_GET["orderby"] , $_GET["order"]);
            ob_clean();
            echo json_encode($array);
        }
    }
    function woo2app_product_one_new_webservice(){
        ob_start();
        if(!empty($_GET["woo2app_product_one"])){
            $array = $this->get_product($_GET["woo2app_product_one"]);
            ob_clean();
            echo json_encode($array);
        }
    }
    function woo2app_product_cats_new_webservice(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        $array = array();
        if(isset($_GET["page"]) &&  isset($_GET["count"]) &&  isset($_GET["cat"]) ){
            $filter = array('limit' => $_GET["count"]  , "category" => $_GET["cat"] );
            $array = $this->get_products(null,null,$filter , $_GET["page"],$_GET["orderby"] , $_GET["order"] );
        }
        ob_clean();
        echo json_encode($array);
    }
    function woo2app_product_search_new_webservice(){
        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        if (isset($_GET["search_word"])) {
            $filter = array('q' => $_GET["search_word"] , 'limit' => 30 );
            $array = $this->get_products(null,null,$filter );
            ob_clean();
            echo json_encode($array);
        }
    }
    function get_products( $fields = null, $type = null, $filter = array(), $page = 1 ,$orderby = '' , $order = '') {
        if ( ! empty( $type ) ) {
            $filter['type'] = $type;
        }
        $filter['page'] = $page;
        $query = $this->query_products( $filter , $orderby , $order );
        $products = array();
        foreach ( $query->posts as $product_id ) {
            /*if ( ! $this->is_readable( $product_id ) ) {
                continue;
            }*/
            $x = current( $this->get_product( $product_id, $fields ) );
            $products[] = $x;
        }
        //$this->server->add_pagination_headers( $query );
        return array( 'products' => $products );
    }
    public function get_product( $id, $fields = null ) {
        //$id = $this->validate_request( $id, 'product', 'read' );
        if ( is_wp_error( $id ) ) {
            return $id;
        }
        $product = wc_get_product( $id );
        if($product == false) return;
        // add data that applies to every product type
        $product_data = $this->get_product_data( $product );
        // add variations to variable products
        if ( $product->is_type( 'variable' ) && $product->has_child() ) {
            $product_data['variations'] = $this->get_variation_data( $product );
        }
        // add the parent product data to an individual variation
        if ( $product->is_type( 'variation' ) && $product->parent ) {
            $product_data['parent'] = $this->get_product_data( $product->parent );
        }
        // Add grouped products data
        if ( $product->is_type( 'grouped' ) && $product->has_child() ) {
            $product_data['grouped_products'] = $this->get_grouped_products_data( $product );
        }
        if ( $product->is_type( 'simple' ) && ! empty( $product->post->post_parent ) ) {
            $_product = wc_get_product( $product->post->post_parent );
            $product_data['parent'] = $this->get_product_data( $_product );
        }
        return array( 'product' => apply_filters( 'woocommerce_api_product_response', $product_data, $product, $fields, $this->server ) );
    }
    private function query_products( $args ,$orderby = '', $order = '' ) {
        // Set base query arguments
        $stock = array();
        ( get_option('woocommerce_hide_out_of_stock_items') == 'yes') ? $stock = array('key'=>'_stock_status','value'=>'instock'): '';
        if(isset($_POST['in'])) {
            $in    = stripcslashes( $_POST['in'] );
            $in    = urldecode( $in );
            $in    = json_decode( $in );
            if(isset($in->exist)){
                $exist = $in->exist;
                $stock = array();
                if ( $exist == 'true'){
                    $stock = array('key'=>'_stock_status','value' => 'instock');
                }
            }
        }
        $query_args = array(
            'fields'      => 'ids',
            'post_type'   => 'product',
            'post_status' => 'publish',
            'meta_query' => array(
                $stock,
            ),
        );
        switch ( $orderby ) {
            case 'rand' :
                $query_args['orderby']  = 'rand  ';
                break;
            case 'date' :
                $query_args['orderby']  = 'date ID';
                $query_args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
                break;
            case 'price' :
                $query_args['orderby']  = "meta_value_num ID";
                $query_args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                $query_args['meta_key'] = '_price';
                break;
            case 'selling' :
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby']	= 'meta_value_num';
                $query_args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                break;
            case 'popularity' :
                $query_args['meta_key'] = 'total_sales';
                //$query_args['orderby']	= 'meta_value_num';
                $query_args['orderby'] = "meta_value_num DESC, post_date DESC";
                break;
            case 'title' :
                $query_args['orderby']  = 'title';
                $query_args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                break;
        }
        // Taxonomy query to filter products by type, category, tag, shipping class, and
        // attribute.
        $tax_query = array();
        // Map between taxonomy name and arg's key.
//
        $taxonomies_arg_map = array(
            'product_type'           => 'type',
            'product_cat'            => 'category',
            'product_tag'            => 'tag',
            'product_shipping_class' => 'shipping_class',
        );
        // Add attribute taxonomy names into the map.
//		foreach ( wc_get_attribute_taxonomy_names() as $attribute_name ) {
//
//			$taxonomies_arg_map[ $attribute_name ] = $attribute_name;
//
//		}
        // Set tax_query for each passed arg.
        foreach ( $taxonomies_arg_map as $tax_name => $arg ) {
            if ( ! empty( $args[ $arg ] ) ) {
                $terms = explode( ',', $args[ $arg ] );
                $tax_query[] = array(
                    'taxonomy' => $tax_name,
                    'field'    => 'slug',
                    'terms'    => $terms,
                );
                unset( $args[ $arg ] );
            }
        }
        if(isset($_POST['in'])){
            $in = stripcslashes($_POST['in']);
            $in = urldecode($in);
            $in =   json_decode($in);
            $filter =  $in->filter;
            $attribute = array();
            foreach ($filter as $f ){
                $terms = array();
                foreach ($f->options as $op){
                    $terms[] = $op->slug;
                }
                $attribute[] =
                    array(
                        'taxonomy'      => $f->slug, // The Group Attribute
                        'terms'         => $terms, // Term Taxonomy IDs for the Group Attribute Terms
                        'field'         => 'slug',
                    );
            }
            foreach ($attribute as $a){
                $tax_query[100]['relation'] = 'AND';
                $tax_query[100][] = $a;
            }
        }
        $query_args['tax_query'] = $tax_query;
        // Filter by specific sku
        if ( ! empty( $args['sku'] ) ) {
            if ( ! is_array( $query_args['meta_query'] ) ) {
                $query_args['meta_query'] = array();
            }
            $query_args['meta_query'][] = array(
                'key'     => '_sku',
                'value'   => $args['sku'],
                'compare' => '='
            );
            $query_args['post_type'] = array( 'product', 'product_variation' );
        }
        $query_args = $this->merge_query_args( $query_args, $args );
        return new WP_Query( $query_args );
        //return $args;
    }
    public function add_pagination_headers( $query ) {
        // WP_User_Query
        if ( is_a( $query, 'WP_User_Query' ) ) {
            $single      = count( $query->get_results() ) == 1;
            $total       = $query->get_total();
            if( $query->get( 'number' ) > 0 ) {
                $page = ( $query->get( 'offset' ) / $query->get( 'number' ) ) + 1;
                $total_pages = ceil( $total / $query->get( 'number' ) );
            } else {
                $page = 1;
                $total_pages = 1;
            }
        } else if ( is_a( $query, 'stdClass' ) ) {
            $page        = $query->page;
            $single      = $query->is_single;
            $total       = $query->total;
            $total_pages = $query->total_pages;
            // WP_Query
        } else {
            $page        = $query->get( 'paged' );
            $single      = $query->is_single();
            $total       = $query->found_posts;
            $total_pages = $query->max_num_pages;
        }
        if ( ! $page ) {
            $page = 1;
        }
        $next_page = absint( $page ) + 1;
        if ( ! $single ) {
            // first/prev
            if ( $page > 1 ) {
                $this->link_header( 'first', $this->get_paginated_url( 1 ) );
                $this->link_header( 'prev', $this->get_paginated_url( $page -1 ) );
            }
            // next
            if ( $next_page <= $total_pages ) {
                $this->link_header( 'next', $this->get_paginated_url( $next_page ) );
            }
            // last
            if ( $page != $total_pages ) {
                $this->link_header( 'last', $this->get_paginated_url( $total_pages ) );
            }
        }
        $this->header( 'X-WC-Total', $total );
        $this->header( 'X-WC-TotalPages', $total_pages );
        do_action( 'woocommerce_api_pagination_headers', $this, $query );
    }
    function get_store_name($u_id){
        return get_user_meta($u_id,'dokan_store_name' , true);
    }
    function get_product_data( $product ) {
        //return metadata_exists( 'post', $product->id, 'total_sales' ) ? (int) get_post_meta( $product->id, 'total_sales', true ) : 0;
        return array(
            'store'         => $this->get_store_name(get_post_field( 'post_author', $product->id )) ,
            'title'              => $product->get_title(),
            'id'                 => (int) $product->is_type( 'variation' ) ? $product->get_variation_id() : $product->id,
            'created_at'         => $this->format_datetime( $product->get_post_data()->post_date_gmt ),
            'updated_at'         => $this->format_datetime( $product->get_post_data()->post_modified_gmt ),
            'type'               => $product->product_type,
            'status'             => $product->get_post_data()->post_status,
            'downloadable'       => $product->is_downloadable(),
            'virtual'            => $product->is_virtual(),
            'permalink'          => $product->get_permalink(),
            'sku'                => $product->get_sku(),
            'price'              => $product->get_price(),
            'regular_price'      => $product->get_regular_price(),
            'sale_price'         => $product->get_sale_price() ? $product->get_sale_price() : null,
            'price_html'         => 'price_html',
            'taxable'            => $product->is_taxable(),
            'tax_status'         => $product->get_tax_status(),
            'tax_class'          => $product->get_tax_class(),
            'managing_stock'     => $product->managing_stock(),
            'stock_quantity'     => $product->get_stock_quantity(),
            'in_stock'           => $product->is_in_stock(),
            'backorders_allowed' => $product->backorders_allowed(),
            'backordered'        => $product->is_on_backorder(),
            'sold_individually'  => $product->is_sold_individually(),
            'purchaseable'       => $product->is_purchasable(),
            'featured'           => $product->is_featured(),
            'visible'            => $product->is_visible(),
            'catalog_visibility' => $product->visibility,
            'on_sale'            => $product->is_on_sale(),
            'product_url'        => $product->is_type( 'external' ) ? $product->get_product_url() : '',
            'button_text'        => $product->is_type( 'external' ) ? $product->get_button_text() : '',
            'weight'             => $product->get_weight() ? $product->get_weight() : null,
            'dimensions'         => array(
                'length' => $product->length,
                'width'  => $product->width,
                'height' => $product->height,
                'unit'   => get_option( 'woocommerce_dimension_unit' ),
            ),
            'shipping_required'  => $product->needs_shipping(),
            'shipping_taxable'   => $product->is_shipping_taxable(),
            'shipping_class'     => $product->get_shipping_class(),
            'shipping_class_id'  => ( 0 !== $product->get_shipping_class_id() ) ? $product->get_shipping_class_id() : null,
            'description'        => wpautop( do_shortcode( $product->get_post_data()->post_content ) ),
            'short_description'  => apply_filters( 'woocommerce_short_description', $product->get_post_data()->post_excerpt ),
            'reviews_allowed'    => ( 'open' === $product->get_post_data()->comment_status ),
            'average_rating'     => wc_format_decimal( $product->get_average_rating(), 2 ),
            'rating_count'       => (int) $product->get_rating_count(),
            'related_ids'        => empty($product->get_upsells()) ? array_map( 'absint', array_values($product->get_related())) : array_map( 'absint', $product->get_upsells() ),
            'upsell_ids'         => array_map( 'absint', $product->get_upsells() ),
            'cross_sell_ids'     => array_map( 'absint', $product->get_cross_sells() ),
            'parent_id'          => $product->is_type( 'variation' ) ? $product->parent->id : $product->post->post_parent,
            'categories'         => wp_get_post_terms( $product->id, 'product_cat', array( 'fields' => 'names' ) ),
            'tags'               => wp_get_post_terms( $product->id, 'product_tag', array( 'fields' => 'names' ) ),
            'images'             => $this->get_images( $product ),
            'featured_src' => (get_the_post_thumbnail_url( $product->id, 'thumbnail')!='')?get_the_post_thumbnail_url( $product->id, 'thumbnail'):'',
            'attributes'         => $this->get_attributes( $product ),
            'downloads'          => $this->get_downloads( $product ),
            'download_limit'     => (int) $product->download_limit,
            'download_expiry'    => (int) $product->download_expiry,
            'download_type'      => $product->download_type,
            'purchase_note'      => wpautop( do_shortcode( wp_kses_post( $product->purchase_note ) ) ),
            'total_sales'        => metadata_exists( 'post', $product->id, 'total_sales' ) ? (int) get_post_meta( $product->id, 'total_sales', true ) : 0,
            'variations'         => array(),
            'parent'             => array(),
            'grouped_products'   => array(),
            'menu_order'         => $this->get_product_menu_order( $product ),
        );
    }
    protected function is_readable( $post ) {
        return $this->check_permission( $post, 'read' );
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
    protected function validate_request( $id, $type, $context ) {
        if ( 'shop_order' === $type || 'shop_coupon' === $type || 'shop_webhook' === $type ) {
            $resource_name = str_replace( 'shop_', '', $type );
        } else {
            $resource_name = $type;
        }
        $id = absint( $id );
        // Validate ID
        if ( empty( $id ) ) {
            return new WP_Error( "woocommerce_api_invalid_{$resource_name}_id", sprintf( __( 'Invalid %s ID', 'woocommerce' ), $type ), array( 'status' => 404 ) );
        }
        // Only custom post types have per-post type/permission checks
        if ( 'customer' !== $type ) {
            $post = get_post( $id );
            if ( null === $post ) {
                return new WP_Error( "woocommerce_api_no_{$resource_name}_found", sprintf( __( 'No %s found with the ID equal to %s', 'woocommerce' ), $resource_name, $id ), array( 'status' => 404 ) );
            }
            // For checking permissions, product variations are the same as the product post type
            $post_type = ( 'product_variation' === $post->post_type ) ? 'product' : $post->post_type;
            // Validate post type
            if ( $type !== $post_type ) {
                return new WP_Error( "woocommerce_api_invalid_{$resource_name}", sprintf( __( 'Invalid %s', 'woocommerce' ), $resource_name ), array( 'status' => 404 ) );
            }
            // Validate permissions
            switch ( $context ) {
                case 'read':
                    if ( ! $this->is_readable( $post ) )
                        return new WP_Error( "woocommerce_api_user_cannot_read_{$resource_name}", sprintf( __( 'You do not have permission to read this %s', 'woocommerce' ), $resource_name ), array( 'status' => 401 ) );
                    break;
                case 'edit':
                    if ( ! $this->is_editable( $post ) )
                        return new WP_Error( "woocommerce_api_user_cannot_edit_{$resource_name}", sprintf( __( 'You do not have permission to edit this %s', 'woocommerce' ), $resource_name ), array( 'status' => 401 ) );
                    break;
                case 'delete':
                    if ( ! $this->is_deletable( $post ) )
                        return new WP_Error( "woocommerce_api_user_cannot_delete_{$resource_name}", sprintf( __( 'You do not have permission to delete this %s', 'woocommerce' ), $resource_name ), array( 'status' => 401 ) );
                    break;
            }
        }
        return $id;
    }
    private function check_permission( $post, $context ) {
        $permission = false;
        if ( ! is_a( $post, 'WP_Post' ) ) {
            $post = get_post( $post );
        }
        if ( is_null( $post ) ) {
            return $permission;
        }
        $post_type = get_post_type_object( $post->post_type );
        if ( 'read' === $context ) {
            $permission = 'revision' !== $post->post_type && current_user_can( $post_type->cap->read_private_posts, $post->ID );
        } elseif ( 'edit' === $context ) {
            $permission = current_user_can( $post_type->cap->edit_post, $post->ID );
        } elseif ( 'delete' === $context ) {
            $permission = current_user_can( $post_type->cap->delete_post, $post->ID );
        }
        return apply_filters( 'woocommerce_api_check_permission', $permission, $context, $post, $post_type );
    }
    private function get_images( $product ) {
        $images = $attachment_ids = array();
        if ( $product->is_type( 'variation' ) ) {
            if ( has_post_thumbnail( $product->get_variation_id() ) ) {
                // Add variation image if set
                $attachment_ids[] = get_post_thumbnail_id( $product->get_variation_id() );
            } elseif ( has_post_thumbnail( $product->id ) ) {
                // Otherwise use the parent product featured image if set
                $attachment_ids[] = get_post_thumbnail_id( $product->id );
            }
        } else {
            // Add featured image
            if ( has_post_thumbnail( $product->id ) ) {
                $attachment_ids[] = get_post_thumbnail_id( $product->id );
            }
            // Add gallery images
            $attachment_ids = array_merge( $attachment_ids, $product->get_gallery_attachment_ids() );
        }
        // Build image data
        foreach ( $attachment_ids as $position => $attachment_id ) {
            $attachment_post = get_post( $attachment_id );
            if ( is_null( $attachment_post ) ) {
                continue;
            }
            $attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
            if ( ! is_array( $attachment ) ) {
                continue;
            }
            $images[] = array(
                'id'         => (int) $attachment_id,
                'created_at' => $this->format_datetime( $attachment_post->post_date_gmt ),
                'updated_at' => $this->format_datetime( $attachment_post->post_modified_gmt ),
                'src'        => current( $attachment ),
                'title'      => get_the_title( $attachment_id ),
                'alt'        => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
                'position'   => (int) $position,
            );
        }
        // Set a placeholder image if the product has no images set
        if ( empty( $images ) ) {
            $images[] = array(
                'id'         => 0,
                'created_at' => $this->format_datetime( time() ), // Default to now
                'updated_at' => $this->format_datetime( time() ),
                'src'        => wc_placeholder_img_src(),
                'title'      => __( 'Placeholder', 'woocommerce' ),
                'alt'        => __( 'Placeholder', 'woocommerce' ),
                'position'   => 0,
            );
        }
        return $images;
    }
    private function get_attributes( $product ) {
        $attributes = array();
        if ( $product->is_type( 'variation' ) ) {
            // variation attributes
            foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {
                // taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
                $attributes[] = array(
                    'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ), $product ),
                    'slug'   => str_replace( 'attribute_', '', str_replace( 'pa_', '', $attribute_name ) ),
                    'option' => $attribute,
                );
            }
        } else {
            foreach ( $product->get_attributes() as $attribute ) {
                $name = $this->get_attribute_options( $product->id, $attribute , "names");
                $slug = $this->get_attribute_options( $product->id, $attribute , "slugs");
                $options = array();
                foreach( $slug as $index => $code ) {
                    $options[] = array( 'name' => $name[$index] , 'slug' => $code );
                }
                $attributes[] = array(
                    'name'      => wc_attribute_label( $attribute['name'], $product ),
                    'slug'      => str_replace( 'pa_', '', $attribute['name'] ),
                    'position'  => (int) $attribute['position'],
                    'visible'   => (bool) $attribute['is_visible'],
                    'variation' => (bool) $attribute['is_variation'],
                    'options'   => $options
                );
            }
        }
        return $attributes;
    }
    private function get_downloads( $product ) {
        $downloads = array();
        if ( $product->is_downloadable() ) {
            foreach ( $product->get_files() as $file_id => $file ) {
                $downloads[] = array(
                    'id'   => $file_id, // do not cast as int as this is a hash
                    'name' => $file['name'],
                    'file' => $file['file'],
                );
            }
        }
        return $downloads;
    }
    protected function get_attribute_options( $product_id, $attribute , $field) {
        if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {
            return wc_get_product_terms( $product_id, $attribute[ 'name' ], array( 'fields' => $field  , 'orderby' => 'parent' ) );
        } elseif ( isset( $attribute['value'] ) ) {
            return array_map( 'trim', explode( '|', $attribute['value'] ) );
        }
        return array();
    }
    private function get_product_menu_order( $product ) {
        $menu_order = $product->post->menu_order;
        if ( $product->is_type( 'variation' ) ) {
            $_product = get_post( $product->get_variation_id() );
            $menu_order = $_product->menu_order;
        }
        return apply_filters( 'woocommerce_api_product_menu_order', $menu_order, $product );
    }
    private function get_variation_data( $product ) {
        $variations = array();
        foreach ( $product->get_children() as $child_id ) {
            $variation = $product->get_child( $child_id );
            if ( ! $variation->exists() ) {
                continue;
            }
            $post_data = get_post( $variation->get_variation_id() );
            $variations[] = array(
                'id'                 => $variation->get_variation_id(),
                'created_at'         => $this->format_datetime( $post_data->post_date_gmt ),
                'updated_at'         => $this->format_datetime( $post_data->post_modified_gmt ),
                'downloadable'       => $variation->is_downloadable(),
                'virtual'            => $variation->is_virtual(),
                'permalink'          => $variation->get_permalink(),
                'sku'                => $variation->get_sku(),
                'price'              => $variation->get_price(),
                'regular_price'      => $variation->get_regular_price(),
                'sale_price'         => $variation->get_sale_price() ? $variation->get_sale_price() : null,
                'taxable'            => $variation->is_taxable(),
                'tax_status'         => $variation->get_tax_status(),
                'tax_class'          => $variation->get_tax_class(),
                'managing_stock'     => $variation->managing_stock(),
                'stock_quantity'     => $variation->get_stock_quantity(),
                'in_stock'           => $variation->is_in_stock(),
                'backorders_allowed' => $variation->backorders_allowed(),
                'backordered'        => $variation->is_on_backorder(),
                'purchaseable'       => $variation->is_purchasable(),
                'visible'            => $variation->variation_is_visible(),
                'on_sale'            => $variation->is_on_sale(),
                'weight'             => $variation->get_weight() ? $variation->get_weight() : null,
                'dimensions'         => array(
                    'length' => $variation->length,
                    'width'  => $variation->width,
                    'height' => $variation->height,
                    'unit'   => get_option( 'woocommerce_dimension_unit' ),
                ),
                'shipping_class'    => $variation->get_shipping_class(),
                'shipping_class_id' => ( 0 !== $variation->get_shipping_class_id() ) ? $variation->get_shipping_class_id() : null,
                'image'             => $this->get_images( $variation ),
                'attributes'        => $this->get_attributes( $variation ),
                'downloads'         => $this->get_downloads( $variation ),
                'download_limit'    => (int) $product->download_limit,
                'download_expiry'   => (int) $product->download_expiry,
            );
        }
        return $variations;
    }
    private function get_grouped_products_data( $product ) {
        $products = array();
        foreach ( $product->get_children() as $child_id ) {
            $_product = $product->get_child( $child_id );
            if ( ! $_product->exists() ) {
                continue;
            }
            $products[] = $this->get_product_data( $_product );
        }
        return $products;
    }
    function related_ids_webservice(){
        if(!empty($_GET["ids"]))
        {
            $in = $_GET["ids"];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array)  json_decode($url_json);
            foreach ($json as $key) {
                $array = $this->get_product($key);
                $products["products"][] = $array["product"];
            }
            $json = json_encode( $products );
            echo $json;
        }
    }
    function woo2app_checkout_webservice(){
        echo apply_filters( "the_content" , "[woocommerce_checkout]" );
    }
}
?>