<?php
if (!defined( 'ABSPATH' )) exit;
wp_register_style( 'bootstrap', WOO2APP_CSS_URL.'bootstrap.css'  );
wp_enqueue_style( 'bootstrap' );
wp_register_style( 'bootstrap-select', WOO2APP_CSS_URL.'bootstrap-select.css'  );
wp_enqueue_style( 'bootstrap-select' );
?>
    <div class="col-md-12">
        <div class="col-md-6 pull-right" style="margin-top: 10px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    آیتم جدید
                </div>
                <div class="panel-body">
                    <div class="col-md-12 pull-right">
                        <select class="form-control" id="select_item_option" style="margin-bottom: 10px">
                            <option value="3"> لیست عمودی </option>
                            <option value="2"> لیست افقی </option>
                            <option value="5"> پیشنهاد شگفت انگیز </option>
                        </select>
                    </div>
                    <div class="div_mainpage div_list_horizontal ">
                        <div class="col-md-12 pull-right">
                            <input placeholder="عنوان آیتم جدید" type="text" class="form-control" name="txt_title_hor_item" id="txt_title_hor_item" style="height:40px;">
                        </div>
                        <div class="col-md-12 pull-right" id="category_list" style="margin-top:15px;">
                            <select id="value_cat_items_hor" class="selectpicker col-md-12 form-control" data-live-search="true" title="انتخاب کنید...">
                                <option value="0" style="text-align:right;">دسته خود را انتخاب کنید</option>
                                <option style="text-align:right;" value="all"> همه دسته ها </option>
                                <?php
                                $args = array('hide_empty' => 0 ,
                                    'taxonomy' => 'product_cat'
                                );
                                $cats = get_categories($args);
                                foreach ($cats as $cat) {
                                    ?>
                                    <option style="text-align:right;" value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                            <select  id="value_sort_hor" class="form-control" style="height:40px;">
                                <option value=""> پیشفرض </option>
                                <option value="orderby=popularity&order=DESC"> محبوبیت </option>
                                <option value="orderby=date&order=DESC"> جدیدترین ها </option>
                                <option value="orderby=price&order=ASC"> کمترین قیمت </option>
                                <option value="orderby=price&order=DESC"> بیشترین قیمت </option>
                                <option value="orderby=selling&order=DESC"> پر فروش </option>
                            </select>
                        </div>
                    </div>
                    <div class="div_mainpage div_list_vertical hide">
                        <div class="col-md-12 pull-right">
                            <input placeholder="عنوان آیتم جدید" type="text" class="form-control" name="txt_title_ver_item" id="txt_title_ver_item" style="height:40px;">
                        </div>
                        <div class="col-md-12 pull-right" id="category_list" style="margin-top:15px;">
                            <select id="value_cat_items_ver" class="selectpicker col-md-12 form-control" data-live-search="true" title="انتخاب کنید...">
                                <option value="0" style="text-align:right;">دسته خود را انتخاب کنید</option>
                                <option style="text-align:right;" value="all"> همه دسته ها </option>
                                <?php
                                $args = array('hide_empty' => 0 ,
                                    'taxonomy' => 'product_cat'
                                );
                                $cats = get_categories($args);
                                foreach ($cats as $cat) {
                                    ?>
                                    <option style="text-align:right;" value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                            <select <?= (get_option('exp_time_WOO2APP') != "" && get_option('exp_time_WOO2APP') <= time())? 'disabled' : ''?> id="value_sort_ver" class="form-control" style="height:40px;">
                                <option value=""> پیشفرض </option>
                                <option value="orderby=popularity&order=DESC"> محبوبیت </option>
                                <option value="orderby=date&order=DESC"> جدیدترین ها </option>
                                <option value="orderby=price&order=ASC"> کمترین قیمت </option>
                                <option value="orderby=price&order=DESC"> بیشترین قیمت </option>
                                <option value="orderby=selling&order=DESC"> پر فروش </option>
                            </select>
                        </div>
                    </div>
                    <div class="div_mainpage div_pishnehad_vizhe hide">
                        <div class="col-md-12 pull-right" id="category_list" style="margin-top:15px;">
                            <select id="value_pishnehad_vizhe" class="selectpicker col-md-12 form-control" data-live-search="true" title="انتخاب کنید...">
                                <option value="0" style="text-align:right;"> لیست پیشنهاد ویژه را انتخاب کنید .</option>
                                <?php
                                global $wpdb;
                                $table_name = $wpdb->prefix . "options";
                                $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE option_name LIKE 'WOO2APP_PISH_SHEGEFT_ANGIZ%' ", OBJECT );
                                foreach ($results as $key) {
                                    $option_value = json_decode($key->option_value);
                                    $array = array();
                                    ?>
                                    <?php
                                    foreach ($option_value as $key1 => $value ) {
                                        if($key1 != "value"){
                                            ?>
                                            <option value="<?= $key->option_name?>" style="text-align:right;"> <?= $value;?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                ?>
                            </select>
                            <div class="form-group" style="margin-top: 10px">
                                <input type="text" placeholder="زمان انقضا بر حسب ساعت :) جون مادرت دقت کن با سایت یکی باشه" class="form-control" id="value_pishnehad_vizhe_time" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-left" style="margin-top:15px;">
                        <div class="col-md-8">
                            <button onclick ="add_item_mainpage()" type="button" class="btn btn-primary btn-sm">افزودن آیتم</button>
                        </div>
                        <div class="col-md-4 ">
                            <img style="width:25px;height:25px;" id="img_load1" class="hide" src="<?php echo WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ).'/../files/img/load.gif' ?>">
                        </div>
                    </div>
                    <div class="col-md-12 pull-right" style="margin-top:15px;">
                        <div class="alert alert-danger hide"  id="alert_item">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
wp_enqueue_script( 'jquery-ui.js' , WOO2APP_JS_URL.'jquery-ui.js', array('jquery'));
wp_enqueue_script( 'sort.js' , WOO2APP_JS_URL.'sort.js?ver=1.1', array('jquery'));
wp_enqueue_media();
wp_enqueue_script( 'upload_item_banner.js' , WOO2APP_JS_URL.'upload_item_banner.js', array('jquery'));
?><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script><?php
wp_enqueue_script( 'bootstrap-select.js' , WOO2APP_JS_URL.'bootstrap-select.js', array('jquery'));
?>