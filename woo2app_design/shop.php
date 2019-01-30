<?php


$current_url = "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
function insert_woo2app_shop(){
	$name = $_POST["name"];
	$subdomain = $_POST["subdomain"];
	$pic = $_POST["src_val"];
	$uniqid = uniqid();

	$array = get_option("mr2app_shop");
	$array[] = array(
		"uniqid" => $uniqid ,
		"name" => $name ,
		"subdomain" => $subdomain ,
		"icon" => $pic ,
	);
	update_option("mr2app_shop",$array);
}
function update_woo2app_shop() {
	if ( isset( $_GET['field'] ) ) {
		if ( $_GET['field'] != "" ) {
			$name = $_POST["name"];
			$subdomain = $_POST["subdomain"];
			$pic = $_POST["src_val"];
			$key = $_GET["field"];
			$plans = get_option("mr2app_shop");
			$i = 0;
			foreach ($plans as $value) {
				if ($key == $value["uniqid"]) {
					$array[] = array(
						"uniqid" => $value["uniqid"] ,
						"name" => $name ,
						"subdomain" => $subdomain ,
						"icon" => $pic ,
					);
				}
				else{
					$array[] = array(
						"uniqid" => $value["uniqid"] ,
						"name" => $value["name"] ,
						"subdomain" => $value["subdomain"] ,
						"icon" => $value["icon"] ,
					);
				}
				$i++;
			}
			update_option("mr2app_shop",$array);
			?>
			<p>
				ویرایش انجام شد...
				<br>
				<a href="admin.php?page=woo2app/woo2app_design/shop.php" > بازگشت </a>
			</p>
			<?php
			exit;
			//wp_redirect('admin.php?page=mr2app_sale_meta/sale_meta.php&tab=plans');
		}
	}
}

function delete_woo2app_shop(){
	if(isset($_GET['field'])){
		if($_GET['field'] != ""){
			$key = $_GET["field"];
			$plans = get_option("mr2app_shop");
			$f = "";
			$i = 0;
			foreach ($plans as $value) {
				if ($key == $value["uniqid"]) {
					$f = $i;
				}
				$i++;
			}
			array_splice($plans, $f, 1);
			update_option("mr2app_shop",$plans);
			?>
			<p>
				فروشگاه مورد نظر حذف شد...
				<br>
				<a href="admin.php?page=woo2app/woo2app_design/shop.php" > بازگشت </a>
			</p>
			<?php
			exit;
			//wp_redirect('admin.php?page=mr2app_sale_meta/sale_meta.php&tab=plans');
		}
	}
}
$for_edit = array();
$for_edit['uniqid'] = '';
$for_edit['name'] = '';
$for_edit['subdomain'] = '';
$for_edit['value'] = '';

if(isset($_GET["action"])){
	if($_GET['action'] == 'delete'){
		delete_woo2app_shop();
	}
	elseif($_GET['action'] == 'edit'){
		if (
			isset($_POST["name"])
			&& $_POST["name"] != ""
		)
		{
			update_woo2app_shop();
		}
		else{
			if(isset($_GET['field'])){
				$plans = get_option("mr2app_shop");
				$i = 0;
				$key = $_GET['field'];
				foreach ($plans as $value) {
					if ($key == $value["uniqid"]) {
						$for_edit = array(
							"uniqid" => $value["uniqid"] ,
							"name" => $value["name"] ,
							"subdomain" => $value["subdomain"] ,
							"value" => $value["icon"] ,
						);
					}
				}
			}
		}
	}
}
else{
	if (
		isset($_POST["name"])
		&& $_POST["name"] != ""
	)
	{
		insert_woo2app_shop();
	}
}
$plans = get_option("mr2app_shop");
if(!is_array($plans)) $plans = array();
?>
<h1>
    چند فروشگاهی
</h1>
<p>
    این بخش نیازمند فعال سازی
    <a target="_blank" href="http://mr2app.com/blog/multi-store">
        ماژول چندفروشگاهی اپلیکیشن
    </a>
    ، در اپلیکیشن می باشد.
</p>
<div class="wrap">
	<form action="" method="post">
		<table class="form-table" style="direction: rtl">
			<tbody>
			<tr valign="top" class="">
				<th scope="row" class="titledesc">
					<label> نام  </label>
				</th>
				<td class="forminp">
					<input type="text" value="<?= $for_edit['name']?>"  name="name"   placeholder=" نام " >
				</td>
			</tr>
			<tr>
			<th scope="row" class="titledesc">
				<label >  زیر دامنه  </label>
			</th>
			<td class="forminp">
				<input type="text"  value="<?= $for_edit['subdomain']?>" name="subdomain"   placeholder="زیر دامنه">
			</td>
			</tr>

			<tr  valign="top"  class="el_type"  id="image"  >
				<th scope="row" class="titledesc">
					<label dir="rtl">    عکس </label>
				</th>
				<td class="forminp">
					<input type="hidden" value="<?= $for_edit['value']?>" id="txt_url_item_hami" name="src_val">
					<img id="div_image_item_hami" class="banner" style="height:128px;cursor: pointer;" src="<?= ($for_edit['value'] != '')? $for_edit['value'] : 'http://placehold.it/512x512'?>">
				</td>
			</tr>

			<tr >
				<th scope="row" class="titledesc"></th>
				<td class="forminp">
					<input type="submit"  value="ذخیره"  name="submit_banner">
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<hr>
	<table class="wp-list-table widefat striped" style="direction: rtl">
		<tbody>
		<tr valign="top" class="">
			<th scope="row" class="">
				<label > #  </label>
			</th>
			<th scope="row" class="">
				<label > عکس   </label>
			</th>
			<th scope="row" class="">
				<label > نام   </label>
			</th>
			<th scope="row" class="">
				<label > زیر دامنه  </label>
			</th>
			<th scope="row" class="">
				<label >  ویرایش / حذف   </label>
			</th>
		</tr>
		<?php
		$i = 0;
		foreach ($plans as $key){
			$i++;
			?>
			<tr valign="top" class="">
				<th scope="row" class="">
					<label > <?= $i; ?>  </label>
				</th>
				<th scope="row" class="">
					<img style="width: 92px;" src="<?= $key['icon']?>">
				</th>
				<th scope="row" class="">
					<label > <?= $key['name'];?>   </label>
				</th>
				<th scope="row" class="">
					<label > <?= $key['subdomain'];?>   </label>
				</th>
				<th scope="row" class="">
					<a href="<?= $current_url . '&action=edit&field='.$key['uniqid']?>"  > ویرایش </a>
					|
					<a href="<?= $current_url . '&action=delete&field='.$key['uniqid']?>" style="color: red;"  >  حذف </a>
				</th>
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

    var custom_uploader_hami;
    jQuery('#div_image_item_hami').click(function (e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami').val(attachment.url);
            url_image = attachment.url;
            jQuery('#div_image_item_hami').attr("src",url_image);
        });
        custom_uploader_hami.open();
    })
</script>
