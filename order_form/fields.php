<?php/** * Created by mr2app. * User: hani * Date: 12/13/17 * Time: 6:10 PM */if (!defined( 'ABSPATH' )) exit;$current_url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];if(isset($_REQUEST['sort'])){    $ids = $_POST['ids'];    $i = 0;    //var_dump($ids);    $i = 0;    foreach ($ids as $id){        $i++;        $array = array(            'ID'            => $id,            'menu_order'    => $i,        );        wp_update_post($array);    }}if(isset($_GET['action'])){    require_once "new_field.php";}else {    if ( isset($_POST['submit'])) {        $array = array(            'post_title'    => $_POST['title'],            'post_type'     => 'woo2app_order_field',            'post_status'   => 'close',        );        if ( wp_insert_post( $array ) ) {            ?>            <div class="notice notice-success is-dismissible">                <p> زبان جدید اضافه شد.</p>            </div>            <?php        } else {            ?>            <div class="notice notice-danger is-dismissible">                <p> متاسفانه ، به مشکل برخوردیم. مجدد امتحان کنید.</p>            </div>            <?php        }    }    if ( isset( $_REQUEST["submit_edit"] ) ) {        $array = array(            'ID'            => $_POST['edit_ID'],            'post_title'    => $_POST['edit_title'],        );        if ( wp_update_post( $array ) ) {            ?>            <div class="notice notice-success is-dismissible">                <p> پرونده به خوبی ویرایش شد.</p>            </div>            <?php        } else {            ?>            <div class="notice notice-danger is-dismissible">                <p> متاسفانه ، به مشکل برخوردیم. مجدد امتحان کنید.</p>            </div>            <?php        }    }    if ( isset( $_REQUEST["submit_delete"] ) ) {        $post_id = $_POST['delete_id'];        if ( wp_delete_post( $post_id ) ) {            ?>            <div class="notice notice-success is-dismissible">                <p> پرونده به درستی حذف شد.</p>            </div>            <?php        } else {            ?>            <div class="notice notice-danger is-dismissible">                <p> متاسفانه ، به مشکل برخوردیم. مجدد امتحان کنید.</p>            </div>            <?php        }    }    ?>    <div class="wrap">        <p>            این بخش نیازمند فعال سازی            <a target="_blank" href="https://mr2app.com/blog/prof-order/">                ماژول ثبت سفارش پیشرفته            </a>            ، در اپلیکیشن می باشد.        </p>        <h1> فیلد های ثبت سفارش            <a class="add-new-h2" href="<?= $current_url.'&action=new'?>">افزودن </a>        </h1>        <p class="admin-msg">            شاید نیاز داشته باشید که فیلدهای ثبت سفارش را ویرایش و یا فیلدی اضافه کنید.        </p>        <div id="col-container" class="">            <div>                <div class="col-wrap ">                    <form name="form_sort" id="form_sort" method="post" action="">                        <table class="wp-list-table widefat fixed striped tags ui-sortable ">                            <thead>                            <tr>                                <th scope="col" id='name' class='manage-column column-name '>                                    <span> عنوان </span>                                </th>                                <th scope="col" id='name' class='manage-column column-name '>                                    <span> نام </span>                                </th>                                <th scope="col" style="width: 70px" id='name' class='manage-column column-name'>                                    <span style="cursor: pointer"> اجباری ؟  </span>                                </th>                                <th scope="col" style="width: 70px" id='name' class='manage-column column-name'>                                    <span style="cursor: pointer"> فعال بودن  </span>                                </th>                                <th scope="col" style="width: 70px" id='name' class='manage-column column-name'>                                    <span style="cursor: pointer"> نمایش  </span>                                </th>                                <th scope="col" id='name' class='manage-column column-name '>                                    <span style="cursor: pointer"> ویرایش / حذف </span>                                </th>                            </tr>                            </thead>                            <tbody id="sortable">                            <?php                            $args      = array(                                'post_type'   => 'woo2app_order',                                'post_status' => 'draft',                                'posts_per_page' => -1,                                'orderby' => 'menu_order',                                'order' => 'ASC'                            );                            $the_query = get_posts( $args );                            $default_fields  = array( 'first_name' , 'last_name' , 'address_1' , 'address_2','city' ,  'state' , 'postcode' , 'country' , 'email' , 'phone' , 'customer_note');                            //var_dump($default_fields);                            foreach ( $the_query as $value ) {                                ?>                                <tr id="tag-<?= $value->ID; ?>" style="cursor: move" class="<?= 'tag-' . $value->ID; ?>">                                    <input type="hidden" name="ids[]" value="<?= $value->ID; ?>">                                    <form action="" onsubmit="return confirm('Do you really want to submit the form?');"                                          method="post" id="form_<?= $value->ID ?>">                                        <input type="hidden" name="delete_id" value="<?= $value->ID; ?>">                                        <td class='name column-name has-row-actions column-primary' data-colname="نام">                                            <p><strong><a style="direction: rtl" class="row-title"                                                          href="<?= $current_url . '&lang_id=' . $value->ID; ?>"> <?= $value->post_title; ?>  </a></strong>                                            </p>                                        </td>                                        <td class='description column-description'>                                            <p dir="ltr" style="text-align: right"><?= $value->post_content; ?> </p>                                        </td>                                        <td class='description column-description'>                                            <p >                                                <?php                                                $r = get_post_meta($value->ID,'required' , true);                                                if($r){                                                    ?>                                                    <input checked onchange="update_post_meta(this,'<?= $value->ID?>' , 'required')" type="checkbox"   name="required">                                                    <?php                                                }                                                else{                                                    ?>                                                    <input  type="checkbox" onchange="update_post_meta(this,'<?= $value->ID?>' , 'required')"  name="required">                                                    <?php                                                }                                                ?>                                            </p>                                        </td>                                        <td class='description column-description'>                                            <p >                                                <?php                                                $r = get_post_meta($value->ID,'active' , true);                                                if($r){                                                    ?>                                                    <input checked type="checkbox" onchange="update_post_meta(this,'<?= $value->ID?>' , 'active')"   name="active">                                                    <?php                                                }                                                else{                                                    ?>                                                    <input  type="checkbox" onchange="update_post_meta(this,'<?= $value->ID?>' , 'active')" name="active">                                                    <?php                                                }                                                ?>                                            </p>                                        </td>                                        <td class='description column-description'>                                            <p >                                                <?php                                                $r = get_post_meta($value->ID,'display' , true);                                                if($r){                                                    ?>                                                    <input checked type="checkbox" onchange="update_post_meta(this,'<?= $value->ID?>' , 'display')"  name="display">                                                    <?php                                                }                                                else{                                                    ?>                                                    <input  type="checkbox" onchange="update_post_meta(this,'<?= $value->ID?>' , 'display')"  name="display">                                                    <?php                                                }                                                ?>                                            </p>                                        </td>                                        <td class='description column-description'>                                            <a href="<?= $current_url.'&action=edit&id='.$value->ID;?>" class="button button-primary"> ویرایش </a>                                            <?php                                            if(!in_array($value->post_content , $default_fields)){                                                ?>                                                <input type="submit" name="submit_delete" class="button button-danger"                                                       value="حذف">                                                <?php                                            }                                            else{                                                echo '<span style="color: red"> قابلیت حذف ندارد.</span>';                                            }                                            ?>                                            <img  width="25px" style="display: none;" id="img_change_menu_<?= $value->ID?>"  src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">                                        </td>                                    </form>                                </tr>                                <?php                            }                            ?>                            </tbody>                        </table>                        <p>                            <input type="submit" name="sort" class="button button-default" value="مرتب سازی">                            برای مرتب سازی روی ردیف ها ، drag & drop کنید .                        </p>                    </form>                </div>            </div>        </div>    </div>    <?php}wp_enqueue_script( 'jquery-ui.js' , WOO2APP_JS_URL.'jquery-ui.js', array('jquery'));?><script>    jQuery(function() {        jQuery( "#sortable" ).sortable();        jQuery( "#sortable" ).disableSelection();    });    function update_post_meta(e ,post_id , meta_key) {        jQuery("#img_change_menu_" + post_id).attr("style", "display:inline-block");        jQuery("#tag-" + post_id + " input").attr('disabled' , 'disabled')        var active = 0;        if(e.checked == true) active = 1;        jQuery.ajax({            url: ajaxurl,            data: {                action: "mr2app_update_post_meta",                post_id : post_id,                meta_key : meta_key,                active : active            },            success:function(data) {                jQuery("#img_change_menu_" + post_id).attr("style", "display:none");                jQuery("#tag-" + post_id + " input").removeAttr('disabled')                //alert(data);                data = data.substring(0,data.length -1);                if(data == 1){                    alert('تغییر انجام شد.')                }                else{                    alert('تغییر با مشکل مواجه شد ، لطفا مجدد انجام دهید.')                }            }        });    }</script>