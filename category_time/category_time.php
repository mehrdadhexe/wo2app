<?php


add_action('product_cat_edit_form_fields','taxonomy_edit_form_fields');
function taxonomy_edit_form_fields () {
    ?>
    <tr>
        <th colspan="2">
            <hr>
        </th>
    </tr>
    <tr>
        <th >
            <a style="cursor: pointer" onclick="category_time()">
                کلیک کنید
            </a>
        </th>
        <td>
            <p>
                این بخش نیازمند فعال سازی
                <a target="_blank" href="http://mr2app.com/blog/shop-time/">
                    ماژول چیدمان سفارشی دسته بندی محصولات در اپلیکیشن
                </a>
                ، در اپلیکیشن می باشد.
            </p>

        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <?php
        $x = get_option("woo2app_product_cat_".$_GET['tag_ID']);
        ?>
        <th valign="top" scope="row">
            <label for="catpic"> شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['shanbe'][0];?>" style="width:20%;float:right"  name="shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['shanbe'][1];?>" style="width:20%;float:right"  name="shanbe[]"/>
                <label style="float:right;margin-right:20px;margin-left:20px"> و </label>

                <label style="float:right"> از </label> <input type="text" value="<?= $x['shanbe'][2];?>" style="width:20%;float:right"  name="shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text" value="<?= $x['shanbe'][3];?>"   style="width:20%;float:right"  name="shanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> یک شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text" value="<?= $x['yekshanbe'][0];?>" style="width:20%;float:right"  name="yekshanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['yekshanbe'][1];?>" style="width:20%;float:right"  name="yekshanbe[]"/> <label style="float:right;margin-right:20px;margin-left:20px"> و </label>
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['yekshanbe'][2];?>" style="width:20%;float:right"  name="yekshanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['yekshanbe'][3];?>"  style="width:20%;float:right"  name="yekshanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> دو شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['2shanbe'][0];?>" style="width:20%;float:right"  name="2shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['2shanbe'][1];?>"  style="width:20%;float:right"  name="2shanbe[]"/>

                <label style="float:right;margin-right:20px;margin-left:20px"> و </label>

                <label style="float:right"> از </label> <input type="text"  value="<?= $x['2shanbe'][2];?>" style="width:20%;float:right"  name="2shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['2shanbe'][3];?>"  style="width:20%;float:right"  name="2shanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> سه شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['3shanbe'][0];?>"  style="width:20%;float:right"  name="3shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"    value="<?= $x['3shanbe'][1];?>"  style="width:20%;float:right"  name="3shanbe[]"/>

                <label style="float:right;margin-right:20px;margin-left:20px"> و </label>

                <label style="float:right"> از </label> <input type="text"   value="<?= $x['3shanbe'][2];?>"  style="width:20%;float:right"  name="3shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"    value="<?= $x['3shanbe'][3];?>"  style="width:20%;float:right"  name="3shanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> چهار شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text" value="<?= $x['4shanbe'][0];?>" style="width:20%;float:right"  name="4shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text" value="<?= $x['4shanbe'][1];?>"  style="width:20%;float:right"  name="4shanbe[]"/> <label style="float:right;margin-right:20px;margin-left:20px"> و </label>
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['4shanbe'][2];?>" style="width:20%;float:right"  name="4shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['4shanbe'][3];?>"  style="width:20%;float:right"  name="4shanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> پنج شنبه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['5shanbe'][0];?>"  style="width:20%;float:right"  name="5shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"   value="<?= $x['5shanbe'][1];?>"  style="width:20%;float:right"  name="5shanbe[]"/> <label style="float:right;margin-right:20px;margin-left:20px"> و </label>
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['5shanbe'][2];?>"  style="width:20%;float:right"  name="5shanbe[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['5shanbe'][3];?>"   style="width:20%;float:right"  name="5shanbe[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th valign="top" scope="row">
            <label for="catpic"> جمعه </label>
        </th>
        <td class="text-center">
            <div style="width:500px;margin:0 auto;">
                <label style="float:right"> از </label> <input type="text"  value="<?= $x['jome'][0];?>"  style="width:20%;float:right"  name="jome[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['jome'][1];?>"   style="width:20%;float:right" name="jome[]"/> <label style="float:right;margin-right:20px;margin-left:20px"> و </label>
                <label style="float:right"> از </label> <input type="text" value="<?= $x['jome'][2];?>"   style="width:20%;float:right"  name="jome[]"/>
                <label style="float:right"> تا </label> <input type="text"  value="<?= $x['jome'][3];?>"   style="width:20%;float:right"  name="jome[]"/>
            </div>
        </td>
    </tr>
    <tr style="display: none;" class="category_time form-field">
        <th colspan="2">
            <a style="cursor: pointer" onclick="close_category_time()">
                بستن
            </a>
        </th>
    </tr>
    <th colspan="2">
        <hr>
    </th>

    <script>
        function category_time(){
            jQuery(".category_time").removeAttr('style')
        }
        function close_category_time() {
            jQuery(".category_time").attr('style','display:none')
        }
    </script>

    <?php
}

add_filter('edited_terms', 'update_my_category_fields');
function update_my_category_fields($term_id) {
    if($_POST['taxonomy'] == 'product_cat'):
        //$tag_extra_fields = get_option(MY_CATEGORY_FIELDS);
        // $tag_extra_fields[$term_id]['shanbe_am_az'] = strip_tags($_POST['shanbe_am_az']);
        // $tag_extra_fields[$term_id]['shanbe_am_ta'] = strip_tags($_POST['shanbe_am_ta']);
        // $tag_extra_fields[$term_id]['shanbe_pm_az'] = strip_tags($_POST['shanbe_pm_az']);
        $tag_extra_fields['shanbe'] = ($_POST['shanbe']);
        $tag_extra_fields['yekshanbe'] = ($_POST['yekshanbe']);
        $tag_extra_fields['2shanbe'] = ($_POST['2shanbe']);
        $tag_extra_fields['3shanbe'] = ($_POST['3shanbe']);
        $tag_extra_fields['4shanbe'] = ($_POST['4shanbe']);
        $tag_extra_fields['5shanbe'] = ($_POST['5shanbe']);
        $tag_extra_fields['jome'] = ($_POST['jome']);


        update_option("woo2app_product_cat_".$_POST['tag_ID'], $tag_extra_fields);
    endif;
}


add_action( 'init', 'mr2app_category_time_api_regular_url');
add_filter( 'query_vars', 'mr2app_category_time_api_query_vars');
add_action( 'parse_request', 'mr2app_category_time_api_parse_request');
function mr2app_category_time_api_regular_url(){
    add_rewrite_rule('^mr2app/time_category$', 'index.php?time_category=$matches[1]', 'top'); //=$matches[1]
    add_rewrite_rule('^mr2app/check_product$', 'index.php?check_product=$matches[1]', 'top'); //=$matches[1]
    flush_rewrite_rules();
}
function mr2app_category_time_api_query_vars($query_vars) {
    $query_vars[] = 'time_category';
    $query_vars[] = 'check_product';
    return $query_vars;
}

function mr2app_category_time_api_parse_request(&$wp){

    if ( array_key_exists( 'time_category', $wp->query_vars ) ) {
        woocats();
        exit();
    }
    if ( array_key_exists( 'check_product', $wp->query_vars ) ) {
        woo2app_check_product();
        exit();
    }
    return;
}

function woo2app_check_product(){

    if(isset($_GET["in"])){
        $in = $_GET['in'];
        $slashless = stripcslashes($in);
        $url_json = urldecode($slashless);
        $x = $json = (array) json_decode($url_json);
        // var_dump($json);
        date_default_timezone_set('Asia/Tehran');
        $today = date("w");
        $h = date('H.i');
        $array = array();
        $i = -1;
        foreach ($json as $key) {
            $i++;
            $term_list = wp_get_post_terms($key->product_id, 'product_cat', array('fields' => 'ids'));
            //$this->is_schedule();

            foreach ($term_list as $c) {

                $check = $this->is_schedule($c , $today , $h);
                if($check == 0){
                    //unset($x[$i]);
                    $array[] = $x[$i];
                }
            }
        }
        ob_clean();
        echo json_encode($array);
    }
    return ;
}
function pa_category_top_parent_id ($catid) {

    while ($catid) {
        $cat = get_category($catid); // get the object for the catid
        $catid = $cat->category_parent; // assign parent ID (if exists) to $catid
        // the while loop will continue whilst there is a $catid
        // when there is no longer a parent $catid will be NULL so we can assign our $catParent
        $catParent = $cat->cat_ID;
    }

    return $catParent;
}
function woocats(){
    header('Content-Type: application/json; charset=utf-8');
    ob_start();
    $product_categories = array();
    $terms = get_terms(  'product_cat' );
    //var_dump($terms);
    foreach ( $terms as $term_id ) {
        //var_dump($term_id);
        $product_categories[] =   get_product_category($term_id)  ;
    }
    //var_dump($product_categories);
    //return;
    $array = array( 'product_categories' => $product_categories );
    date_default_timezone_set('Asia/Tehran');
    $array['long_time'] = time();
    $array['date_time'] = date('Y/m/d H:i:s');
    ob_clean();
    echo json_encode($array);
}

function get_product_category( $term, $fields = null ) {

    //return $term->term_id;
    //$term = get_term( $id, 'product_cat' );
    // if ( is_wp_error( $term ) || is_null( $term ) ) {
    //     return 0;
    // }
    $term_id = $term->term_id;

    // Get category display type
    $display_type = get_woocommerce_term_meta( $term_id, 'display_type' );

    // Get category image
    $image = '';
    if ( $image_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id' ) ) {
        $image = wp_get_attachment_url( $image_id );
    }

    //return $term_id;
    date_default_timezone_set('Asia/Tehran');

    $today = date("w");
    $h = date('H.i');
    //echo $ds_schedule = $this->ds_schedule($term_id);

    return $product_category = array(
        'id'          => $term_id,
        'name'        => $term->name,
        'slug'        => $term->slug,
        'parent'      => $term->parent,
        'description' => $term->description,
        'display'     => $display_type ? $display_type : 'default',
        'image'       => $image ? esc_url( $image ) : '',
        'count'       => intval( $term->count ),
        'is_schedule' => is_schedule($term_id , $today , $h),
        'ds_schedule' => ds_schedule($term_id , $today),
        'image2' => get_option('z_taxonomy_image'.$term_id),
    );
    //return array( 'product_category' => apply_filters( 'woocommerce_api_product_category_response', $product_category, $id, $fields, $term, $this ) );
}

function ds_schedule($id , $today){
    $ds = get_option("woo2app_product_cat_".$id);
    if(!$ds) return array();
    $arr = array();

    $arr['shanbe'][0] = $ds["shanbe"][0];
    $arr['shanbe'][1] = $ds["shanbe"][1];
    $arr['shanbe'][2] = $ds["shanbe"][2];
    $arr['shanbe'][3] = $ds["shanbe"][3];

    $arr['yekshanbe'][0] = $ds["yekshanbe"][0];
    $arr['yekshanbe'][1] = $ds["yekshanbe"][1];
    $arr['yekshanbe'][2] = $ds["yekshanbe"][2];
    $arr['yekshanbe'][3] = $ds["yekshanbe"][3];

    $arr['2shanbe'][0] = $ds["2shanbe"][0];
    $arr['2shanbe'][1] = $ds["2shanbe"][1];
    $arr['2shanbe'][2] = $ds["2shanbe"][2];
    $arr['2shanbe'][3] = $ds["2shanbe"][3];

    $arr['3shanbe'][0] = $ds["3shanbe"][0];
    $arr['3shanbe'][1] = $ds["3shanbe"][1];
    $arr['3shanbe'][2] = $ds["3shanbe"][2];
    $arr['3shanbe'][3] = $ds["3shanbe"][3];

    $arr['4shanbe'][0] = $ds["4shanbe"][0];
    $arr['4shanbe'][1] = $ds["4shanbe"][1];
    $arr['4shanbe'][2] = $ds["4shanbe"][2];
    $arr['4shanbe'][3] = $ds["4shanbe"][3];

    $arr['5shanbe'][0] = $ds["5shanbe"][0];
    $arr['5shanbe'][1] = $ds["5shanbe"][1];
    $arr['5shanbe'][2] = $ds["5shanbe"][2];
    $arr['5shanbe'][3] = $ds["5shanbe"][3];

    $arr['jome'][0] = $ds["jome"][0];
    $arr['jome'][1] = $ds["jome"][1];
    $arr['jome'][2] = $ds["jome"][2];
    $arr['jome'][3] = $ds["jome"][3];

    return $arr;

}

function is_schedule( $id , $today ,$h){
    $is_in_table = get_option("woo2app_product_cat_".$id);
    if(!$is_in_table) return get_parent_schedule( $id , $today ,$h);
    switch ($today) {
        case '6':
            if((float)$is_in_table['shanbe'][0] == "" &&  (float)$is_in_table['shanbe'][2] == "" ) return get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['shanbe'][0] && (float)$h < (float)$is_in_table['shanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['shanbe'][2] && (float)$h < (float)$is_in_table['shanbe'][3] ) return 1;
            return 0;
            break;
        case '0':
            if((float)$is_in_table['yekshanbe'][0] == "" &&  (float)$is_in_table['yekshanbe'][2] == "" ) return get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['yekshanbe'][0] && (float)$h < (float)$is_in_table['yekshanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['yekshanbe'][2] && (float)$h < (float)$is_in_table['yekshanbe'][3] ) return 1;
            return 0;
            break;
        case '1':
            if((float)$is_in_table['2shanbe'][0] == "" &&  (float)$is_in_table['2shanbe'][2] == "" ) return  get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['2shanbe'][0] && (float)$h < (float)$is_in_table['2shanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['2shanbe'][2] && (float)$h < (float)$is_in_table['2shanbe'][3] ) return 1;
            return 0;
            break;
        case '2':
            if((float)$is_in_table['3shanbe'][0] == "" &&  (float)$is_in_table['3shanbe'][2] == "" ) return  get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['3shanbe'][0] && (float)$h < (float)$is_in_table['3shanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['3shanbe'][2] && (float)$h < (float)$is_in_table['3shanbe'][3] ) return 1;
            return 0;
            break;
        case '3':
            if((float)$is_in_table['4shanbe'][0] == "" &&  (float)$is_in_table['4shanbe'][2] == "" ) return  get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['4shanbe'][0] && (float)$h < (float)$is_in_table['4shanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['4shanbe'][2] && (float)$h < (float)$is_in_table['4shanbe'][3] ) return 1;
            return 0;
            break;
        case '4':
            if((float)$is_in_table['5shanbe'][0] == "" &&  (float)$is_in_table['5shanbe'][2] == "" ) return  get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['5shanbe'][0] && (float)$h < (float)$is_in_table['5shanbe'][1] ) return 1;
            if((float)$h > (float)$is_in_table['5shanbe'][2] && (float)$h < (float)$is_in_table['5shanbe'][3] ) return 1;
            return 0;
            break;
        case '5':
            if((float)$is_in_table['jome'][0] == "" &&  (float)$is_in_table['jome'][2] == "" ) return  get_parent_schedule( $id , $today ,$h);
            if((float)$h > (float)$is_in_table['jome'][0] && (float)$h < (float)$is_in_table['jome'][1] ) return 1;
            if((float)$h > (float)$is_in_table['jome'][2] && (float)$h < (float)$is_in_table['jome'][3] ) return 1;
            return 0;
            break;
        default:
            return 0;
    }
}
function get_parent_schedule( $id , $today ,$h){
    $cat = get_term($id);
    if($cat->parent == 0) return 1;
    return is_schedule($cat->parent , $today ,$h);
}
?>
