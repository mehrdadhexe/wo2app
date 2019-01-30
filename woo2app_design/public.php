<?php
if(isset($_POST["submit_banner"])){
	update_option("DEFAULT_FONT_APP" , $_POST['font'] );
	update_option("DEFAULT_PRODUCT_CELL" ,  $_POST['product_cell'] );
	update_option("category_them" ,  $_POST['category_them'] );
	update_option("category_select_btn" ,  $_POST['category_select_btn'] );
	update_option("NAVIGATION_BUTTON" ,   $_POST['NAVIGATION_BUTTON']  );
}
?>
<div class="wrap">
    <form action="" method="post">
        <table class="form-table " style="direction: rtl">
            <tbody>
            <tr  class="" >
                <th scope="row" class="titledesc">
                    <label >  فونت </label>
                </th>
                <td>
                    <select name="font" style="w">
                        <option <?php echo (get_option("DEFAULT_FONT_APP") == 1) ? "selected" : "" ; ?> value="1">یکان</option>
                        <option <?php echo (get_option("DEFAULT_FONT_APP") == 2) ? "selected" : "" ; ?> value="2">کودک</option>
                        <option <?php echo (get_option("DEFAULT_FONT_APP") == 3) ? "selected" : "" ; ?> value="3">ترافیک</option>
                        <option <?php echo (get_option("DEFAULT_FONT_APP") == 4) ? "selected" : "" ; ?> value="4">نازنین</option>
                        <option <?php echo (get_option("DEFAULT_FONT_APP") == 5) ? "selected" : "" ; ?> value="5">ایران سنس </option>
                    </select>
                    <p class="description" id="tagline-description"> میتوانید فونت اپلیکشین خود را تنظیم نمایید.</p>
                </td>
                <td></td>
            </tr>
            <tr  class="">
                <th scope="row" class="titledesc">
                    <label>    سلول محصولات </label>
                </th>
                <td class="forminp" style="vertical-align: top" >
                    <select name="product_cell" onchange="change_demo_img(this)">
                        <option <?php echo (get_option("DEFAULT_PRODUCT_CELL") == 1) ? "selected" : "" ; ?> value="1"> مدل 1  </option>
                        <option <?php echo (get_option("DEFAULT_PRODUCT_CELL") == 2) ? "selected" : "" ; ?> value="2"> مدل 2  </option>
                    </select>
                    <p class="description">
                        مدل نمایش سلول محصول در اپلیکشین را مشخص میکند.
                    </p>
                </td>
                <td>
                    <img id="demo_img_cell_1" class="demo_img_cell" src="http://mr2app.com/document/uploaded/img/demo_img_cell_1.jpg" style="<?= (get_option("DEFAULT_PRODUCT_CELL") == 1)?'display:block':'display:none';?>;width: 256px;" >
                    <img id="demo_img_cell_2" class="demo_img_cell" src="http://mr2app.com/document/uploaded/img/demo_img_cell_2.jpg" style="<?= (get_option("DEFAULT_PRODUCT_CELL") == 2)?'display:block':'display:none';?>;width: 256px;" >
                </td>
            </tr>
            <tr  class="">
                <th scope="row" class="titledesc">
                    <label>  نمایش دسته بندی </label>
                </th>
                <td class="forminp" style="vertical-align: top" >
                    <select name="category_them" >
                        <option <?php echo (get_option("category_them") == 1) ? "selected" : "" ; ?> value="1">  خطی  </option>
                        <option <?php echo (get_option("category_them") == 2) ? "selected" : "" ; ?> value="2">  جدول(grid)  </option>
                    </select>
                    <p class="description">
                        مدل نمایش لیست دسته بندی در اپلیکشین را مشخص میکند.
                    </p>
                </td>
                <td>
                </td>
            </tr>
            <tr  class="">
                <th scope="row" class="titledesc">
                    <label>   دکمه انتخاب در دسته بندی  </label>
                </th>
                <td class="forminp" style="vertical-align: top" >
                    <select name="category_select_btn" >
                        <option <?php echo (get_option("category_select_btn") == 1) ? "selected" : "" ; ?> value="1">  نمایش  </option>
                        <option <?php echo (get_option("category_select_btn") == 2) ? "selected" : "" ; ?> value="2">  عدم نمایش  </option>
                    </select>
                    <p class="description">
                        به وسیله این گزینه میتوانید نمایش و یا عدم نمایش دکمه انتخاب در لیست دسته بندی را مشخص کنید.
                    </p>
                </td>
                <td>
                </td>
            </tr>
            <tr  class="">
                <th scope="row" class="titledesc">
                    <label>   تغییر نمایش صفحه اصلی </label>
                </th>
                <td class="forminp" style="vertical-align: top" >
                    <select name="NAVIGATION_BUTTON" >
                        <option <?php echo (get_option("NAVIGATION_BUTTON") == 0) ? "selected" : "" ; ?> value="0">  تم اصلی  </option>
                        <option <?php echo (get_option("NAVIGATION_BUTTON") == 1) ? "selected" : "" ; ?> value="1">  تم تب بار  </option>
                    </select>
                </td>
                <td>
                </td>
            </tr>
            <tr style="border-top: 1px solid #e5e5e5">
                <th scope="row" class="titledesc">
                </th>
                <td class="forminp">
                    <input type="submit"  class="button button-primary" value=" ذخیره تغییرات "  name="submit_banner">
                </td>
            </tbody>
        </table>
    </form>
</div>
<script>
    function change_demo_img(e) {
        //alert(e.value);
        jQuery(".demo_img_cell").attr('style','display:none');
        jQuery("#demo_img_cell_" + e.value).attr('style','display:block;width: 256px');
    }
</script>