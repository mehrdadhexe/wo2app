<?php

/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/11/2018
 * Time: 6:59 PM
 */

$current_url = admin_url().'admin.php?page=woo2app/discount/index.php&tab=roles';
//remove_role('mr2app_discount');
if(isset($_GET["action"])){
    if($_GET['action'] == 'delete'){
        remove_role($_GET["field"]);
        ?>
        <p>
            #زمینه دلخواه پاک شد.
        </p>
        <a class="button button-primary" href="<?= $current_url;?>">  بازگشت </a>
        <?php
        exit();
    }
}
else {
    if(isset($_POST["value"]) && $_POST["value"] != "" && isset($_POST["label"]) && $_POST["label"] != ""){
    $result = add_role( $_POST["value"], __(
        $_POST["label"] ),
        array(
            'read' => true, // true allows this capability
            'mr2app_discount'=>true,
        )
    );
    if($result){
        echo '<p style="color: green;"> نقش به درستی ثبت شد .</p>';
    }
    else{
        echo '<p style="color: red;">  خطایی رخ داده است ، لطفا مقدار صحیح وارد کنید ...</p>';
    }
}
}

$roles = wp_roles();

//var_dump($roles);
//return;
?>

<form action="" method="post">
    <table class="form-table " style="direction: rtl">
        <tbody>
        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label > اسلاگ - نام یکتا </label>
            </th>
            <td class="forminp">
                <input type="text"   name="value" id="value"  placeholder=" اسلاگ - نام یکتا" >
            </td>
        </tr>

        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label > برچسب نقش </label>
            </th>
            <td class="forminp">
                <input type="text"   name="label" id="label"  placeholder="  برچسب نقش " >
            </td>
        </tr>

        <tr >
            <th scope="row" class="titledesc">

            </th>
            <td class="forminp">
                <input type="submit"  value="ذخیره"  name="submit_banner">
            </td>
        </tr>
        </tbody>
    </table>
</form>

<table class="wp-list-table widefat striped" style="direction: rtl">
    <tbody>
    <tr valign="top" class="">
        <th scope="row" class="">
            <label > #  </label>
        </th>
        <th scope="row" class="">
            <label > برچسب نقش (رول)  </label>
        </th>
        <th scope="row" class="">
            <label >  حذف  </label>
        </th>
    </tr>
    <?php
    foreach ($roles->roles as $r => $rr){
        //if($r['value'] == )
        //var_dump($rr);continue;
       // $role = get_role( $r['value'] );
        //va
        $i = 0;
        foreach ($rr['capabilities'] as $cap => $val){
            if($cap == 'mr2app_discount'){
                $i ++;
                //var_dump($r);
                ?>
                <tr class="">
                    <td scope="row" class="">
                        <label > <?= $i; ?>  </label>
                    </td>
                    <td scope="row" class="">
                        <label > <?= $rr['name'];?>  </label>
                    </td>
                    <td scope="row" class="">
                        <a href="<?= $current_url . '&action=delete&field='.$r?>" style="color: red;"  >  حذف </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>

        <?php
    }
    ?>
    </tbody>
</table>
