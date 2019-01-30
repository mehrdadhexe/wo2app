<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

<?php
wp_register_style( 'select', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css'  );
wp_enqueue_style( 'select' );



function delete_option_by_id($option_name){
    $option = get_option($option_name);
    if(!$option){
        return;
    }
    delete_option($option_name);
    global $wpdb;
    $table_name = $wpdb->prefix . "options";
    $results = $wpdb->get_results( "SELECT  * FROM $table_name WHERE option_name LIKE 'mr2app_cat_%' " ,OBJECT);
    foreach ($results as $r) {
        $value  = json_decode( $r->option_value );
        $parent = explode( '_', $option_name );
        if($value->parent == $parent['2']){
            delete_option_by_id($r->option_name);
        }
    }
}

$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(isset($_REQUEST['sort'])){
    $ids = $_POST['ids'];
    $i = 0;
    //var_dump($ids);
    foreach ($ids as $id){

        $i++;
        $cat = json_decode(get_option("mr2app_cat_$id"));
        //var_dump($cat);
        $array = array(
            'id' => $cat->id,
            'label' => $cat->label,
            'parent' => $cat->parent,
            'order' => $i
        );
        update_option("mr2app_cat_$id",json_encode($array));
    }
}
if(isset($_REQUEST["submit_edit"])){
    $count = $_REQUEST['hidden_edit'];
    $op_ed = json_decode(get_option('mr2app_cat_'.$count));
    if(isset($op_ed)){
        $order = $op_ed->order;
    }
    else{
        $order = 1000;
    }
    $slug = $_REQUEST['edit_product_cat'];
    $category = get_term_by( 'slug', $slug, 'product_cat' );
    $label = ($_REQUEST['edit_label'] != "") ? $_REQUEST['edit_label'] : $category->name;
    $parent = $_REQUEST['edit_parent'];
    $id = $category->term_id;
    $array = array(
        'id' => $id,
        'label' => $label,
        'parent' => $parent,
        'order' => $order
    );
    update_option('mr2app_cat_'.$count,json_encode($array));
}

if(isset($_POST['submit'])){
    if($_REQUEST["product_cat"]){
        global $wpdb;
        $table_name = $wpdb->prefix . "options";
        $results = $wpdb->get_results( "SELECT  * FROM $table_name  ORDER BY option_id DESC LIMIT 1" ,OBJECT);

        $count = $results[0]->option_id + 1;
        $slug = $_REQUEST['product_cat'];
        $category = get_term_by( 'slug', $slug, 'product_cat' );
        $label = ($_REQUEST['label'] != "") ? $_REQUEST['label'] : $category->name;
        $parent = $_REQUEST['parent'];
        $id = $category->term_id;
        $array = array(
            'id' => $id,
            'label' => $label,
            'parent' => $parent,
            'order' => 1000
        );
        update_option('mr2app_cat_'.$count,json_encode($array));

        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>
                    دسته بندی با موفقیت ذخیره شد
                </strong>
            </p>
        </div>
        <div class="clear"></div>
        <?php
    }
    else{
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><strong> با مشکل مواجه شد ، لطفا مجددا تلاش کنید. </strong></p>
        </div>
        <div class="clear"></div>
        <?php
    }
}
$edit_id = 0;
$current = 0;
$current_id = -1;
if(isset($_GET['action']) && isset($_GET['id'])){
    if($_GET['action'] == 'edit'){
        $current = get_option('mr2app_cat_'.$_GET['id']);
        if($current){
            $current = json_decode($current);
            $current_id = $_GET['id'];
        }
    }
    elseif($_GET['action'] == 'delete'){
        $current = get_option('mr2app_cat_'.$_GET['id']);
        $current = json_decode($current);
        delete_option_by_id('mr2app_cat_'.$_GET['id']);
        //delete_option('mr2app_cat_'.$_GET['id']);
        header('Location: '.$current_url.'&action=edit&id='.$current->parent);
        exit();
    }
}
?>
<div class="wrap">
    <p>

        این بخش نیازمند فعال سازی

        <a target="_blank" href="http://mr2app.com/blog/woo2app-custom-category">

            ماژول چیدمان سفارشی دسته بندی محصولات

        </a>

        ، در اپلیکیشن می باشد.

    </p>
    <div id="col-container" class="wp-clearfix">
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h1>افزودن دسته بندی دراپلیکشین </h1>
                    <form id="addtag" method="post" action="" class="validate">
                        <!--                    <input type="hidden" name="hidden_edit" value="--><?//= $current_id;?><!--">-->
                        <div class="form-field form-required term-name-wrap">
                            <label for="tag-name"> نام </label>
                            <input name="label"  type="text" value=""   />
                            <p>در اپ با این نام نمایش داده می‌شود.</p>
                        </div>
                        <div class="form-field term-slug-wrap">
                            <select id="product_cat" required="required" name="product_cat" title="انتخاب کنید...">
                                <option value="" style="text-align:right;">دسته خود را انتخاب کنید</option>
                                <?php
                                $args = array('hide_empty' => 0 ,
                                    'taxonomy' => 'product_cat'
                                );
                                $cats = get_categories($args);
                                foreach ($cats as $cat) {
                                    ?>
                                    <option  value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p>
                                دسته را انتخاب کنید و به دسته بندی در اپ اضافه کنید.
                            </p>
                        </div>
                        <div class="form-field term-parent-wrap">
                            <label for="parent">دسته بندی مادر</label>
                            <select  name='parent' id='parent' class='postform' >
                                <option value='-1'>هیچ کدام</option>
                                <?php
                                global $wpdb;
                                $table_name = $wpdb->prefix . "options";
                                $results = $wpdb->get_results( "SELECT  * FROM $table_name WHERE option_name LIKE 'mr2app_cat_%' " ,OBJECT);
                                $values = array();
                                foreach ($results as $r){
                                    $value = json_decode($r->option_value);
                                    $parent = explode('_',$r->option_name);
                                    $values[] = array(
                                        'id' => $value->id,
                                        'label' => $value->label,
                                        'parent' => $value->parent,
                                        'order' => $value->order,
                                        'parent_2' => $parent['2']
                                    );
                                    ?>
                                    <option  value="<?= $parent['2']?>"><?= $value->label;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p>
                                با انتخاب یک سرپرست (والد)از دسته بندی ها اپ می‌توانید سلسله‌مراتب بسازید.
                            </p>
                        </div>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="افزودن دسته بندی"  />
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <div id="col-right" >
            <div class="col-wrap ">
                <form name="form_sort" id="form_sort" method="post" action="">

                    <table class="wp-list-table widefat fixed striped tags ui-sortable ">
                        <thead>
                        <tr>
                            <th scope="col" id='name' class='manage-column column-name '>
                                <span> عنوان </span>
                            </th>
                            <th scope="col" id='name' class='manage-column column-name '>
                                <span> نامک </span>
                            </th>
                            <th scope="col" id='name' class='manage-column column-name '>
                                <span style="cursor: pointer"> ------- </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
                        <?php
                        usort($values, function($a, $b) {
                            return $a['order'] - $b['order'];
                        });
                        //var_dump($values);
                        foreach ($values as $value){
                            //$value = json_decode($r->option_value);
                            if($value['parent'] != $current_id) continue;
                            $category = get_term_by( 'id', $value['id'], 'product_cat' );
                            $i=0;
                            foreach ($results as $rr){
                                $value1 = json_decode($rr->option_value);
                                $parent = explode('_',$r->option_name);
                                //echo $value1->parent;
                                //echo $parent['2'];
                                if($value1->parent == $value['parent_2'])
                                    $i++;
                                //else continue;

                            }
                            ?>
                            <tr id="tag-<?= $value['id'];?>" style="cursor: move" class="<?= 'tag-'.$value['parent']?>">
                                <input type="hidden" name="ids[]" value="<?= $value['parent_2']; ?>">
                                <form action="" method="post">
                                    <input type="hidden" name="hidden_edit" value="<?= $value['parent_2'];?>">
                                    <td class='name column-name has-row-actions column-primary' data-colname="نام">
                                        <p><strong ><a style="direction: rtl" class="row-title" href="<?= $current_url; ?>&action=edit&id=<?= $value['parent_2'];?>"> <?= $value['label'];?> (<?= $i; ?>) </a></strong></p>
                                        <input class="el_edit el_edit_<?= $value['parent_2'];?>" value="<?= $value['label']?>" name="edit_label" style="display: none" type="text" placeholder="عنوان">
                                        <input class="el_edit el_edit_<?= $value['parent_2'];?>" value="<?= $value['parent']?>" name="edit_parent" type="hidden">
                                    </td>
                                    <td class='description column-description' data-colname="نامک">
                                        <p><?= urldecode($category->slug);?> </p>
                                        <div class="el_edit el_edit_<?=  $value['parent_2'];?>"  style="display: none">
                                            <select   style="width: 80%" name="edit_product_cat" title="انتخاب کنید...">
                                                <option value="" style="text-align:right;">دسته خود را انتخاب کنید</option>
                                                <?php
                                                foreach ($cats as $cat) {
                                                    ?>
                                                    <option <?= ($cat->term_id == $value['id'])? 'selected' : ''?>  value="<?= $cat->slug; ?>"><?= $cat->name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class='description column-description' >
                                        <p><span class='edit'>
								<a style="cursor: pointer" onclick="show_edit('<?= $value['parent_2'];?>')" >ویرایش</a>
								|
							</span>
                                            <span ><a onclick="delete_category('<?=  $value['parent_2'];?>')" style="cursor:pointer;color: red" > پاک کردن </a></span>
                                        </p>
                                        <div class="el_edit_<?=  $value['parent_2'];?>" style="display: none">
                                            <input type="submit" name="submit_edit" class="button button-primary" value="ویرایش">
                                            <a style="cursor:pointer" onclick="hide_edit('<?=  $value['parent_2'];?>')" class="button button-default"> لغو </a>
                                        </div>
                                    </td>
                                </form>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <input type="submit" name="sort" value="مرتب سازی">
                </form>
            </div>
        </div>
    </div>
</div>


<?php
wp_enqueue_script( 'jquery-ui.js' , WOO2APP_JS_URL.'jquery-ui.js', array('jquery'));
?>
<script>
    jQuery(function() {
        jQuery( "#sortable" ).sortable();
        jQuery( "#sortable" ).disableSelection();
    });
    jQuery(function () {
        jQuery("select").select2();
    });
    function show_edit(id) {
        //jQuery(".el_edit").attr('style' , 'display:none');
        jQuery(".el_edit_" + id).attr('style' , 'display:block');
    }
    function hide_edit(id) {
        jQuery(".el_edit_" + id).attr('style' , 'display:none');
    }
    function delete_category(id) {
        if(confirm("آیا مطمئن به حذف هستید؟")){
            location.href = "<?= $current_url?>&action=delete&id="+id;
        }
    }
    searchBox = document.querySelector("#searchBox");
    countries = document.querySelector("#product_cat");
    var when = "keyup"; //You can change this to keydown, keypress or change

    searchBox.addEventListener("keyup", function (e) {
        var text = e.target.value;
        var options = countries.options;
        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            var optionText = option.text;
            var lowerOptionText = optionText.toLowerCase();
            var lowerText = text.toLowerCase();
            var regex = new RegExp("^" + text, "i");
            var match = optionText.match(regex);
            var contains = lowerOptionText.indexOf(lowerText) != -1;
            if (match || contains) {
                option.selected = true;
                return;
            }
            searchBox.selectedIndex = 0;
        }
    });

</script>


