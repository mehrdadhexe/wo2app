<?php
if (!defined( 'ABSPATH' )) exit;
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );
wp_register_style( 'bootstrap-select', WOO2APP_CSS_URL.'bootstrap-select.css'  );
wp_enqueue_style( 'bootstrap-select' );
?>
<div class="col-md-12">
	<div class="col-md-5 pull-right" style="margin-top: 10px">
		<div class="panel panel-default">
			<div class="panel-heading">
				چینمان اصلی
			</div>
			<div class="panel-body">
				<form name="form_sort" id="form_sort" method="post" action="">
					<ul id="sortable">

					</ul>
				</form>
				<div class="col-md-12 text-left" style="margin-top:15px;">
					<div class="col-md-8 ">
						<button  id="btn_form_order" class="btn btn-primary btn-sm">
							مرتب سازی
						</button>
					</div>
					<div class="col-md-4 ">
						<img style="width:25px;height:25px;" id="img_load" class="hide" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">
					</div>
				</div>
				<div class="col-md-12" style="margin-top:15px;">
					<div class="alert alert-success hide" id="alert_sort">

					</div>
				</div>
				<div class="col-md-12" style="margin-top:15px;">
					<?php $r = get_option("SHOW_BTN_CATLST");  ?>
					<p>
						<input type="checkbox" class="form-control" name="check_list_cat_mainpage" id="check_list_cat_mainpage"
							<?php echo ( 1 == $r ) ? "checked" :  "" ; ?> >
						<span>نمایش دکمه ی لیست دسته بندی ها</span>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
wp_enqueue_script( 'jquery-ui.js' , WOO2APP_JS_URL.'jquery-ui.js', array('jquery'));
wp_enqueue_script( 'sort.js' , WOO2APP_JS_URL.'sort.js', array('jquery'));
wp_enqueue_media();
wp_enqueue_script( 'upload_item_banner.js' , WOO2APP_JS_URL.'upload_item_banner.js', array('jquery'));
?><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script><?php
wp_enqueue_script( 'bootstrap-select.js' , WOO2APP_JS_URL.'bootstrap-select.js', array('jquery'));
?>
