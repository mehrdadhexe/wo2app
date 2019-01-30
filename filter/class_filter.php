<?php
class mr2app_filter {
    function __construct() {
        add_action( 'init', array( $this, 'mr2app_filter_api_regular_url' ) );
        add_filter( 'query_vars', array( $this, 'mr2app_filter_api_query_vars' ) );
        add_action( 'parse_request', array( $this, 'mr2app_filter_api_parse_request' ) );
    }
    function mr2app_filter_api_regular_url() {
        add_rewrite_rule( '^mr2app/category_feature$', 'index.php?category_feature=$matches[1]', 'top' ); //=$matches[1]
        flush_rewrite_rules();
    }
    function mr2app_filter_api_query_vars( $query_vars ) {
        $query_vars[] = 'category_feature';
        return $query_vars;
    }
    function mr2app_filter_api_parse_request( &$wp ) {
        if ( array_key_exists( 'category_feature', $wp->query_vars ) ) {
            $this->category_feature();
            exit();
        }
        return;
    }
    public $arr = array();
    public function get_cat( $args, $slug ) {
        $subcats = get_categories( $args );
        foreach ( $subcats as $cat ) {
            $args = array(
                'hierarchical'     => 1,
                'show_option_none' => '',
                'hide_empty'       => 0,
                'parent'           => $cat->term_id,
                'taxonomy'         => 'product_cat'
            );
            $this->get_cat( $args, $cat->slug );
        }
        $this->arr[] = $slug;
    }
    function get_the_custom_taxonomies( $post ) {
        //$post =
        $ids = join(",",$post);
        global $wpdb;
        $results = $wpdb->get_results("
				select * from wp_term_relationships r, wp_term_taxonomy tt, wp_terms t 
				where r.term_taxonomy_id = tt.term_taxonomy_id 
				and tt.term_id = t.term_id 
				AND tt.taxonomy LIKE 'pa_%'  
				AND r.object_id IN ($ids)"
            ,OBJECT);
        return $results;
    }
    public function category_feature() {
        header( 'Content-Type: application/json; charset=utf-8' );
        //$x = $this->get_the_custom_taxonomies( array(46,47) );
//
        //var_dump($x);return;
        $result = array();
        $result['status'] = false;
        if ( isset( $_GET['in'] ) ) {
            $in = $_GET['in'];
            $slashless = stripcslashes( $in );
            $url_json = urldecode( $slashless );
            $json = json_decode( $url_json );
            $json->slug;
            $category = array();
            //$category[] = $json->slug;
            $cat        = get_term_by( 'slug', $json->slug, 'product_cat' );
            $category[] = $cat->slug;
            $args       = array(
                'hierarchical'     => 1,
                'show_option_none' => '',
                'hide_empty'       => 0,
                'parent'           => $cat->term_id,
                'taxonomy'         => 'product_cat'
            );
            $this->get_cat( $args, $json->slug );
            $product = new WC_Product_Query( array(
                'limit'    => - 1,
                'orderby'  => 'date',
                'order'    => 'DESC',
                'return'   => 'ids',
                'category' => $this->arr
            ) );
            $products = $product->get_products();
            $features = array();
            $x = array();
            $y = array();
            $x = $this->get_the_custom_taxonomies($products);
            foreach ($x as $xx){
                $attr[]= array(
                    'slug' => $xx->taxonomy,
                    'label' =>wc_attribute_label($xx->taxonomy),
                );
                $options[] = array(
                    'name' => $xx->name,
                    'parent'=>$xx->taxonomy,
                    'slug' => $xx->slug
                );
            }
            $x = array_map( "unserialize", array_unique( array_map( "serialize", $attr ) ) );
            $y = array_map( "unserialize", array_unique( array_map( "serialize", $options ) ) );
            $z = array();
            foreach ( $x as $key ) {
                $yyy = array();
                foreach ( $y as $yy ) {
                    if ( $yy['parent'] != $key['slug'] ) {
                        continue;
                    }
                    $yyy[] = array(
                        'slug' => $yy['slug'],
                        'label' => $yy['name']
                    );
                }
                $z[] = array(
                    'slug' => $key['slug'],
                    'label' => $key['label'],
                    'options' => $yyy
                );
            }
            $result = $z;
        }
        $array = array();
        sort($result);
        $array['result'] = $result;
        echo json_encode( $array );
        return;
    }
}