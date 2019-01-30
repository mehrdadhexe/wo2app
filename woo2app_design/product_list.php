<?php
global $wpdb;
$table_name = $wpdb->prefix . "woo2app_mainpage";
if($_POST){
	if(isset($_POST['ins_list'])){
		$title = $_REQUEST["title"];
		$type = 0;
		$sort = $_REQUEST["sort"]  ;
		$value = $_REQUEST["value"]  ;
		$showtype =  9 ;
		$pic = $_REQUEST["pic"];
		$pic = time() + $pic * (60*60);
		$value = json_encode($_POST['list_product']);
		$res = $wpdb->get_results("select MAX(mp_order) AS max_order from $table_name");
		if(!is_null($res[0]->max_order)){
			foreach ($res as $key) {
				$max = $key->max_order + 1;
			}
		}else{
			$max = 1;
		}

		$r = $wpdb->query( $wpdb->prepare("INSERT INTO $table_name 
		( mp_title , mp_type , mp_value , mp_showtype , mp_pic , mp_order ,mp_sort) 
		VALUES ( %s, %d, %s, %d, %s, %d, %s )", $title,$type,$value,$showtype,$pic,$max, $sort) );
		if($r){
			echo "<p style='color:green'>". "لیست محصولات دلخواه ذخیره شد .از قسمت مرتب سازی مرتب کنید." .'</p>';
		}
	}
	if(isset($_POST['del_q'])){
		$id = $_POST['id_q'];
		$r = $wpdb->query( $wpdb->prepare("DELETE FROM $table_name WHERE mp_id = $id " ) );
		if($r){
			echo "<p style='color:green'>". "آیتم مورد نظر حذف شد." .'</p>';
		}
	}
}

$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE  	mp_showtype = 9 " , OBJECT );
?>
<div class="">
    <form action="" method="post">
        <table class="form-table " style="direction: rtl">
            <tbody>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > عنوان  </label>
                </th>
                <td class="forminp">
                    <input type="text"  name="title" id="title" value="" placeholder="عنوان">
                </td>
            </tr>
            <tr valign="top" class="el_type" id="el_products" >
                <th scope="row" class="titledesc">
                    <label > محصول </label>
                </th>
                <td class="forminp">
                    <input id="search_product_banner1" placeholder="نام محصول را جستجو کنید ...." type="text">
                    <select name="product" id="value_post_items" style="width:200px" class="form-control"></select>
                    <img style="display:none;" height="25" width="25" id="img_load" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">
                </td>
            </tr>
            <tr  >
                <th scope="row" class="titledesc">
                    <label dir="rtl">     لیست اضافه شده  </label>

                </th>
                <td class="forminp">
                    <select multiple style="width: 200px;" class="form-control" required name="list_product[]" id="sel_inserted_product">

                    </select>
                    <button type="button" id="btn_remove_option" > Remove </button>
                </td>
            </tr>

            <tr>
                <th scope="row" class="titledesc">

                </th>
                <td class="forminp">
                    <input type="submit" name="ins_list" value="اضافه"  name="submit_banner">
                </td>
            </tr>

            </tbody>
        </table>
    </form>
    <hr>
    <h3 class="panel-title"><strong> لیست محصولات دلخواه </strong></h3>
    <table class="wp-list-table widefat  striped " style="direction: rtl">
        <thead>
        <tr>
            <th class="text-center"> عنوان </th>
            <th class="text-center"> محصولات </th>
            <th class="text-center"> -------- </th>
        </tr>
        </thead>
        <tbody class="text-center">
		<?php
		$i = 0;
		foreach ($results as $key) {
			$i ++ ;
			?>
            <tr valign="top" >
                <td style="vertical-align: middle;"><?= $key->mp_title;?></td>
                <td style="vertical-align: middle;">
					<?php
					$i=1;
					$option_value = json_decode($key->mp_value);
					foreach ($option_value as $key1) {
						echo $i++.' - '.get_the_title( $key1 ).' , ';
					}
					?>
                </td>
                <td style="vertical-align: middle;">
                    <form method="post" style="margin-right: 5px;">
                        <input type="hidden" name="id_q" value="<?= $key->mp_id;?>">
                        <input type="submit" onclick="return confirm('مطمئن به حذف لیست هستید?')" name="del_q" class="btn btn-danger btn-xs" value="حذف">
                    </form>
                </td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>

</div>
<?php
wp_enqueue_media();
?>
<script>

    jQuery('#select_action_banner').on('change', function() {

        var type_val = (jQuery('#select_action_banner').val());
        //alert(type_val)
        jQuery('.el_type').css('display','none');
        if(type_val == 1){
            jQuery('#el_products').css('display','table-row');
        }
        else if(type_val == 2){
            jQuery('#el_cats').css('display','table-row');
        }
        else if(type_val == 3){
            jQuery('#el_link').css('display','table-row');
        }
    });

    jQuery('#search_product_banner1').keyup(function(){
        // alert(11)
        var  v = jQuery(this).val();
        if(v.length >= 3){
            jQuery("#img_load").attr("style",'display:block');
            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: "load_all_product",
                    search_product : v
                },
                success:function(data) {
                    jQuery("#img_load").attr("style",'display:none');
                    jQuery("#value_post_items").html(data);
                    jQuery("#value_post_items").prepend('<option selected value="0"> انتخاب کنید ....</option>')

                }
            });
        }
    });

</script>
<script type="text/javascript">


    jQuery('#btn_remove_option').click(function(){
        jQuery('#sel_inserted_product option:selected').remove();
        jQuery('#sel_inserted_product option').prop('selected', true);
    })


    jQuery('#value_post_items').change(function(){
        //alert(jQuery(this).val());
        if(jQuery(this).val() == '0') return;
        else{
            jQuery("#sel_inserted_product").append('<option value="'+ jQuery(this).val() +'">'+ jQuery("#value_post_items option:selected").text() +'</option>');
            jQuery('#sel_inserted_product option').prop('selected', true);
        }
    })
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
