<?php $current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style('bootstrap');

if(isset($_GET["tab"]))
	$tab = $_GET["tab"];
else
	$tab = "cats";
?>

<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
	<a href="<?= $current_url.'&tab=cats' ?>" class="nav-tab <?= ($tab == 'cats') ? 'nav-tab-active' : ''; ?>">
		دسته بندی ها
	</a>
	<a href="<?= $current_url.'&tab=colors' ?>" class="nav-tab <?= ($tab == 'colors') ? 'nav-tab-active' : ''; ?>">
		تنظیمات رنگ
	</a>		
</nav>

<?php
if($tab == 'cats' || $tab == ""){
	require_once('cats.php');
}elseif ($tab == 'colors') {
	require_once('colors.php');
}
?>
