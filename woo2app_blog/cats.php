<!-- ******************this category list******************************************* -->
<?php
if (!defined( 'ABSPATH' )) exit;
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );

if ( isset( $_POST['wp2appir_primery_category'] ) &&
wp_verify_nonce( $_POST['wp2appir_primery_category'], 'wp2appir_category_primery' ) ) {
	
	if(isset($_POST['post_category']) || isset($_POST["tax_input"])){
		$product_cat = $_POST["tax_input"];
		if(!is_null($product_cat))
		{
			foreach($product_cat["product_cat"] as $key){
				$cats .= $key.",";
			}
		}
		if(!is_null($_POST['post_category']))
		{
			foreach ($_POST['post_category'] as $key) {
				$cats .= $key.",";
			}	
		}
		
	    $cats = substr( $cats , 0 , strlen($cats)-1 ); 
		if ($cats == "0") {
			$cats = "";
		}
		
		$r = update_option('wp2app_cats' , $cats); 
		if($r){
			update_option('hrt_lastmf_cat' , current_time( 'mysql' ));
		}
	}
}
?>
<?php
	$type_post = get_option("wp2app_post_types");// read custom post_types
    $ar = json_decode($type_post,true);
	if($type_post == ""){
		$taxonomy = get_object_taxonomies("post"); //get taxonomy post_type
		$category = get_categories(array("taxonomy" => "category"));
		
		$cats_post_types[] = "category"; 
		
	}else{
		
		foreach ($ar as $key ) {
			
			foreach ($key as $value) {
				foreach ($value as $k) {
					$cats_post_types[] = $k; 
				}
			}
		}
	}
?>
<div class="col-md-12" >

	<div class="col-md-5 pull-right" style="margin-top: 10px">
	<form method="post">
	<?php wp_nonce_field('wp2appir_category_primery' , 'wp2appir_primery_category'); ?>
		<div class="panel panel-default">
            <div class="panel-heading" style="">
                انتخاب دسته بندی های قابل نمایش در اپلیکیشن
            </div>
            <div class="panel-body">
            	<div class="col-md-12 text-right" style="text-align: justify;">
            		<p>دسته های مورد نظر خود را انتخاب کرده و دکمه تایید را کلیک کنید.</br>
            		توجه : به صورت پیش فرض تمام دسته بندی ها انتخاب شده اند.</p>
            		<p style="color:red;">توجه : برای انتخاب زیر دسته های یک دسته بندی باید خود آن دسته بندی نیز انتخاب شده باشد در غیر اینصورت در اپ دیده نمیشود.</p>
            	</div>
            	<input type="hidden" id="txt_cat_custom" name="txt_cat_custom">
				<div class="col-md-12" style="margin-right:15px;margin-top:20px;">
			<ul class="children">
				<li id="category-0" class="popular-category">
					<label class="selectit koodak"><input value="0" name="post_category[]" id="in-category-0" type="checkbox"> انتخاب همه</label>
				</li>
			</ul>
		</div>
		<?php
			foreach ($cats_post_types as $key ) {
				$my_tax = get_taxonomy($key);
			?>
				<div class="col-md-12" style="margin-top:15px;">
					<div class="col-md-12 text-right koodak" style="border:1px solid #e1e1e1;padding:5px;">
					<?= $my_tax->label; ?>
					</div>
						<?php
						echo post_categories_meta_box($key);
					    ?>            
            	</div>		
			<?php	
			}
		?>
            	<div class="col-md-12" style="margin-top:15px;padding:0px;">
            		<div class="col-md-4" style="margin-left:15px;padding:0px;">
	            		<button type="submit" class="btn btn-primary">
							تایید
							<label class="fa fa-2x fa-save"></label>
						</button>
					</div>
				</div>
        	</div>
        </div>
    </form>
    </div>
</div>
<!-- ******************this category list******************************************* -->
<?php
	$cat_selected = get_option('wp2app_cats');
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#category-all').addClass("koodak");
		jQuery('#product_cat-all').addClass("koodak");
		jQuery('#category-all').css({'font-weight':'100'});
		jQuery('#product_cat-all').css({'font-weight':'100'});
		var cat = "<?php echo $cat_selected; ?>";
		if(cat.length > 0)
		{
			var res = cat.split(","); 
			jQuery.each( res, function( key, v ) {
				  
				  jQuery('#in-category-' + v).attr('checked','checked');
			});
jQuery.each( res, function( key, v ) {
				  
				  jQuery('#in-product_cat-' + v).attr('checked','checked');
			});
		}else{
				jQuery('#in-category-0').attr('checked','checked');			
				jQuery('#in-product_cat-0').attr('checked','checked');
		}
		jQuery('.selectit input:not(#in-category-0)').click(function(){
             jQuery('#in-category-0').attr('checked',false);			
		});
jQuery('.selectit_p input:not(#in-product_cat-0)').click(function(){
alert(150);
             jQuery('#in-product_cat-0').attr('checked',false);			
		});
		jQuery('#in-category-0').click(function(){
			jQuery('.selectit input:not(#in-category-0)').attr('checked','checked');
		});
jQuery('#in-product_cat-0').click(function(){
alert(200);
			jQuery('.selectit_p input:not(#in-product_cat-0)').attr('checked','checked');
		});
	});
</script>
		<?php
		
function post_categories_meta_box($tax) {
	$defaults = array( 'taxonomy' => $tax );
		$args = array();
	
	$r = wp_parse_args( $args, $defaults );
	$tax_name = esc_attr( $r['taxonomy'] );
	$taxonomy = get_taxonomy( $r['taxonomy'] );
	
	?>
	<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
		<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
			
			<ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
				<?php wp_terms_checklist( $post->ID, array( 'taxonomy' => $tax_name, 'popular_cats' => $popular_ids ) ); ?>
			</ul>
			
		</div>
	</div>
	<?php
}
