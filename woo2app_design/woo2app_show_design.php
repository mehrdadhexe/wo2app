<?php
$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(isset($_GET["tab"]))	$tab = $_GET["tab"];
else $tab = 'public';
if(isset($_GET["sub_page"])) $page = $_GET["sub_page"];
else $page = 'banner';

?>
<div class="wrap">
    <h2 class="nav-tab-wrapper koodak">
        <a href="<?= $current_url.'&tab=public' ?>" class="nav-tab <?php echo ('public' == $tab) ? 'nav-tab-active' : ''; ?>"> عمومی </a>
        <a href="<?= $current_url.'&tab=chideman' ?>" class="nav-tab <?php echo ('chideman' == $tab) ? 'nav-tab-active' : ''; ?>">چینمان صفحه اصلی</a>
        <a href="<?= $current_url.'&tab=2' ?>" class="nav-tab <?php echo (2 == $tab) ? 'nav-tab-active' : ''; ?>">تنظیمات اسپلش</a>
        <a href="<?= $current_url.'&tab=3' ?>" class="nav-tab <?php echo (3 == $tab) ? 'nav-tab-active' : ''; ?>">تنظیمات رنگ</a>
        <a href="<?= $current_url.'&tab=4' ?>" class="nav-tab <?php echo (4 == $tab) ? 'nav-tab-active' : ''; ?>">تنظیمات منو</a>
        <a href="<?= $current_url.'&tab=5' ?>" class="nav-tab <?php echo (5 == $tab) ? 'nav-tab-active' : ''; ?>">تنظیمات اسلایدر</a>
    </h2>

<?php
if($tab == 'chideman'){
	?>
    <ul class="subsubsub" style="margin-top: 10px">
        <li><a href="<?= $current_url.'&sub_page=banner' ?>" class="<?= ($page =="banner")?'current':'';?>"> بنر تکی </a> | </li>
        <li><a href="<?= $current_url.'&sub_page=banner2' ?>" class="<?= ($page =="banner2")?'current':'';?>" > بنر 2 تایی</a> | </li>
        <li><a href="<?= $current_url.'&sub_page=banner3' ?>" class="<?= ($page =="banner3")?'current':'';?>" >بنر 3 تایی Right</a> | </li>
        <li><a href="<?= $current_url.'&sub_page=banner3_L' ?>" class="<?= ($page =="banner3_L")?'current':'';?>" >بنر 3 تایی Left </a> | </li>
        <li><a href="<?= $current_url.'&sub_page=banner4' ?>" class="<?= ($page =="banner4")?'current':'';?>" >بنر 4 تایی</a> | </li>
        <li><a href="<?= $current_url.'&sub_page=product_list' ?>" class="<?= ($page =="product_list")?'current':'';?>" >  محصولات متفرقه </a> | </li>
        <li><a href="<?= $current_url.'&sub_page=vertical_list' ?>" class="<?= ($page =="vertical_list")?'current':'';?>" > لیست محصولات </a> | </li>
        <li><a href="<?= $current_url.'&sub_page=list_post' ?>" class="<?= ($page =="list_post")?'current':'';?>" > لیست پست ها </a> | </li>
        <li><a href="<?= $current_url.'&sub_page=sort_chideman' ?>" class="<?= ($page =="sort_chideman")?'current':'';?>" > مرتب سازی </a> | </li>

    </ul>
    <br class="clear" />
    <hr class="clear" />
	<?php
	if($page == "banner") require_once('woo2app_banner.php');
    elseif($page == "banner2") require_once('woo2app_banner2.php');
    elseif($page == "banner3") require_once('woo2app_banner3.php');
    elseif($page == "banner3_L") require_once('woo2app_left_banner3.php');
    elseif($page == "banner4") require_once('woo2app_banner4.php');
    elseif($page == "product_list") require_once('product_list.php');
    elseif($page == "vertical_list") require_once('woo2app_mainpage.php');
    elseif($page == "horizontal_lis") require_once('woo2app_mainpage.php');
    elseif($page == "shegeft_angiz") require_once('woo2app_mainpage.php');
    elseif($page == "sort_chideman") require_once('woo2app_sort_chideman.php');
    elseif($page == "list_post") require_once('list_post.php');
}elseif ($tab == 2) {
	require_once('woo2app_splash.php');
}elseif ($tab == 3) {
	require_once('woo2app_colors.php');
}elseif ($tab == 4) {
	require_once('woo2app_menu.php');
}elseif ($tab == 5) {
	require_once('woo2app_slider.php');
}
elseif ($tab == 'public') {
	require_once('public.php');
}
?>
</div>
