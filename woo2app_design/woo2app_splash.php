<style>
    .banner:hover{
        border:2px solid #555;
    }
</style>
<?php
if($_POST['submit_banner']){
	if($_REQUEST["src_val"] != "") {
		update_option("URL_SPLASH_PIC" , $_REQUEST["src_val"]);
	}
	if($_REQUEST["delay_splash"] != "") {
		update_option("NUM_SPLASH_DELAY" , $_REQUEST["delay_splash"]);
	}
}
?>
<form action="" method="post">
    <table class="form-table " style="direction: rtl">
        <tbody>
        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label > زمان تاخیر اسپلش :
                </label>
            </th>
            <td class="forminp">
                <input type="number"  value="<?= get_option('NUM_SPLASH_DELAY')?>" name="delay_splash" placeholder="زمان تاخیر اسپلش ">
            </td>
        </tr>

        <tr  >
            <th scope="row" class="titledesc">
                <label dir="rtl">    عکس </label>
                <p style="color: red">
                    ابعاد مثلا 1334 در 750
                </p>
            </th>
            <td class="forminp">
                <input type="hidden" id="txt_url_item_hami" name="src_val">
                <img id="div_image_item_hami" class="banner" style="cursor: pointer;width: 187.5px;height: 333.5px" src="<?= get_option('URL_SPLASH_PIC') ? get_option('URL_SPLASH_PIC') : 'http://placehold.it/1334x750'?> ">
            </td>
        </tr>
        <tr >
            <th scope="row" class="titledesc">

            </th>
            <td class="forminp">
                <input type="submit"  value="ذخیره"  name="submit_banner">
            </td>


        </tbody>
    </table>
</form>

<?php wp_enqueue_media();?>
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