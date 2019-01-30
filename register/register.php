<?php
$current_url = admin_url().'admin.php?page=woo2app/register/register.php';
$tab = "fields";
if (array_key_exists('tab', $_GET)) {
	$tab = $_GET['tab'];
}
?>
<div class="wrap">
    <h2 class="nav-tab-wrapper koodak">
        <a href="<?= $current_url.'&tab=fields' ?>" class="nav-tab <?php echo ('fields' == $tab) ? 'nav-tab-active' : ''; ?>"> فیلدهای ثبت نام </a>
        <a href="<?= $current_url.'&tab=sms' ?>" class="nav-tab <?php echo ('sms' == $tab) ? 'nav-tab-active' : ''; ?>"> تنظیمات پیامک </a>
    </h2>
    <p>
        این بخش نیازمند فعال سازی
        <a target="_blank" href="http://mr2app.com/blog/prof-register">
            ماژول ثبت نام
        </a>
        ، در اپلیکیشن می باشد.
    </p>
	<?php
	if($tab == 'fields'){
		require_once  "fields.php";
	}
    elseif ($tab == 'sms'){
		require_once 'sms.php';
	}
	?>
</div>