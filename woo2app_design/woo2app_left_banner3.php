<style>    .banner:hover{        border:2px solid #555;    }</style><link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /><script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script><?phpif(isset($_POST['submit_banner'])){    if($_REQUEST["action1"] && $_REQUEST["action2"] && $_REQUEST["action3"]  && $_REQUEST["pic1"]  && $_REQUEST["pic2"]  && $_REQUEST["pic3"] ) {        $title    = "بنر 3 تایی -  چپ";        $type     = "";        $pic     = "";        $showtype = 7;        $value1   = "";        $value2   = "";        $value3   = "";        $value4   = "";        if ( $_REQUEST["action1"] == 1 ) {            $value1 = $_REQUEST["product1"];        }        if ( $_REQUEST["action1"] == 2 ) {            $value1 = $_REQUEST["cat1"];        }        if ( $_REQUEST["action1"] == 3 ) {            $value1 = $_REQUEST["link1"];        }        if ( $_REQUEST["action1"] == 4 ) {            $value1 = $_REQUEST["post1"];        }        if ( $_REQUEST["action1"] == 5 ) {            $value1 = $_REQUEST["post_cat1"];        }        if ( $_REQUEST["action2"] == 1 ) {            $value2 = $_REQUEST["product2"];        }        if ( $_REQUEST["action2"] == 2 ) {            $value2 = $_REQUEST["cat2"];        }        if ( $_REQUEST["action2"] == 3 ) {            $value2 = $_REQUEST["link2"];        }        if ( $_REQUEST["action2"] == 4 ) {            $value2 = $_REQUEST["post2"];        }        if ( $_REQUEST["action2"] == 5 ) {            $value2 = $_REQUEST["post_cat2"];        }        if ( $_REQUEST["action3"] == 1 ) {            $value3 = $_REQUEST["product3"];        }        if ( $_REQUEST["action3"] == 2 ) {            $value3 = $_REQUEST["cat3"];        }        if ( $_REQUEST["action3"] == 3 ) {            $value3 = $_REQUEST["link3"];        }        if ( $_REQUEST["action3"] == 4 ) {            $value3 = $_REQUEST["post3"];        }        if ( $_REQUEST["action3"] == 5 ) {            $value3 = $_REQUEST["post_cat3"];        }        $arr['banner4']             = array();        $arr['banner4'][0]['title'] = $_REQUEST["title1"];        $arr['banner4'][0]['type']  = (isset($_REQUEST["sub_category_1"]) && $_REQUEST["sub_category_1"] == 'on') ? '7' : $_REQUEST["action1"];        $arr['banner4'][0]['value'] = $value1;        if(isset($_REQUEST["sub_category_1"]) && $_REQUEST["sub_category_1"] == 'on'){            $x = get_term_by('slug', $value1, 'product_cat');            if($x){                $arr['banner4'][0]['value'] = $x->term_id;            }        }        $arr['banner4'][0]['pic']   = $_REQUEST["pic1"];        $arr['banner4'][1]['title'] = $_REQUEST["title2"];        $arr['banner4'][1]['type']  = (isset($_REQUEST["sub_category_2"]) && $_REQUEST["sub_category_2"] == 'on') ? '7' : $_REQUEST["action2"];        $arr['banner4'][1]['value'] = $value2;        if(isset($_REQUEST["sub_category_2"]) && $_REQUEST["sub_category_2"] == 'on'){            $x = get_term_by('slug', $value2, 'product_cat');            if($x){                $arr['banner4'][1]['value'] = $x->term_id;            }        }        $arr['banner4'][1]['pic']   = $_REQUEST["pic2"];        $arr['banner4'][2]['title'] = $_REQUEST["title3"];        $arr['banner4'][2]['type']  = (isset($_REQUEST["sub_category_3"]) && $_REQUEST["sub_category_3"] == 'on') ? '7' : $_REQUEST["action3"];        $arr['banner4'][2]['value'] = $value3;        if(isset($_REQUEST["sub_category_3"]) && $_REQUEST["sub_category_3"] == 'on'){            $x = get_term_by('slug', $value3, 'product_cat');            if($x){                $arr['banner4'][2]['value'] = $x->term_id;            }        }        $arr['banner4'][2]['pic']   = $_REQUEST["pic3"];        $value = ( json_encode( $arr ) );        global $wpdb;        $table_name = $wpdb->prefix . "woo2app_mainpage";        $res        = $wpdb->get_results( "select MAX(mp_order) AS max_order from $table_name" );        if ( ! is_null( $res[0]->max_order ) ) {            foreach ( $res as $key ) {                $max = $key->max_order + 1;            }        } else {            $max = 1;        }        $r = $wpdb->query( $wpdb->prepare( "INSERT INTO $table_name 		( mp_title , mp_type , mp_value , mp_showtype , mp_pic , mp_order ,mp_sort) 		VALUES ( %s, %d, %s, %d, %s, %d, %s )", $title, $type, $value, $showtype, $pic, $max, 100 ) );        if ( $r ) {            echo "<p style='color:green'>". "بنر ذخیره شد .از قسمت مرتب سازی مرتب کنید." .'</p>';        }    }}$args = array(    'hide_empty' => 0 ,    'taxonomy' => 'product_cat');$cats = get_categories($args);$args = array(    'hide_empty' => 0 ,    'taxonomy' => 'category');$post_cats = get_categories($args);?><form action="" method="post">    <table class="form-table " style="float:right;direction: rtl;width:50%">        <tbody>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > عنوان </label>            </th>            <td class="forminp">                <input type="text"  name="title1"  value="" placeholder="عنوان">            </td>        </tr>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > اکشن *  </label>            </th>            <td class="forminp">                <select class="form-control action" id="action1" name="action1" required="required">                    <option value="0"> لطفا یکی از موارد زیر را انتخاب کنید</option>                    <option value="1" >محصول</option>                    <option value="2">دسته بندی محصولات</option>                    <option value="3">لینک</option>                    <option value="4"> انتخاب پست </option>                    <option value="5">  دسته بندی پست ها </option>                    <option value="6"> بدون اکشن </option>                </select>            </td>        </tr>        <tr valign="top" class="el_type_action1" id="action1_product" style="display: none">            <th scope="row" class="titledesc">                <label > محصول </label>            </th>            <td class="forminp">                <input class="search_product_banner" id="search_product_banner1" data-id="1" placeholder="محصول را جستجو کنید ...." type="text">                <select id="value_post_items_menu1" name="product1" style="width:200px" class="form-control"></select>                <img style="display:none;" height="25" width="25" id="img_load1" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">            </td>        </tr>        <tr valign="top" class="el_type_action1" id="action1_cat" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی </label>            </th>            <td class="forminp">                <select class="form-control" name="cat1" id="">                    <?php                    foreach ($cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>                <input type="checkbox" name="sub_category_1"/> باز کردن زیر دسته های این دسته در اپلیکیشن            </td>        </tr>        <tr valign="top" class="el_type_action1" id="action1_link" style="display: none">            <th scope="row" class="titledesc">                <label > لینک کامل با http  * </label>            </th>            <td class="forminp">                <input type="text"  name="link1" id="" value="" placeholder="لینک">                <p>                    در صورتی که میخواهید به یک برگه یا پست لینک ایجاد کند میتوانید                    <a href="https://mr2app.com/blog/wp-page-link" target="_blank">                        این آموزش                    </a>                    را مطالعه کنید                </p>            </td>        </tr>        <tr valign="top" class="el_type_action1" id="action1_post" style="display: none">            <th scope="row" class="titledesc">                <label > پست </label>            </th>            <td class="forminp">                <select name="post1"  id="value_post_notif"  style="width: 500px;" class="js-data-example-ajax"></select>            </td>        </tr>        <tr valign="top" class="el_type_action1" id="action1_post_cats" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی پست </label>            </th>            <td class="forminp">                <select class="form-control" name="post_cat1" id="post_cat">                    <?php                    foreach ($post_cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->term_id; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>            </td>        </tr>        <tr   style="border-bottom: 1px solid #ccc;">            <th scope="row" class="titledesc">                <label >  عکس  *</label>                <p style="color: red">                    ابعاد مثلا 512 در 1024                    <br>                    یا 1024 در 2048                </p>            </th>            <td class="forminp">                <input type="hidden" id="banner1_val" name="pic1">                <img id="banner1" class="banner" style="cursor: pointer;width: 128px;height: 256px" src="http://placehold.it/512x1024&text=1x2">            </td>        </tr>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > عنوان </label>            </th>            <td class="forminp">                <input type="text"  name="title2"  value="" placeholder="عنوان">            </td>        </tr>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > اکشن *  </label>            </th>            <td class="forminp">                <select class="form-control action" id="action2" name="action2" required="required">                    <option value="0"> لطفا یکی از موارد زیر را انتخاب کنید</option>                    <option value="1" >محصول</option>                    <option value="2">دسته بندی محصولات</option>                    <option value="3">لینک</option>                    <option value="4"> انتخاب پست </option>                    <option value="5">  دسته بندی پست ها </option>                    <option value="6"> بدون اکشن </option>                </select>            </td>        </tr>        <tr valign="top" class="el_type_action2" id="action2_product" style="display: none">            <th scope="row" class="titledesc">                <label > محصول </label>            </th>            <td class="forminp">                <input class="search_product_banner" id="search_product_banner2" data-id="2" placeholder="محصول را جستجو کنید ...." type="text">                <select id="value_post_items_menu2" name="product2" style="width:200px" class="form-control"></select>                <img style="display:none;" height="25" width="25" id="img_load2" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">            </td>        </tr>        <tr valign="top" class="el_type_action2" id="action2_cat" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی </label>            </th>            <td class="forminp">                <select class="form-control" name="cat2" id="">                    <?php                    foreach ($cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>                <input type="checkbox" name="sub_category_2"/> باز کردن زیر دسته های این دسته در اپلیکیشن            </td>        </tr>        <tr valign="top" class="el_type_action2" id="action2_link" style="display: none">            <th scope="row" class="titledesc">                <label > لینک کامل با http  * </label>            </th>            <td class="forminp">                <input type="text"  name="link2" id="" value="" placeholder="لینک">                <p>                    در صورتی که میخواهید به یک برگه یا پست لینک ایجاد کند میتوانید                    <a href="https://mr2app.com/blog/wp-page-link" target="_blank">                        این آموزش                    </a>                    را مطالعه کنید                </p>            </td>        </tr>        <tr valign="top" class="el_type_action2" id="action2_post" style="display: none">            <th scope="row" class="titledesc">                <label > پست </label>            </th>            <td class="forminp">                <select name="post2"  id="value_post_notif1"  style="width: 500px;" class="js-data-example-ajax"></select>            </td>        </tr>        <tr valign="top" class="el_type_action2" id="action2_post_cats" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی پست </label>            </th>            <td class="forminp">                <select class="form-control" name="post_cat2" id="post_cat">                    <?php                    foreach ($post_cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->term_id; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>            </td>        </tr>        <tr   style="border-bottom: 1px solid #ccc;">            <th scope="row" class="titledesc">                <label >  عکس  *</label>                <p style="color: red">                    ابعاد مثلا 512 در 512                    <br>                    یا 1024 در 1024                </p>            </th>            <td class="forminp">                <input type="hidden" id="banner2_val" name="pic2">                <img id="banner2" class="banner" style="cursor: pointer;width: 128px;height: 128px" src="http://placehold.it/512x512&text=1x1">            </td>        </tr>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > عنوان </label>            </th>            <td class="forminp">                <input type="text"  name="title3"  value="" placeholder="عنوان">            </td>        </tr>        <tr valign="top" class="">            <th scope="row" class="titledesc">                <label > اکشن *  </label>            </th>            <td class="forminp">                <select class="form-control action" id="action3" name="action3" required="required">                    <option value="0"> لطفا یکی از موارد زیر را انتخاب کنید</option>                    <option value="1" >محصول</option>                    <option value="2">دسته بندی محصولات</option>                    <option value="3">لینک</option>                    <option value="4"> انتخاب پست </option>                    <option value="5">  دسته بندی پست ها </option>                    <option value="6"> بدون اکشن </option>                </select>            </td>        </tr>        <tr valign="top" class="el_type_action3" id="action3_product" style="display: none">            <th scope="row" class="titledesc">                <label > محصول </label>            </th>            <td class="forminp">                <input class="search_product_banner" id="search_product_banner3" data-id="3" placeholder="محصول را جستجو کنید ...." type="text">                <select id="value_post_items_menu3" name="product3" style="width:200px" class="form-control"></select>                <img style="display:none;" height="25" width="25" id="img_load3" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">            </td>        </tr>        <tr valign="top" class="el_type_action3" id="action3_cat" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی </label>            </th>            <td class="forminp">                <select class="form-control" name="cat3" id="">                    <?php                    foreach ($cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>                <input type="checkbox" name="sub_category_3"/> باز کردن زیر دسته های این دسته در اپلیکیشن            </td>        </tr>        <tr valign="top" class="el_type_action3" id="action3_link" style="display: none">            <th scope="row" class="titledesc">                <label > لینک کامل با http  * </label>            </th>            <td class="forminp">                <input type="text"  name="link3" id="" value="" placeholder="لینک">                <p>                    در صورتی که میخواهید به یک برگه یا پست لینک ایجاد کند میتوانید                    <a href="https://mr2app.com/blog/wp-page-link" target="_blank">                        این آموزش                    </a>                    را مطالعه کنید                </p>            </td>        </tr>        <tr valign="top" class="el_type_action3" id="action3_post" style="display: none">            <th scope="row" class="titledesc">                <label > پست </label>            </th>            <td class="forminp">                <select name="post3"  id="value_post_notif3"  style="width: 500px;" class="js-data-example-ajax"></select>            </td>        </tr>        <tr valign="top" class="el_type_action3" id="action3_post_cats" style="display: none">            <th scope="row" class="titledesc">                <label > دسته بندی پست </label>            </th>            <td class="forminp">                <select class="form-control" name="post_cat3" id="post_cat">                    <?php                    foreach ($post_cats as $cat) {                        ?>                        <option style="text-align:right;" value="<?= $cat->term_id; ?>"><?= $cat->name; ?></option>                        <?php                    }                    ?>                </select>            </td>        </tr>        <tr   style="border-bottom: 1px solid #ccc;">            <th scope="row" class="titledesc">                <label >  عکس  *</label>                <p style="color: red">                    ابعاد مثلا 512 در 512                    <br>                    یا 1024 در 1024                </p>            </th>            <td class="forminp">                <input type="hidden" id="banner3_val" name="pic3">                <img id="banner3" class="banner" style="cursor: pointer;width: 128px;height: 128px" src="http://placehold.it/512x512&text=1x1">            </td>        </tr>        <tr >            <th scope="row" class="titledesc">            </th>            <td class="forminp">                <input type="submit"  value="اضافه"  name="submit_banner">            </td>        </tbody>    </table>    <div style="width:48%;float:right;"  >        <div style="float: left;width: 128px;margin:2px">            <img id="p_banner1" class="" style="margin:2px;float:left;cursor: pointer;width: 128px;height: 256px" src="http://placehold.it/512x1024&text=1x2">        </div>        <div style="float: left;width: 128px;margin:2px">            <img id="p_banner2" class="" style="margin:2px 0;float:left;cursor: pointer;width: 128px;height: 128px" src="http://placehold.it/512x512&text=1x1">            <br class="clear">            <img id="p_banner3" class="" style="float:left;cursor: pointer;width: 128px;height: 128px" src="http://placehold.it/512x512&text=1x1">        </div>    </div></form><?phpwp_enqueue_media();wp_enqueue_script( 'loadajax.js' , WOO2APP_JS_URL.'loadAjaxPosts.js', array('jquery'));?><script>    jQuery('.action').on('change', function() {        var type_val = (jQuery(this).val());        var action = (jQuery(this).attr('id'))        jQuery('.el_type_' + action).css('display','none');        if(type_val == 1){            jQuery('#'+ action +'_product').css('display','table-row');        }        else if(type_val == 2){            jQuery('#'+ action +'_cat').css('display','table-row');        }        else if(type_val == 3){            jQuery('#'+ action +'_link').css('display','table-row');        }        else if(type_val == 4){            jQuery('#'+ action +'_post').css('display','table-row');        }        else if(type_val == 5){            jQuery('#'+ action +'_post_cats').css('display','table-row');        }    });    var custom_uploader_hami;    jQuery('.banner').click(function (e) {        var b_id = jQuery(this).attr('id');        e.preventDefault();        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({            title: 'انتخاب تصویر',            library: {type: 'image'},            button: {text: 'انتخاب'},            multiple: false        });        custom_uploader_hami.on('select', function() {            attachment = custom_uploader_hami.state().get('selection').first().toJSON();            jQuery('#' + b_id + '_val').val(attachment.url);            url_image = attachment.url;            jQuery('#' + b_id ).attr("src",url_image);            jQuery('#p_' + b_id ).attr("src",url_image);        });        custom_uploader_hami.open();    })    jQuery('.search_product_banner').keyup(function(){        var id = jQuery(this).attr('data-id');        //alert(id);        var  v = jQuery(this).val();        if(v.length >= 3){            jQuery("#img_load"+id).attr("style",'display:block');            jQuery.ajax({                url: ajaxurl,                data: {                    action: "load_all_product",                    search_product : v                },                success:function(data) {                    jQuery("#img_load"+id).attr("style",'display:none');                    jQuery("#value_post_items_menu"+id).html(data);                }            });        }    });</script>