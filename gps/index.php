<?php

/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/29/2018
 * Time: 7:44 PM
 */

$current_url = admin_url().'admin.php?page=woo2app/gps/index.php';

if(isset($_GET["tab"]))	$tab = $_GET["tab"];

else $tab = 'period';

$c_user = wp_get_current_user();
if($c_user->roles[0] != 'administrator'){
    $tab = 'report';
}

?>

<div class="wrap">

    <h2 class="nav-tab-wrapper koodak">
        <?php
        if($c_user->roles[0] == 'administrator'){
            ?>
            <a href="<?= $current_url.'&tab=period' ?>" class="nav-tab <?php echo ('period' == $tab) ? 'nav-tab-active' : ''; ?>"> بازه زمانی </a>
            <a href="<?= $current_url.'&tab=place' ?>" class="nav-tab <?php echo ('place' == $tab) ? 'nav-tab-active' : ''; ?>"> موقعیت </a>
            <?php
        }
        ?>
        <a href="<?= $current_url.'&tab=report' ?>" class="nav-tab <?php echo ('report' == $tab) ? 'nav-tab-active' : ''; ?>"> گزارشات </a>
    </h2>

    <p>
        این بخش نیازمند فعال سازی
        <a target="_blank" href="http://mr2app.com/blog/gps-mudule">
            ماژول gps
        </a>
        ، در اپلیکیشن می باشد.
    </p>
    <?php
    if($tab == 'period'){
        require_once  "period.php";
    }
    elseif($tab == 'place'){
        require_once  "place.php";
    }
    elseif($tab == 'report'){
        require_once  "report.php";
    }
    ?>

</div>

