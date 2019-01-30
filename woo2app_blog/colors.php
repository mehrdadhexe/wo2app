<?php
if (!defined( 'ABSPATH' )) exit;
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );
if($_POST){
	update_option("COLOR_BLOG_SELLOL_BG",$_POST['COLOR_BLOG_SELLOL_BG']);// color of actionbar
	update_option("COLOR_BLOG_SELLOL_TXT",$_POST['COLOR_BLOG_SELLOL_TXT']);// color of statusbar
	update_option("COLOR_BLOG_HEADER_BG",$_POST['COLOR_BLOG_HEADER_BG']);//background color main page
	update_option("COLOR_BLOG_HEADER_TXT",$_POST['COLOR_BLOG_HEADER_TXT']);//background color main page
	update_option("COLOR_BLOG_FOOTER_BG",$_POST['COLOR_BLOG_FOOTER_BG']);//background color main page
	update_option("COLOR_BLOG_FOOTER_TXT",$_POST['COLOR_BLOG_FOOTER_TXT']);//background color main page
}
?>
<div class="col-md-12">
	<div class="col-md-6 pull-right">
	<div class="panel panel-default" style="margin-top: 10px">
        <div class="panel-heading">
			تنظیمات رنگ وبلاگ
        </div>
        <div class="panel-body">
			<div class="panel-group"  id="accordion">
                    <form method="post">
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                        	<div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ بک گراند سلول</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option("COLOR_BLOG_SELLOL_BG"); ?>" name="COLOR_BLOG_SELLOL_BG" class="jscolor form-control" >
			                	</div>
			                </div>
			                <div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ متن داخل سلول</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option("COLOR_BLOG_SELLOL_TXT"); ?>" name="COLOR_BLOG_SELLOL_TXT" class="jscolor form-control" >
			                	</div>
			                </div>
			                   <div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ بک گراند هدر</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option("COLOR_BLOG_HEADER_BG"); ?>" name="COLOR_BLOG_HEADER_BG" class="jscolor form-control" >
			                	</div>
			                </div>
                            <div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ متن هدر</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option("COLOR_BLOG_HEADER_TXT"); ?>" name="COLOR_BLOG_HEADER_TXT" class="jscolor form-control" >
			                	</div>
			                </div>
			                 <div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ بک گراند فوتر</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option('COLOR_BLOG_FOOTER_BG'); ?>" name="COLOR_BLOG_FOOTER_BG" class="jscolor form-control" >
			                	</div>
			                </div>
			                 <div class="row" style="margin-bottom:10px;">
			                	<div class="col-md-6 pull-right" style="padding-top:5px;">
			                		<p>رنگ متن فوتر</p>
			                	</div>
			                	<div class="col-md-6 pull-right">
			                		<input value="<?= get_option('COLOR_BLOG_FOOTER_TXT'); ?>" name="COLOR_BLOG_FOOTER_TXT" class="jscolor form-control" >
			                	</div>
			                </div>
                        </div>
                    </div>
                    <div class="row" style="margin:10px auto;">
	            	<div class="col-md-6">
	            		<button  type="submit" class="btn btn-primary">
							ذخیره کردن
						</button>
	            	</div>
	            </div>
                    </form>
				
	            <div class="row" style="margin-top:15px;">
					<div class="alert alert-success hide" id="alert_color">
                                
                    </div>
				</div>
            </div>
    	</div>
	</div>
	</div>
	
</div>
<?php
if(get_option('exp_time_WOO2APP') != "" && get_option('exp_time_WOO2APP') <= time() && get_option('exp_time_WOO2APP') != -1){
	?>
		<script> jQuery('input').attr('disabled','true');</script>
	<?php
}
else{
	wp_enqueue_script( 'jscolor_woo.js' , WOO2APP_JS_URL.'jscolor_woo.js', array('jquery'));
}
//wp_enqueue_script( 'woo2app_colors.js' , WOO2APP_JS_URL.'woo2app_colors.js', array('jquery'));
wp_enqueue_script( 'bootstrap.min.js' , WOO2APP_JS_URL.'bootstrap.min.js', array('jquery'));
?>