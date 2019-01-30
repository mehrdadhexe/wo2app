<?php
if(!class_exists( 'mr2app_custom_category' )){
    class mr2app_custom_category{
        function __construct(){
            add_action( 'init', array( $this , 'mr2app_custom_category_api_regular_url' ));
            add_filter( 'query_vars', array( $this , 'mr2app_custom_category_api_query_vars' ));
            add_action( 'parse_request', array( $this , 'mr2app_custom_category_api_parse_request' ));
        }
        function mr2app_custom_category_api_regular_url(){
            add_rewrite_rule('^mr2app/plugin_category$', 'index.php?plugin_category=$matches[1]', 'top'); //=$matches[1]
            flush_rewrite_rules();
        }
        function mr2app_custom_category_api_query_vars($query_vars) {
            $query_vars[] = 'plugin_category';
            return $query_vars;
        }
        function mr2app_custom_category_api_parse_request(&$wp){
            if ( array_key_exists( 'plugin_category', $wp->query_vars ) ) {
                $this->plugin_category();
                exit();
            }
            return;
        }
        public  function plugin_category(){
            header('Content-Type: application/json; charset=utf-8');
            global $wpdb;
            $table_name = $wpdb->prefix . "options";
            $results = $wpdb->get_results( "SELECT  * FROM $table_name WHERE option_name LIKE 'mr2app_cat_%' " ,OBJECT);
            foreach ($results as $r){
                $value = json_decode($r->option_value);
                $category = get_term_by( 'id', $value->id, 'product_cat' );
                $display_type = get_woocommerce_term_meta( $category->term_id, 'display_type' );
                $image = '';
                if ( $image_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id' ) ) {
                    $image = wp_get_attachment_url( $image_id );
                }
                $parent = explode('_',$r->option_name);
                $cats[] = array(
                    'id' => (int)$parent['2'],
                    'name' => $value->label,
                    'slug' => $category->slug,
                    'parent'=> ($value->parent == -1) ? 0 : (int)$value->parent,
                    'description'=> $category->description,
                    'display'     => $display_type ? $display_type : 'default',
                    'image'       => $image ? esc_url( $image ) : '',
                    'count'       => intval( $category->count ),
                    'order' => (int)$value->order
                );
            }
            $array = array();
            $array['product_categories'] = $cats;
            echo json_encode($array);
            return;
        }
    }
    $mr2app_custom_category = new mr2app_custom_category();
}