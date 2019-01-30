<?php
/**
 * Created by mr2app.
 * User: hani
 * Date: 12/13/17
 * Time: 6:07 PM
 */
class class_custom_order {
    function __construct(){

        add_action( 'init', array( $this , 'mr2app_custom_order_api_regular_url' ));

        add_filter( 'query_vars', array( $this , 'mr2app_custom_order_api_query_vars' ));

        add_action( 'parse_request', array( $this , 'mr2app_custom_order_api_parse_request' ));

    }

    function mr2app_custom_order_api_regular_url(){
        add_rewrite_rule('^mr2app/get_order_form$', 'index.php?get_order_form=$matches[1]', 'top'); //=$matches[1]

        flush_rewrite_rules();

    }

    function mr2app_custom_order_api_query_vars($query_vars) {

        $query_vars[] = 'get_order_form';
        return $query_vars;
    }

    function mr2app_custom_order_api_parse_request(&$wp){

        if ( array_key_exists( 'get_order_form', $wp->query_vars ) ) {

            $this->get_order_form();

            exit();

        }
        return;
    }

    public function get_order_form(){
        header('Content-Type: application/json; charset=utf-8');
        $args      = array(
            'post_type'   => 'woo2app_order',
            'post_status' => 'draft',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $the_query = get_posts( $args );
        $array = array();
        foreach ($the_query as $f){
            $array['order_form'][] = array(
                'title' => $f->post_title,
                'name' => $f->post_content,
                'default' => get_post_meta($f->ID , 'default' , true)? get_post_meta($f->ID , 'default' , true) : '',
                'required' => get_post_meta($f->ID , 'required', true)? get_post_meta($f->ID , 'required' , true) : '0',
                'active' => get_post_meta($f->ID , 'active', true)? get_post_meta($f->ID , 'active' , true) : '0',
                'display' => get_post_meta($f->ID , 'display', true)? get_post_meta($f->ID , 'display' , true) : '0',
                'order' => $f->menu_order,
                'values' => (get_post_meta($f->ID , 'type' ,true) == 'list' || get_post_meta($f->ID , 'type',true) == 'radio_button') ? explode(',',get_post_meta($f->ID , 'values',true)) : array(),
                'relation' => get_post_meta($f->ID , 'relation', true)? get_post_meta($f->ID , 'relation' , true) : '',
                'type' => get_post_meta($f->ID , 'type', true)? get_post_meta($f->ID , 'type' , true) : '',
                'validate' => get_post_meta($f->ID , 'validate',true) ? get_post_meta($f->ID , 'validate',true) : 'general',
            );
        }

        echo json_encode($array);
        return;
    }
}
// Add meta box
add_action( 'add_meta_boxes', 'tcg_tracking_box' );
function tcg_tracking_box() {
    global $post;
    $map = get_post_meta($post->ID , '_order_map' , true);
    if($map){
        add_meta_box(
            'tcg-tracking-modal',
            ' محل سفارش در روی نقشه',
            'tcg_meta_box_callback',
            'shop_order',
            'side',
            'default'
        );
    }
}
// Callback
function tcg_meta_box_callback( $post )
{
    $value = get_post_meta( $post->ID, '_order_map', true );
    $loc = ! empty( $value ) ? esc_attr( $value ) : '';
    $loc = explode(',',$loc);
    $lat = $loc[0];
    $lng = $loc[1];
    ?>
    <style type="text/css">
        #map-canvas {

            width:    100%;
            height:   200px;
        }
    </style>

    <div id="map-canvas"></div><!-- #map-canvas -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1grFb5dYPNOQ5FaDHMkZLmVz3s3OerbI"></script>
    <script type="text/javascript">
        google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );
        var map;
        var markers = [];
        function gmaps_results_initialize() {
            map = new google.maps.Map( document.getElementById( 'map-canvas' ), {
                zoom:           13,
                center:         new google.maps.LatLng( <?= $lat ;?>, <?= $lng ;?> ),
            });
            var  marker = new google.maps.Marker({

                position: new google.maps.LatLng( <?= $lat ;?>, <?= $lng ;?> ),
                map:      map,
                animation: google.maps.Animation.BOUNCE
            });
//            markers.push(marker);
//            google.maps.event.addListener(map, 'click', function(event) {
//                placeMarker(map, event.latLng);
//            });
        }
        function placeMarker(map, location) {
            //deletetMarkers();
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
            // alert(location.lat() + location.lng())
            markers.push(marker);
        }
        function setMapOnAll(map) {
            for(var i = 0 ;i < markers.length; i++){
                markers[i].setMap(map);
            }
        }
        function clearMarkers() {
            setMapOnAll(null);
        }
        function deletetMarkers() {
            clearMarkers();
            markers = [];
        }

    </script>
    <?php
}