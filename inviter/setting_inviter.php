<?php
if (!defined( 'ABSPATH' )) exit;
$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


if(isset($_POST['submit_update'])){
    $update = array();
    $update = get_option('woo2app_inviter_setting');
    if(!is_array($update)){
        $update = array();
        $update['enable_after_register'] = false ;
        $update['inviter_title'] = '' ;
        $update['inviter_description'] = '' ;
        $update['display_score_in_menu'] = false ;
        $update['score_value_for_inviter'] = '' ;
        $update['score_type'] = '0' ;
        $update['score_user_title'] = '' ;
        $update['display_marketer_code'] = false ;
        $update['marketer_title'] = '' ;
        $update['type_make_code'] = 'username' ;
        update_option('woo2app_inviter_setting' , $update);
    }

    $update['enable_after_register'] = (isset($_POST['enable_after_register']) && $_POST['enable_after_register'] == 'on' ) ? true : false;
    $update['inviter_title'] = $_POST['inviter_title'] ;
    $update['inviter_description'] = $_POST['inviter_description'] ;
    $update['display_score_in_menu'] = (isset($_POST['display_score_in_menu']) && $_POST['display_score_in_menu'] == 'on') ? true : false;
    $update['score_value_for_inviter'] = $_POST['score_value_for_inviter'] ;
    $update['score_type'] = $_POST['score_type'] ;
    $update['score_user_title'] = $_POST['score_user_title'] ;
    $update['display_marketer_code'] = (isset($_POST['display_marketer_code']) && $_POST['display_marketer_code'] == 'on' ) ? true : false;
    $update['marketer_title'] = $_POST['marketer_title'] ;
    $update['type_make_code'] = $_POST['type_make_code'] ;
    update_option('woo2app_inviter_setting' , $update);

}
?>
<div class="wrap">

    <?php
    $update = array();
    $update = get_option('woo2app_inviter_setting');
    if(!is_array($update)){
        $update = array();
        $update['enable_after_register'] = false ;
        $update['inviter_title'] = '' ;
        $update['inviter_description'] = '' ;
        $update['display_score_in_menu'] = false ;
        $update['score_value_for_inviter'] = '' ;
        $update['score_type'] = '0' ;
        $update['score_user_title'] = '' ;
        $update['display_marketer_code'] = false ;
        $update['marketer_title'] = '' ;
        $update['type_make_code'] = 'username' ;
        update_option('woo2app_inviter_setting' , $update);
    }
    ?>
    <h1>
        تنظیم کدمعرف
    </h1>
    <p>
        این بخش نیازمند فعال سازی
        <a target="_blank" href="http://mr2app.com/blog/woocommerce-marketing/">
            ماژول همکاری در فروش
        </a>
        ، در اپلیکیشن می باشد.
    </p>
    <form method="post">
        <table class="form-table " style="direction: rtl">
            <tbody>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > فعال سازی دریافت کد معرف </label>
                </th>
                <td class="forminp">
                    <input type="checkbox" name="enable_after_register" <?= checked( $update['enable_after_register']  );?> />
                    در صورت فعال بودن ، پس از ثبت نام کاربر ، کد معرف از کاربر دریافت می شود.
                </td>
            </tr>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > عنوان فیلد کد معرف </label>
                </th>
                <td class="forminp">
                    <input type="text" class="regular-text" name="inviter_title" value="<?= $update['inviter_title']?>">
                </td>
            </tr>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label >  توضیحات کد معرف  </label>
                </th>
                <td class="forminp">
                    <textarea  type="text" class="regular-text" name="inviter_description" ><?= $update['inviter_description']?></textarea>
                </td>
            </tr>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > نحوه امتیازدهی به معرف </label>
                </th>
                <td class="forminp">
                    <select name="score_type" class="regular-text">
                        <option value="0" <?= selected( $update['score_type'],'0'  );?> > هیچ </option>
                        <option value="1" <?= selected( $update['score_type'],'1'  );?> > امتیاز </option>
                        <option value="2" <?= selected( $update['score_type'],'2'  );?> > کیف پول </option>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > مقدار امتیاز </label>
                </th>
                <td class="forminp">
                    <input type="number" class="regular-text" name="score_value_for_inviter" value="<?= $update['score_value_for_inviter']?>">
                    <p>
                        مقدار امتیاز و یا مبلغ هدیه برای معرف در ازای معرفی هر کاربر.
                    </p>
                </td>
            </tr>
            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > نمایش امتیاز کاربر </label>
                </th>
                <td class="forminp">
                    <input type="checkbox" name="display_score_in_menu" <?= checked( $update['display_score_in_menu']  );?> />
                    در صورت فعال بودن ،  امتیاز کاربر در منو اپلیکیشن نمایش داده میشود.
                </td>
            </tr>

            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > عنوان امتیاز کاربر </label>
                </th>
                <td class="forminp">
                    <input type="text" class="regular-text" name="score_user_title" value="<?= $update['score_user_title']?>">
                </td>
            </tr>

            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > نمایش کد بازاریابی  </label>
                </th>
                <td class="forminp">
                    <input type="checkbox" name="display_marketer_code" <?= checked( $update['display_marketer_code']  );?> />
                    در صورت فعال بودن ،کد بازاریابی کاربر در منو نمایش داده میشود
                </td>
            </tr>

            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label> عنوان کد بازاریابی </label>
                </th>
                <td class="forminp">
                    <input type="text" class="regular-text" name="marketer_title" value="<?= $update['marketer_title']?>">
                </td>
            </tr>

            <tr valign="top" class="" >
                <th scope="row" class="titledesc">
                    <label > نحوه ساخت کد معرف </label>
                </th>
                <td class="forminp">
                    <select name="type_make_code" class="regular-text">
                        <option value="username" <?= selected( $update['type_make_code'],'username'  );?> > نام کاربری </option>
                        <option value="random_digit" <?= selected( $update['type_make_code'],'random_digit'  );?> > عدد رندوم 7 رقمی </option>
                        <option value="random_alphabet" <?= selected( $update['type_make_code'],'random_alphabet'  );?> > حروف رندوم 7 حرفی </option>
                    </select>
                </td>
            </tr>


            </tbody>
        </table>
        <input name="submit_update"  class="button button-primary" value="ذخیره‌" type="submit">
    </form>
</div>
