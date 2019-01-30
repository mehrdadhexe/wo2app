<?php
if (!defined( 'ABSPATH' )) exit;
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );
global $wpdb;
$table_name = $wpdb->prefix . 'woo2app_nazar';
if($_POST){
		if(isset($_POST['ins_q'])){
			$value = "";
			
			if($_POST['type'] == 2) {
				$i = 0;
				foreach ($_POST['opt'] as $key ) {
					if($key == ""){
						 unset($_POST['opt'][$i]);
					}
					$i++;
				}
				$value = json_encode($_POST['opt']);
			}
			$r = $wpdb->query( 
			$wpdb->prepare("INSERT INTO $table_name 
	   		( title , type , value , disable ) 	VALUES ( %s , %d , %s , %d )",
	   		 $_POST["title"] , $_POST["type"] , $value , 0));
		}
		if(isset($_POST['dis_q'])){
			$id = $_POST['id_q'];
			$wpdb->update( $table_name, 
				array( 'disable' => 1),
				array( 'id' => $id ));
		}
		if(isset($_POST['en_q'])){
			$id = $_POST['id_q'];
			$wpdb->update( $table_name, 
				array( 'disable' => 0),
				array( 'id' => $id ));
		}
		if(isset($_POST['del_q'])){
			$id = $_POST['id_q'];
			$r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d" , $id));
		}
}
?>

<div class="container" >
	<div class="col-md-8" style="margin: 0 auto;float: none;">
	    <div class="panel panel-default">
		  <div class="panel-heading"><h3 class="panel-title"><strong> درج سوال </strong></h3></div>
			  <div class="panel-body">
			   <form role="form" method="post">
				  <div class="form-group">
				    <label for="exampleInputEmail1"> عنوان / سوال</label>
				    <div style="float: left;">
				    	<input type="radio" name="type" value="1" checked="" /> تشریحی
				    	<input type="radio" name="type" value="2" /> گزینه ای
				    </div>
				    <textarea name="title" class="form-control"></textarea>
				  </div>
				  <div id="div_opt" class="hide">
				  	<div class="form-group">
				   <label> گزینه اول </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				    <div class="form-group">
				   <label> گزینه دوم </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				    <div class="form-group">
				   <label> گزینه سوم </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				    <div class="form-group">
				   <label> گزینه چهارم </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				    <div class="form-group">
				   <label> گزینه پنجم </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				    <div class="form-group">
				   <label> گزینه ششم </label>
				   	<input type="text" name="opt[]" class="form-control" />
				  </div>
				  </div>
				   
				  <button type="submit" name="ins_q" class="btn btn-sm btn-default"> ثبت سوال</button>
			</form>
			  </div>
		</div>
	</div>
   
    <div class="col-sm-12 col-md-12" style="margin: 0 auto;float: none;"> 
    	<div class="panel panel-default">
        	  <div class="panel-heading"><h3 class="panel-title"><strong> لیست سوالات </strong></h3></div>
			  <div class="panel-body">
			  	<table class="table" id="table">
	                <thead>
	                    <tr>
	                        <th class="text-center"> سوال</th>
	                        <th class="text-center"> تشریحی / گزینه ای</th>
	                        <th class="text-center"> -------- </th>
	                        <th class="text-center"> ----------- </th>
	                    </tr>
	                </thead>
	                <tbody class="text-center">
	                	<?php
	                	$rec = $wpdb->get_results("select * from $table_name  order by disable asc");
						$array = array();
						foreach ($rec as $key ) {
							?>
							 <tr class="<?= ($key->disable == 1)? 'danger':'' ;?>" style="position: relative;">
		                        <td style="vertical-align: middle;"><?= $key->title;?></td>
		                        <td style="vertical-align: middle;"><?= ($key->type == 1)? 'تشریحی' : 'گزینه ای'?></td>
		                        <td style="text-align:right;vertical-align: middle; width: 40%;">
		                        	<?php
		                        		if($key->value != ""){
		                        			$str = json_decode($key->value);
		                        			$i = 0;
		                        			foreach ($str as $s) {
		                        				$i++;
		                        				echo $i.' - '.$s . '<br>';
		                        			}
		                        		}
		                        	?>
		                        </td>
		                        <td style="vertical-align: middle;">
		                        	<?php
		                        		if($key->disable == 1 ){
		                        			?>
		                        				<form method="post" style="float: left;margin-right: 5px;">
		                        					<input type="hidden" name="id_q" value="<?= $key->id;?>">
		                        					<input type="submit" name="en_q" class="btn btn-success btn-xs" value="فعال">
		                        				</form>
		                        			<?php
		                        		}
		                        		else{
		                        			?>
		                        				<form method="post" style="float: left;margin-right: 5px;">
		                        					<input type="hidden" name="id_q" value="<?= $key->id;?>">
		                        					<input type="submit" name="dis_q" class="btn btn-warning btn-xs" value="غیر فعال">
		                        				</form>
		                        			<?php
		                        		}
		                        	?>
		                        	<form method="post" style="float: left;margin-right: 5px;">
		                        		<input type="hidden" name="id_q" value="<?= $key->id;?>">
                    					<input type="submit" onclick="return confirm('مطمئن به حذف سوال هستید?')" name="del_q" class="btn btn-danger btn-xs" value="حذف">
                    				</form>
		                        </td>
		                    </tr>
							<?php
						}
	                	?>
	                </tbody>
	            </table>
			  </div>
    	</div>
        <hr>
    </div>



<?php
 $current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
wp_enqueue_script( 'jquery-ui.js' , WOO2APP_JS_URL.'jquery-ui.js', array('jquery'));
wp_enqueue_media();
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript">

	function areyou(){
		if(confirm(" Are You Sure ?")){
			return false;
		}
		else{
			return false;
		}
	}
	jQuery('input[name=type]').on('change', function() {
   		var type_val = (jQuery('input[name=type]:checked').val()); 
   		if(type_val == 1){
   			jQuery("#div_opt").addClass('hide');
   		}
   		else if(type_val == 2){
   			jQuery("#div_opt").removeClass('hide');	
   		}
	});
</script>
