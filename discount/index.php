<?php

/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/11/2018
 * Time: 6:44 PM
 */

$current_url = admin_url().'admin.php?page=woo2app/discount/index.php';

if(isset($_GET["tab"]))	$tab = $_GET["tab"];

else $tab = 'rules';

?>

<div class="wrap">

    <h2 class="nav-tab-wrapper koodak">
        <a href="<?= $current_url.'&tab=roles' ?>" class="nav-tab <?php echo ('roles' == $tab) ? 'nav-tab-active' : ''; ?>"> رول ها </a>
        <a href="<?= $current_url.'&tab=discount' ?>" class="nav-tab <?php echo ('discount' == $tab) ? 'nav-tab-active' : ''; ?>"> تخفیف ها </a>
    </h2>

    <p>
        این بخش نیازمند فعال سازی
        <a target="_blank" href="http://mr2app.com/blog/discount-module">
            ماژول تخفیف
        </a>
        ، در اپلیکیشن می باشد.
    </p>
    <?php
    if($tab == 'discount'){
        require_once  "discount.php";
    }
    else{
        require_once 'roles.php';
    }
    ?>

</div>

