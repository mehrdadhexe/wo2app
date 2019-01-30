<?php

if (!defined( 'ABSPATH' )) exit;





$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(isset($_GET["tab"]))	$tab = $_GET["tab"];

else $tab = 'main';

if(isset($_POST["submit_primary_setting_form"]) && $_POST["submit_primary_setting_form"] == "hami" ){


    //$font = $_POST["select_font_primary_setting"];

    $unit = $_POST["unit_primary_setting"];

    //$ver = $_POST["version_primary_setting"];

    //$lnk = $_POST["link_primary_setting"];

    $buy_with_login = (isset($_POST["BUY_WITH_LOGIN"]) && $_POST["BUY_WITH_LOGIN"] == 'on') ? true : false;
    $enter_with_login =  (isset($_POST["ENTER_WITH_LOGIN"]) && $_POST["ENTER_WITH_LOGIN"] == 'on') ? true : false;
    $display_btn_share =  (isset($_POST["display_btn_share"]) && $_POST["display_btn_share"] == 'on') ? true : false;

    $woo2app_googleloginkey = $_POST["woo2app_googleloginkey"] ;

    $minimum_purchase_amount = (int)$_POST["minimum_purchase_amount"];



    if(isset($unit)) update_option("DEFAULT_UNIT_APP" , $unit);

    //if(isset($unit)) update_option("DEFAULT_VER_APP" , $ver);

    //if(isset($unit)) update_option("DEFAULT_LNK_APP" , $lnk);

    if(isset($unit)) update_option("minimum_purchase_amount" , $minimum_purchase_amount);

    update_option('BUY_WITH_LOGIN',$buy_with_login);

    update_option('ENTER_WITH_LOGIN',$enter_with_login);

    update_option('display_btn_share',$display_btn_share);

    update_option('woo2app_googleloginkey',$woo2app_googleloginkey);

    update_option('calltoprice_tell',$_POST["calltoprice_tell"]);

    update_option('calltoprice_price',$_POST["calltoprice_price"]);

    update_option('default_product_images',$_POST["default_product_images"]);
    update_option('refco_primary_setting' , $_POST['refco_primary_setting'] );

}

if(isset($_POST['submit_update'])){

    $update = array();

    $update = get_option('woo2app_update');
     $updatev = get_option('refco_primary_setting');

     if(!is_array($updatev)){

     update_option('refco_primary_setting' ,1);



      }

    if(!is_array($update)){

        $update = array();

        $update['android_ver_code'] = '' ;

        $update['android_update_url'] = '' ;

        $update['android_update_req'] = false ;

        $update['ios_ver_code'] = '' ;

        $update['ios_update_url'] = '' ;

        $update['description_update'] = '' ;

        $update['description_update_ios'] = '' ;

        $update['ios_update_req'] = false ;

        update_option('woo2app_update' , $update);


    }



    $update['android_ver_code'] = $_POST['android_ver_code'] ;

    $update['android_update_url'] = $_POST['android_update_url'] ;

    $update['android_update_req'] = (isset($_POST['android_update_req']) && $_POST['android_update_req'] == 'on' ) ? true : false;

    $update['ios_ver_code'] = $_POST['ios_ver_code'] ;

    $update['ios_update_url'] = $_POST['ios_update_url'] ;

    $update['description_update'] = $_POST['description_update'] ;
    $update['description_update_ios'] = $_POST['description_update_ios'] ;

    $update['ios_update_req'] = (isset($_POST['ios_update_req']) &&  $_POST['ios_update_req'] == 'on') ? true : false;

    update_option('woo2app_update' , $update);
     update_option('refco_primary_setting' , $_POST['refco_primary_setting'] );
}





?>

<div class="wrap">

    <h2 class="nav-tab-wrapper koodak">

        <a href="<?= $current_url.'&tab=main' ?>" class="nav-tab <?php echo ('main' == $tab) ? 'nav-tab-active' : ''; ?>"> تنظیمات اصلی </a>

        <a href="<?= $current_url.'&tab=update' ?>" class="nav-tab <?php echo ('update' == $tab) ? 'nav-tab-active' : ''; ?>"> آپدیت </a>

    </h2>

    <?php

    if($tab == 'main'){

        ?>

        <h1>

            تنظیمات اصلی اپلیکیشن

        </h1>

        <form method="post">

            <input value="hami" name="submit_primary_setting_form" type="hidden">

            <table class="form-table " style="direction: rtl">

                <tbody>



                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">
                        <label > واحد پول </label>
                    </th>

                    <td class="forminp">
                        <input type="text" class="regular-text" name="unit_primary_setting" value="<?php echo get_option("DEFAULT_UNIT_APP"); ?>">
                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">
                        <label > در صد کمیسیون از کل فاکتور</label>
                    </th>

                    <td class="forminp">
                        <input type="text" class="regular-text" name="refco_primary_setting" value="<?php echo get_option("refco_primary_setting"); ?>">
                    </td>

                 </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > حداقل مبلغ سبد خرید</label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="minimum_purchase_amount" value="<?php echo get_option("minimum_purchase_amount"); ?>">

                        <p >  درصورت 0 ، بینهایت محسوب خواهد شد. </p>

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        ثبت نام برای ورود به اپ

                    </th>

                    <td class="forminp">

                        <input type="checkbox" name="ENTER_WITH_LOGIN" <?= checked(get_option("ENTER_WITH_LOGIN")  );?> />

                        در صورت فعال بودن ، ورود به اپ فقط با عضویت امکان پذیر هست

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        ثبت نام برای ثبت سفارش

                    </th>

                    <td class="forminp">

                        <input type="checkbox" name="BUY_WITH_LOGIN" <?= checked(get_option("BUY_WITH_LOGIN")  );?> />

                        در صورت فعال بودن ، خرید فقط با عضویت امکان پذیر هست.

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">
                        نمایش دکمه اشتراک گذاری
                    </th>

                    <td class="forminp">

                        <input type="checkbox" name="display_btn_share" <?= checked(get_option("display_btn_share")  );?> />

                        در صورت فعال بودن ، دکمه اشتراک گذاری در اپ نمایش خواهد شد.

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        کلید ورود گوگل

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="woo2app_googleloginkey" value="<?php echo get_option("woo2app_googleloginkey"); ?>">

                        <p>

                            برای اطلاعات بیشتر

                            <a target="_blank" href="http://mr2app.com/blog/login-with-google/">

                                اینجا

                            </a>

                            کلیک کنید.

                        </p>

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label >   شماره تلفن جهت تماس </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="calltoprice_tell" value="<?php echo get_option("calltoprice_tell"); ?>">

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > قیمت محصول جهت تماس بگیرید </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="calltoprice_price" value="<?php echo get_option("calltoprice_price"); ?>">

                        <p >  اگر قیمت محصول با قیمت وارد شده برابر باشد به جای دکمه اضافه به سبد خرید  دکمه تماس بگیرید نمایش داده میشود. </p>

                    </td>

                </tr>

                <tr>
                    <th >
                        <label dir="rtl"> تصویر پیش فرض محصولات </label>
                    </th>
                    <td class="forminp">
                        <input type="text" class="regular-text" id="default_product_images" value="<?= get_option('default_product_images');?>" name="default_product_images">
                        <button class="button" id="div_image_item_hami"> انتخاب عکس </button>
                        <p>
                            تنظیم تصویر پیش فرض محصولات برای حالتی که محصول تصویر ندارد.
                        </p>
                    </td>
                </tr>

                </tbody>

            </table>

            <input name="submit" id="submit" class="button button-primary" value="ذخیره‌ی تغییرات" type="submit">

        </form>

        <?php

    }

    else{

        $update = array();

        $update = get_option('woo2app_update');

        if(!is_array($update)){

            $update = array();

            $update['android_ver_code'] = '' ;

            $update['android_update_url'] = '' ;

            $update['android_update_req'] = false ;

            $update['ios_ver_code'] = '' ;

            $update['ios_update_url'] = '' ;

            $update['ios_update_req'] = false ;

            update_option('woo2app_update' , $update);

        }

        ?>

        <h1>

            تنظیم آپدیت

        </h1>

        <form method="post">

            <table class="form-table " style="direction: rtl">

                <tbody>



                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > کد نسخه اندروید </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="android_ver_code" value="<?= $update['android_ver_code']?>">

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > لینک آپدیت اندروید </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="android_update_url" value="<?= $update['android_update_url']?>">

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > آپدیت اجباری اندروید</label>

                    </th>

                    <td class="forminp">

                        <input type="checkbox" name="android_update_req" <?= checked( $update['android_update_req']  );?> />

                        در صورت فعال بودن ،آپدیت اپلیکیشن برای کاربر اجباری می شود.

                    </td>

                </tr>

                <hr>



                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > کد نسخه  آی او اس </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="ios_ver_code" value="<?= $update['ios_ver_code']?>">

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > لینک آپدیت آی او اس </label>

                    </th>

                    <td class="forminp">

                        <input type="text" class="regular-text" name="ios_update_url" value="<?= $update['ios_update_url']?>">

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label > آپدیت اجباری آی او اس </label>

                    </th>

                    <td class="forminp">

                        <input type="checkbox" name="ios_update_req" <?= checked( $update['ios_update_req']  );?> />

                        در صورت فعال بودن ،آپدیت اپلیکیشن برای کاربر اجباری می شود.

                    </td>

                </tr>

                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label >  توضیحات آپدیت اندروید </label>

                    </th>

                    <td class="forminp">

                        <?php
                        wp_editor( $update['description_update'],'description_update');
                        ?>

                    </td>

                </tr>


                <tr valign="top" class="" >

                    <th scope="row" class="titledesc">

                        <label >  توضیحات آپدیت ios </label>

                    </th>

                    <td class="forminp">

                        <?php
                        wp_editor( $update['description_update_ios'],'description_update_ios');
                        ?>

                    </td>

                </tr>




                </tbody>

            </table>

            <input name="submit_update"  class="button button-primary" value="ذخیره‌" type="submit">

        </form>

        <?php

    }

    ?>

</div>

<?php

wp_enqueue_media();
?>
<script>
    jQuery('#div_image_item_hami').click(function (e) {

        e.preventDefault();

        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({

            title: 'انتخاب تصویر',

            library: {type: 'image'},

            button: {text: 'انتخاب'},

            multiple: false

        });

        custom_uploader_hami.on('select', function() {

            attachment = custom_uploader_hami.state().get('selection').first().toJSON();

            jQuery('#default_product_images').val(attachment.url);

            url_image = attachment.url;





            jQuery('#div_image_item_hami').attr("src",url_image);

        });

        custom_uploader_hami.open();

    })
</script>