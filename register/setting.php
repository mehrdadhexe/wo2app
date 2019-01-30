<?php
/**
 * Created by mr2app.
 * User: hani
 * Date: 11/13/18
 * Time: 16:16
 */
if (!defined( 'ABSPATH' )) exit;
$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(isset($_REQUEST["submit_edit"])){
    $array = array(
        'replace_phone' => $_POST['replace_phone'],
    );
    if(update_option( 'mr2app_register_form',$array )){
        ?>
        <div class="notice notice-success is-dismissible">
            <p>  تنظیمات با موفقیت اعمال شد.</p>
        </div>
        <?php
    }
    else {
        ?>
        <div class="notice notice-danger is-dismissible">
            <p> متاسفانه ، به مشکل برخوردیم. مجدد امتحان کنید.</p>
        </div>
        <?php
    }
}
$setting = get_option('mr2app_register_form');
?>
<div class="wrap" >
    <h1>
        تنظیمات کلی ماژول ثبت نام پیشرفته
    </h1>
    <hr>
    <div id="col-container" class="">
        <div class="col-wrap" style="width: 50%;">
            <div class="form-wrap">
                <form id="addtag" method="post" action="" class="validate">

                    <div class="form-field ">
                        <label> انتخاب فیلد جایگزین شماره تماس </label>
                        <?php
                        $args      = array(
                            'post_type'   => 'woo2app_register',
                            'post_status' => 'draft',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        );
                        $the_query = get_posts( $args );
                        ?>
                        <select name="replace_phone" style="width: 90%">
                            <option value="0">
                                یکی از گزینه های زیر را انتخاب کنید
                            </option>
                            <?php
                            foreach ($the_query as $f){
                                ?>
                                <option <?= ($setting['replace_phone'] == $f->post_content)? 'selected' : '' ;?> value="<?= $f->post_content; ?>">
                                    <?= $f->post_title;?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-field " >
                        <p class="submit">
                            <input type="submit" name="submit_edit"  class="button button-primary" value="ویرایش"  />
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>