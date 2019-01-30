<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/11/2018
 * Time: 6:54 PM
 */

//discount_amount - role - min_basket - max_basket - type -  categories - products
$current_url = admin_url().'admin.php?page=woo2app/discount/index.php&tab=discount';

$discount_amount = "";
$role = "";
$min_basket = "";
$max_basket = "";
$type = "";
$categories = "";
$products = "";

if(isset($_POST['create'])) {
    global $wpdb;
    $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value like '". $_POST['role'] ."'", ARRAY_A );
    if(!$results) {
        $array = array(
            'post_title' => 'تخفیف - اپلیکیشن - پیش نویس',
            'post_content' => '',
            'post_type' => 'woo2app_discount',
            'post_status' => 'draft',
        );
        if ($post = wp_insert_post($array)) {
            add_post_meta($post, 'role', $_POST['role']);
            add_post_meta($post, 'discount_amount', $_POST['discount_amount']);
            add_post_meta($post, 'min_basket', $_POST['min_basket']);
            add_post_meta($post, 'max_basket', $_POST['max_basket']);
            add_post_meta($post, 'type', $_POST['type']);
            add_post_meta($post, 'categories', $_POST['categories']);
            add_post_meta($post, 'products', $_POST['products']);
        }
    }
    else{
        ?>
        <div class="notice notice-danger is-dismissible">
            <p>  نقش تکراری می باشد ، لطفا از نقش دیگری انتخاب کنید .</p>
        </div>
        <?php
    }

}
elseif(isset($_REQUEST['submit_edit'])){
    $post_id = $_POST['delete_id'];

    update_post_meta($post_id, 'role', $_POST['role']);
    update_post_meta($post_id, 'discount_amount', $_POST['discount_amount']);
    update_post_meta($post_id, 'min_basket', $_POST['min_basket']);
    update_post_meta($post_id, 'max_basket', $_POST['max_basket']);
    update_post_meta($post_id, 'type', $_POST['type']);
    update_post_meta($post_id, 'categories', $_POST['categories']);
    update_post_meta($post_id, 'products', $_POST['products']);
    //exit();
}

if(isset($_GET['action']) && isset($_GET['field'])) {
    if ($_GET['action'] == 'delete' && $_GET['field'] != '') {
        $post_id = $_GET['field'];
        if (wp_delete_post($post_id)) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p> پرونده به درستی حذف شد.</p>
            </div>
            <?php
        } else {
            ?>
            <div class="notice notice-danger is-dismissible">
                <p> متاسفانه ، به مشکل برخوردیم. مجدد امتحان کنید.</p>
            </div>
            <?php
        }
    }
}

$args      = array(
    'post_type'   => 'woo2app_discount',
    'post_status' => 'draft',
    'posts_per_page' => -1,
);
$register_role = array();
$the_query = get_posts( $args );
foreach ( $the_query as $f ) {
    $register_role[] = get_post_meta($f->ID , 'role' , true);
}

$editable = 0;
if(isset($_GET['action']) && isset($_GET['field'])){
    if($_GET['action'] == 'edit' && $_GET['field'] != ''){
        $editable = 1;
        $post_id = $_GET['field'];
        $discount_amount = get_post_meta($post_id , 'discount_amount' , true);
        $edit_role = get_post_meta($post_id , 'role' , true);
        $min_basket = get_post_meta($post_id , 'min_basket' , true);
        $max_basket = get_post_meta($post_id , 'max_basket' , true);
        $type = get_post_meta($post_id , 'type' , true);
        $categories = get_post_meta($post_id , 'categories' , true);
        $products = get_post_meta($post_id , 'products' , true);
    }
}

?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <form action="" method="post">
        <table class="form-table " style="direction: rtl">
            <tbody>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > انتخاب نقش (رول) </label>
                </th>
                <td class="forminp forminp-text">
                    <select  class="form-control" name="role" required="required">
                        <?php
                        $roles = wp_roles();
                        foreach ($roles->roles as $r => $rr){
                            //if($r['value'] == )
                            //$role = get_role( $r['value'] );
                            //va
                            //var_dump($role->capabilities);
                            $i = 0;
                            foreach ($rr['capabilities'] as $cap => $val){
                                if($cap == 'mr2app_discount'){
                                    $i ++;
                                    ?>
                                    <option <?= selected($edit_role, $r)?> value="<?= $r; ?>"> <?= $rr['name'];?></option>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label >  حداقل سفارش </label>
                </th>
                <td class="forminp">
                    <input type="text" value="<?= $min_basket;?>"  name="min_basket" id="min_basket"  placeholder=" حداقل سفارش " >
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label >  حداکثر سفارش </label>
                </th>
                <td class="forminp">
                    <input type="text" value="<?= $max_basket;?>"  name="max_basket" id="max_basket"  placeholder=" حداکثر سفارش " >
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label >  نوع تخفیف </label>
                </th>
                <td class="forminp">
                    <strong> درصد : </strong> <input type="radio" <?= ($type == 'percent')? 'checked': ''?> name="type" checked value="percent" >
                    <strong> ثابت : </strong> <input type="radio" <?= ($type == 'constant')? 'checked': ''?> name="type"  value="constant">
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label>  مقدار تخفیف </label>
                </th>
                <td class="forminp">
                    <input type="text" required value="<?= $discount_amount;?>"  name="discount_amount" id="discount_amount"  placeholder=" مقدار تخفیف  " >
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label >  انتخاب دسته های مجاز </label>
                </th>
                <td class="forminp">
                    <select  name="categories[]"  style="width: 100%"  multiple class="col-md-12 js-example-basic-single form-control"  title="انتخاب کنید...">
                        <option value="0" style="text-align:right;">دسته خود را انتخاب کنید</option>
                        <?php
                        $i=0;
                        $cats = get_terms(array('product_cat'));
                        foreach ($cats as $cat) {
                            $i++;
                            ?>
                            <option <?= (in_array($cat->term_id,$categories)) ? 'selected' :''?> style="text-align:right;"  value="<?= $cat->term_id; ?>"><?= $cat->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label >  انتخاب محصولات مجاز </label>
                </th>
                <td class="forminp">
                    <select name="products[]" data-id="product"  multiple style="width: 100%;" class="col-md-10 js-data-example-ajax form-control">
                        <?php
                        $_pf = new WC_Product_Factory();
                        foreach ($products as $p){
                            $_product = $_pf->get_product($p);
                            if($_product){
                                ?>
                                <option selected value="<?= $_product->get_id()?>"><?= $_product->get_title()?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>


            <tr >
                <th scope="row" class="titledesc">

                </th>
                <td class="forminp">
                    <?php
                    if($editable == 1){
                        ?>
                        <input type="submit" class="button button-primary"  value="ویرایش"  name="submit_edit" />
                        <a href="<?= $current_url;?>" class="button" >  جدید </a>
                        <?php
                    }
                    else{
                        ?>
                        <input type="submit"  class="button" value="ذخیره"  name="create" />
                        <?php
                    }
                    ?>
                    <input type="hidden" name="delete_id" value="<?= $post_id?>" />
                </td>
            </tbody>
        </table>
    </form>


    <hr>

    <table class="wp-list-table widefat striped" style="direction: rtl">
        <tbody>
        <tr valign="top" class="">
            <th scope="row" class="">
                <label > #  </label>
            </th>
            <th scope="row" class="">
                <label >  رول  </label>
            </th>
            <th scope="row" class="">
                <label > حداقل سفارش  </label>
            </th>
            <th scope="row" class="">
                <label > حداکثر سفارش  </label>
            </th>
            <th scope="row" class="">
                <label > نوع  </label>
            </th>
            <th scope="row" class="">
                <label > مقدار تخفیف  </label>
            </th>
            <th scope="row" class="">
                <label > ویرایش / حذف  </label>
            </th>
        </tr>
        <?php
        $args   = array(
            'post_type'   => 'woo2app_discount',
            'post_status' => 'draft',
            'posts_per_page' => -1,
        );
        $the_query = get_posts( $args );
        $i = 0;
        foreach ($the_query as $f){
            $i++;
            ?>
            <tr valign="top" class="">
                <th scope="row" class="">
                    <label > <?= $i; ?>  </label>
                </th>
                <th scope="row" class="">
                    <label > <?= get_post_meta($f->ID,'role' , true);?>  </label>
                </th>
                <th scope="row" class="">
                    <label > <?= get_post_meta($f->ID,'min_basket' , true);?>  </label>
                </th>
                <th scope="row" class="">
                    <label > <?= get_post_meta($f->ID,'max_basket' , true);?>  </label>
                </th>
                <th scope="row" class="">
                    <label > <?= get_post_meta($f->ID,'type' , true);?>  </label>
                </th>
                <th scope="row" class="">
                    <label > <?= get_post_meta($f->ID,'discount_amount' , true);?>  </label>
                </th>
                <th scope="row" class="">
                    <form action="" onsubmit="return confirm('Do you really want to submit the form?');" method="post" id="form_<?= $f->ID ?>">
                        <input type="hidden" name="delete_id" value="<?= $f->ID; ?>" />
                        <a class="button button-primary" href="<?= $current_url . '&action=edit&field='.$f->ID; ?>"  > ویرایش </a>
                        <a class="button " href="<?= $current_url . '&action=delete&field='.$f->ID; ?>"  > حذف </a>
                        <!--                        <input type="submit" name="submit_delete" class="button button-danger" value="حذف" />-->
                    </form>
                </th>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

<?php
wp_enqueue_script( 'loadajax.js' , WOO2APP_JS_URL.'loadAjaxPosts.js', array('jquery'));
?>