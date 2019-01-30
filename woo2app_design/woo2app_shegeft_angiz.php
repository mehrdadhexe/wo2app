<?php


if (!defined( 'ABSPATH' )) exit;


wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );


wp_enqueue_style( 'bootstrap' );


wp_register_style( 'bootstrap-select', WOO2APP_CSS_URL.'bootstrap-select.css'  );


wp_enqueue_style( 'bootstrap-select' );





global $wpdb;


$table_name = $wpdb->prefix . "options";


$results = $wpdb->get_results( "SELECT  * FROM $table_name WHERE option_name LIKE 'WOO2APP_PISH_SHEGEFT_ANGIZ%' " ,OBJECT);


$count = count($results) + 1;


if($_POST){


	if(isset($_POST['ins_vizhe'])){


		$array = array();


		$array['title'] = ($_POST['title']);


		$array['value'] = ($_POST['list_product']);


		update_option("WOO2APP_PISH_SHEGEFT_ANGIZ_$count", json_encode($array));


	}


	if(isset($_POST['del_q'])){


		delete_option($_POST['id_q']);


	}


}





$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE option_name LIKE 'WOO2APP_PISH_SHEGEFT_ANGIZ%' " , OBJECT );


//var_dump($results);


?>





<div class="container row" >


    <div class="col-md-4 col-sm-12" style="margin: 0 auto;float: none;">


        <div class="panel panel-default" style="margin-top: 10px">


            <div class="panel-heading"><h3 class="panel-title"><strong> انتخاب لیست </strong></h3></div>


            <div class="panel-body">


                <div class="col-md-12">


                    <input id="search_product_banner1" class="form-control" placeholder="اینجا اسم محصول موردنظر رو سرج کن" type="text">


                </div>


                <div class="col-md-12 pull-right " id="loading_product" style="margin-top:10px;">


                    <select name="product" id="value_post_items" class="form-control"></select>


                    <img style="display:none;" height="25" width="25" id="img_load" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">


                </div>





                <form role="form" method="post">


                    <div class="col-md-12 pull-right form-group" style="margin-top: 10px">


                        <div class="col-md-10 pull-right" id="div_show_span" >


                            <select multiple class="form-control" required name="list_product[]" id="sel_inserted_product">





                            </select>


                        </div>


                        <div class="col-md-2 pull-right"  style="margin-top: 20px; margin-left:10px">


                            <button type="button" id="btn_remove_option" class="btn btn-default btn-xs pull-right"> حذف محصول انتخاب شده </button>


                        </div>


                    </div>


                    <div class="form-group">


                        <input type="text" required placeholder="این اسم لیسته. بزن پیشنهادات شگفت انگیز :)" class="form-control" name="title" />


                    </div>


                    <div class="col-md-12  text-center" >


                        <button type="submit" onclick="jQuery('#sel_inserted_product option').prop('selected', true);" name="ins_vizhe" class="btn btn-sm btn-default"> ذخیره لیست </button>


                    </div>


                </form>





            </div>


        </div>


    </div>





    <div class="col-sm-12 col-md-12" style="margin: 0 auto;float: none;">


        <div class="panel panel-default">


            <div class="panel-heading"><h3 class="panel-title"><strong> لیست پیشنهادات ویژه . لطفا بعد از  وارد کردن لیست جدید قبلی رو حذف کن </strong></h3></div>


            <div class="panel-body">


                <table class="table" id="table">


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


						$option_value = json_decode($key->option_value);


						$array = array();


						?>


                        <tr  >


							<?php


							foreach ($option_value as $key1 => $value ) {


								?>





								<?php


								if($key1 != "value"){


									?>


                                    <td style="vertical-align: middle;"><?= $value;?></td>


									<?php


								}


                                elseif($key1 == "value"){


									?>


                                    <td style="vertical-align: middle;">


										<?php


										foreach ($value as $key_value) {


											echo get_the_title( $key_value ).'<br>';


										}


										?>


                                    </td>


									<?php


								}


								?>





								<?php


							}


							?>


                            <td style="vertical-align: middle;">


                                <form method="post" style="margin-right: 5px;">


                                    <input type="hidden" name="id_q" value="<?= $key->option_name;?>">


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


	<?php


	wp_enqueue_script( 'bootstrap-select.js' , WOO2APP_JS_URL.'bootstrap-select.js', array('jquery'));


	?>


    <script type="text/javascript">





        jQuery('#btn_remove_option').click(function(){


            jQuery('#sel_inserted_product option:selected').remove();


            jQuery('#sel_inserted_product option').prop('selected', true);


        })





        jQuery('#value_post_items').change(function(){


            //alert(jQuery(this).val());


            jQuery("#sel_inserted_product").append('<option value="'+ jQuery(this).val() +'">'+ jQuery("#value_post_items option:selected").text() +'</option>');


            jQuery('#sel_inserted_product option').prop('selected', true);





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


                    }


                });


            }


        });


    </script>


