<?php
/**
 * Created by mr2app.
 * User: hani
 * Date: 12/18/17
 * Time: 9:44 AM
 */
?>
<style>
    input[type="checkbox"][readonly] {
        pointer-events: none;
    }
</style>
<?php
if (!defined( 'ABSPATH' )) exit;

$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if($_POST['submit']) {
	$array = array(
		'post_title'    => $_POST['title'],
		'post_content' => $_POST['name'],
		'post_type'     => 'woo2app_register',
		'post_status'   => 'draft',
		'menu_order' => 100,
	);

	if($post = wp_insert_post( $array )){
		add_post_meta($post,'default',$_POST['default']);
		add_post_meta($post,'required',($_POST['required'] == 'on') ? 1 : 0);
		add_post_meta($post,'active',($_POST['active'] == 'on') ? 1 : 0);
		update_post_meta($post,'display_edit',($_POST['display_edit'] == 'on') ? 1 : 0);
		update_post_meta($post,'display_register',($_POST['display_register'] == 'on') ? 1 : 0);
		add_post_meta($post,'values',$_POST['values']);
		add_post_meta($post,'type',$_POST['type']);
        if($_POST['type'] == 'text'){
			add_post_meta($post,'validate',$_POST['validate']);
		}
		else{
			add_post_meta($post,'validate','');
		}
		wp_redirect("admin.php?page=woo2app/register/register.php&action=edit&id=".$post);
	}

}

if(isset($_REQUEST["submit_edit"])){

	$array = array(
		'ID' => $_GET['id'],
		'post_title'    => $_POST['title'],
		'post_content' => $_POST['name'],
		'post_type'     => 'woo2app_register',
		'post_status'   => 'draft',
	);
	if($post = wp_update_post( $array )){
		update_post_meta($post,'default',$_POST['default']);
		update_post_meta($post,'required',($_POST['required'] == 'on') ? 1 : 0);
		update_post_meta($post,'active',($_POST['active'] == 'on') ? 1 : 0);
		update_post_meta($post,'display_edit',($_POST['display_edit'] == 'on') ? 1 : 0);
		update_post_meta($post,'display_register',($_POST['display_register'] == 'on') ? 1 : 0);
		update_post_meta($post,'values',$_POST['values']);
		update_post_meta($post,'type',$_POST['type']);
		if($_POST['type'] == 'text'){
			update_post_meta($post,'validate',$_POST['validate']);
		}
		else{
			update_post_meta($post,'validate','');
		}
		wp_redirect("admin.php?page=woo2app/register/register.php&action=edit&id=".$_GET['id']);
	}


}
if($_GET['action'] == 'edit'){
	$id = $_GET['id'];
	$post = get_post($id);
	$default_fields  = array( 'user_login'  , 'user_email' , 'user_pass','user_url' ,  'display_name' ,
		'first_name' , 'last_name' , 'description' , 'billing_first_name' , 'billing_last_name','billing_company','billing_address_1',
		'billing_address_2','billing_city','billing_state','billing_postcode','billing_country','billing_email','billing_phone');
	?>
    <div class="wrap" >
        <h1>
            ویرایش فیلد
        </h1>
        <div id="col-container" class="">
            <div class="col-wrap" style="width: 50%;">
                <div class="form-wrap">
                    <form id="addtag" method="post" action="" class="validate">
                        <div class="form-field ">
                            <label > عنوان </label>
                            <input required type="text" value="<?= $post->post_title;?>" name="title"  />
                            <p>
                                عنوانی که در اپ دیده میشود
                            </p>
                        </div>
                        <div class="form-field ">
                            <label> نام <span style="color: red">   <?= (in_array($post->post_content , $default_fields)) ? 'قابل ویرایش نمی باشد.' : '' ;?> </span> </label>
                            <input required type="text" dir="ltr" id="txt_name" <?= (in_array($post->post_content , $default_fields)) ? 'readonly' : '' ;?> value="<?= $post->post_content;?>" name="name"  />
                            <p>
                                متا نیمی که برای زمینه دلخواه در نظر گرفته میشود  در ثبت سفارش با همین نام ارسال میشود.
                            </p>
                        </div>
                        <div class="form-field ">
							<?php
							$type = get_post_meta($post->ID,'type')[0];
							?>
                            <label> نوع </label>
                            <select name="type" <?= ($post->post_content  == 'state' || $post->post_content == 'city')? 'readonly' : ''?> onchange="change_type_list(this)">
                                <option <?= ($type == 'text')? 'selected' : '' ;?> value="text"> کادر متنی </option>
                                <option <?= ($type == 'list')? 'selected' : '' ;?> value="list"> لیست کشویی </option>
                                <option <?= ($type == 'paragraph')? 'selected' : '' ;?> value="paragraph"> پاراگراف </option>
                                <option <?= ($type == 'radio_button')? 'selected' : '' ;?> value="radio_button"> دکمه رایویی </option>
                                <option <?= ($type == 'map')? 'selected' : '' ;?> value="map"> نقشه </option>
                            </select>
                        </div>
                        <div class="form-field validate_form" style="<?= ($type != 'text') ? 'display:none' : ''?>">
                            <?php
                            $validate = get_post_meta($post->ID,'validate' , true);
                            ?>
                            <label>  اعتبار سنجی </label>
                            <select name="validate" >
                                <option <?= ($validate == 'general')? 'selected' : '' ;?> value="general"> عمومی </option>
                                <option <?= ($validate == 'email')? 'selected' : '' ;?> value="email"> ایمیل </option>
                                <option <?= ($validate == 'phone')? 'selected' : '' ;?> value="phone"> شماره تلفن </option>
                                <option <?= ($validate == 'number')? 'selected' : '' ;?> value="number"> عدد </option>
                                <option <?= ($validate == 'password')? 'selected' : '' ;?> value="password"> رمز </option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label> پیش فرض </label>
                            <input type="text"   <?= ($post->post_content == 'user_login' || $post->post_content == 'user_email' || $post->post_content == 'user_pass')? 'readonly' : ''?> value="<?= get_post_meta($id , 'default')[0]?>" name="default"  />
                            <p>
                                پیش فرضی که برای فیلد در نظر گرفته شده است.
                            </p>
                        </div>
                        <div class="form-field ">
                            <p style="">
                                <input   <?= ($post->post_content == 'user_login' || $post->post_content == 'user_email' || $post->post_content == 'user_pass')? 'readonly' : ''?> style="float: right"  type="checkbox" <?= get_post_meta($id , 'required')[0] ? 'checked' : '';?>  name="required"  />
                                <label style="float: right;margin-right: 5px" > اجباری ؟ (اجباری بودن فیلد را مشخص می کند) </label>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input  <?= ($post->post_content == 'user_login' || $post->post_content == 'user_email' || $post->post_content == 'user_pass')? 'readonly' : ''?> style="float: right" type="checkbox"  <?= get_post_meta($id , 'active')[0] ? 'checked' : '';?> name="active"  />
                                <label style="float: right;margin-right: 5px;"> فعال بودن (مشخص میکند ، کاربر می تواند پیشفرض را ویرایش کند یا خیر) </label>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input   <?= ($post->post_content == 'user_login' || $post->post_content == 'user_pass')? 'readonly' : ''?> style="float: right"  type="checkbox" <?= get_post_meta($id , 'display_register')[0] ? 'checked' : '';?> name="display_register"  />
                                <label style="float: right;margin-right: 5px;">
                                    نمایش در فرم ثبت نام کاربر
                                </label>
	                                <?php
	                                if($post->post_content == 'user_email'){
	                                    echo '<div class="clear"></div>';
	                                    echo '(اگر برای فیلد ایمیل این گزینه فعال نباشد ، از نام کاربری ایمیل درست خواهد شد و به عنوان ایمیل ارسال خواهد شد. مثال : username@site.com)';
                                    }
	                                ?>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input  style="float: right"  type="checkbox" <?= get_post_meta($id , 'display_edit')[0] ? 'checked' : '';?> name="display_edit"  />
                                <label style="float: right;margin-right: 5px;">
                                    نمایش در فرم ویرایش کاربر
                                </label>
                            </p>
                        </div>
                        <br>
                        <div class="form-field values_area"  style="<?= ($post->post_content  == 'state' || $post->post_content == 'city')? 'display:none' : (get_post_meta($id , 'type')[0] == 'list' || get_post_meta($id , 'type')[0] == 'radio_button') ? '' : 'display:none'?>">
                            <label > مقادیر </label>
                            <textarea  name="values"><?= get_post_meta($id , 'values')[0];?></textarea>
                            <p>
                                اگر فیلد دارای مقادیر قابل انتخاب باشد در این فیلد ذخیره می شود ، مقادیر با ,  از هم جدا میشوند.
                            </p>
                        </div>

                        <div class="form-field " >
                            <p class="submit">
                                <input type="submit" name="submit_edit"  class="button button-primary" value="ویرایش"  />
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php
}
else{
	?>
    <div class="wrap" >
        <h1>
            فیلد جدید
        </h1>
        <p class="admin-msg">
            اضافه کردن فیلد جدید به فرم ثبت نام
        </p>
        <div id="col-container" class="">
            <div class="col-wrap" style="width: 50%;">
                <div class="form-wrap">
                    <form id="addtag" method="post" action="" class="validate">
                        <div class="form-field ">
                            <label > عنوان </label>
                            <input type="text"  required name="title"  />
                            <p>
                                عنوانی که در اپ دیده میشود
                            </p>
                        </div>
                        <div class="form-field ">
                            <label> نام </label>
                            <input type="text" required dir="ltr" id="txt_name" name="name"  />
                            <p>
                                متا نیمی که برای زمینه دلخواه در نظر گرفته میشود  در ثبت سفارش با همین نام ارسال میشود.
                            </p>
                        </div>
                        <div class="form-field ">
                            <label> نوع </label>
                            <select name="type" onchange="change_type_list(this)">
                                <option value="text"> کادر متنی </option>
                                <option value="list"> لیست کشویی </option>
                                <option value="paragraph"> پاراگراف </option>
                                <option value="radio_button"> دکمه رایویی </option>
                                <option value="map">  نقشه </option>
                            </select>
                        </div>
						 <div class="form-field validate_form">
                            <?php
                            $validate = get_post_meta($post->ID,'validate' , true);
                            ?>
                            <label>  اعتبار سنجی </label>
                            <select name="validate" >
                                <option <?= ($validate == 'general')? 'selected' : '' ;?> value="general"> عمومی </option>
                                <option <?= ($validate == 'email')? 'selected' : '' ;?> value="email"> ایمیل </option>
                                <option <?= ($validate == 'phone')? 'selected' : '' ;?> value="phone"> شماره تلفن </option>
                                <option <?= ($validate == 'number')? 'selected' : '' ;?> value="number"> عدد </option>
                                <option <?= ($validate == 'password')? 'selected' : '' ;?> value="password"> رمز </option>
                            </select>
                        </div>
                        <div class="form-field ">
                            <label> پیش فرض </label>
                            <input type="text"  name="default"  />
                            <p>
                                پیش فرضی که برای فیلد در نظر گرفته شده است.
                            </p>
                        </div>
                        <div class="form-field ">
                            <p style="">
                                <input   style="float: right"  type="checkbox"   name="required"  />
                                <label style="float: right;margin-right: 5px" > اجباری ؟ (اجباری بودن فیلد را مشخص می کند) </label>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input   style="float: right" type="checkbox"   name="active"  />
                                <label style="float: right;margin-right: 5px;"> فعال بودن (مشخص میکند ، کاربر می تواند پیشفرض را ویرایش کند یا خیر) </label>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input   style="float: right"  type="checkbox"  name="display_register"  />
                                <label style="float: right;margin-right: 5px;">
                                    نمایش در فرم ثبت نام کاربر
                                </label>
                            </p>
                            <div class="clear"></div>
                            <p style="">
                                <input  style="float: right"   type="checkbox"  name="display_edit"  />
                                <label style="float: right;margin-right: 5px;">
                                    نمایش در فرم ویرایش کاربر
                                </label>
                            </p>
                        </div>
                        <br>
                        <div class="form-field values_area" style="display: none">
                            <label > مقادیر </label>
                            <textarea name="values"></textarea>
                            <p>
                                اگر فیلد دارای مقادیر قابل انتخاب باشد در این فیلد ذخیره می شود ، مقادیر با ,  از هم جدا میشوند.
                            </p>
                        </div>
                        <div class="form-field " >
                            <p class="submit">
                                <input type="submit" name="submit"  class="button button-primary" value="ذخیره"  />
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php
}
?>
<script>
    function change_type_list(e) {
        if(e.value == 'list' || e.value == 'radio_button'){
            jQuery(".values_area").attr('style','display:block');
        }
        else{
            jQuery(".values_area").attr('style','display:none');
        }
		
		if(e.value != 'text'){
			jQuery(".validate_form").attr('style','display:none');
		}
		else{
			jQuery(".validate_form").attr('style','display:block');
		}
    }
</script>