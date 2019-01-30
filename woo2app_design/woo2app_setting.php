<?php
wp_register_style( 'bootstrap', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/css/bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );
global $wpdb;
$table_name = $wpdb->prefix . 'woo2app_setting';
$resq = $wpdb->get_results("select * from $table_name where st_name = 'cs_key' or st_name = 'ck_key'  ");
$ckkey ="";
$cskey ="";
foreach ($resq as $key) {
	if($key->st_name == "ck_key") $ckkey = $key->st_value;
	if($key->st_name == "cs_key") $cskey = $key->st_value;
}
if(isset($_POST[cskey_woo2app] )&& isset($_POST[ckkey_woo2app])){
	if(trim($_POST[cskey_woo2app]) == "" ||  trim($_POST[ckkey_woo2app]) == "" ){
		$_SESSION["sucess_woo2app"] = "error_api";
	}else{
		$res = $wpdb->get_results("select * from $table_name where st_name = 'ck_key' or st_name = 'ck_key'  ");
		if(!empty($res)){
			$r = $wpdb->update( $table_name, 
			array( 'st_value' => $_POST["cskey_woo2app"]),
			array( 'st_name' => "cs_key" ) );
			$r = $wpdb->update( $table_name, 
			array( 'st_value' => $_POST["ckkey_woo2app"]),
			array( 'st_name' => "ck_key" ) );
			$_SESSION["success_woo2app"] = "OK_API";
		}else{
			$r = $wpdb->query( 
					$wpdb->prepare("INSERT INTO $table_name 
			   		( st_name , st_value ) 
			   		VALUES ( %s , %s )", 
			   		"cs_key" , $_POST["cskey_woo2app"])
			   		 );
			$r1 = $wpdb->query(
					$wpdb->prepare("INSERT INTO $table_name 
			   		( st_name , st_value ) 
			   		VALUES ( %s , %s )",
			   		"ck_key" , $_POST["ckkey_woo2app"])
			   		 );		
			$_SESSION["success_woo2app"] = "OK_API";
		}	
	}
	
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-6 pull-right">
			<div class="panel panel-primary">
                <div class="panel-heading">
                    تنظیمات کلید API
                </div>
                <form method="POST">
                <div class="panel-body">
                    <div class="col-md-12 row" style="margin-bottom:15px;">
                    	<input value="<?= $cskey; ?>" type="text" class="form-control text-left" name="cskey_woo2app" id="cskey_woo2app" placeholder="CSKEY">
                    </div>
                    <div class="col-md-12 row" style="margin-bottom:15px;">
                    	<input value="<?= $ckkey; ?>" type="text" class="form-control text-left" name="ckkey_woo2app" id="ckkey_woo2app" placeholder="CKKEY">
                    </div>
                    <div class="col-md-12 row text-left" style="margin-bottom:15px;">
                    	<button type="submit" class="btn btn-primary btn-sm">ذخیره</button>
                    </div>
                    <?php
                    if($_SESSION["success_woo2app"] == "OK_API"){
                    ?>
                    <div class="col-md-12 row text-right" style="margin-bottom:15px;">
                    	<div class="alert alert-success">
                           اطلاعات ذخیره شد.
                        </div>
                    </div>
                    <?php
                    }elseif($_SESSION["success_woo2app"] == "error_api" ){
                    ?>
                    <div class="col-md-12 row text-right" style="margin-bottom:15px;">
                    	<div class="alert alert-danger">
                           خطا در ذخیره اطلاعات ، لطفا دوباره امتحان کنید.
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                </form>
            </div>
		</div>
	</div>
</div>