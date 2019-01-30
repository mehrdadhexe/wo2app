jQuery(document).ready(function(){

    var custom_uploader_hami;
    jQuery('#add_item_hami_div').on('click','#btn_sel_pic_banner',function(e) {

        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر اسلایدر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami').val(attachment.url);
            url_image = attachment.url;

            /*jQuery('#div_image_item_hami').css('background-image', 'url(' + url_image + ')');
            jQuery('#div_image_item_hami').css('background-size', '150px auto');
            jQuery('#div_image_item_hami').css('background-repeat', 'no-repeat');*/
            jQuery('#div_image_item_hami').attr("src",url_image);
            jQuery('#div_image_item_hami').css({'margin' : '10px auto' , 'height' : '100px'  , ' max-width' : '200px' });
            jQuery('#div_image_item_hami').removeClass('hide');
        });
        custom_uploader_hami.open();
    });

    jQuery('#add_item_hami_div1').on('click','#btn_sel_pic_banner1',function(e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر اسلایدر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami1').val(attachment.url);
            url_image = attachment.url;
            jQuery('#div_image_item_hami1').attr("src",url_image);
            jQuery('#div_image_item_hami1').css({'margin' : '10px auto' , 'height' : '100px'  , ' max-width' : '200px' });
            jQuery('#div_image_item_hami1').removeClass('hide');
        });
        custom_uploader_hami.open();
    });

    jQuery('#add_item_hami_div2').on('click','#btn_sel_pic_banner2',function(e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر اسلایدر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami2').val(attachment.url);
            url_image = attachment.url;

            jQuery('#div_image_item_hami2').attr("src",url_image);
            jQuery('#div_image_item_hami2').css({'margin' : '10px auto' , 'height' : '100px'  , ' max-width' : '200px' });
            jQuery('#div_image_item_hami2').removeClass('hide');
        });
        custom_uploader_hami.open();
    });

    jQuery('#add_item_hami_div3').on('click','#btn_sel_pic_banner3',function(e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر اسلایدر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami3').val(attachment.url);
            url_image = attachment.url;

            jQuery('#div_image_item_hami3').attr("src",url_image);
            jQuery('#div_image_item_hami3').css({'margin' : '10px auto' , 'height' : '100px'  , ' max-width' : '200px' });
            jQuery('#div_image_item_hami3').removeClass('hide');
        });
        custom_uploader_hami.open();
    });

    jQuery('#add_item_hami_div4').on('click','#btn_sel_pic_banner4',function(e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
            title: 'انتخاب تصویر اسلایدر',
            library: {type: 'image'},
            button: {text: 'انتخاب'},
            multiple: false
        });
        custom_uploader_hami.on('select', function() {
            attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_item_hami4').val(attachment.url);
            url_image = attachment.url;

            /*jQuery('#div_image_item_hami4').css('background-image', 'url(' + url_image + ')');
            jQuery('#div_image_item_hami4').css('background-size', '100% 100%');
            jQuery('#div_image_item_hami4').css('background-repeat', 'no-repeat');*/
            jQuery('#div_image_item_hami4').attr("src",url_image);
            jQuery('#div_image_item_hami4').css({'margin' : '10px auto' , 'height' : '100px'  , ' max-width' : '200px' });
            jQuery('#div_image_item_hami4').removeClass('hide');
        });
        custom_uploader_hami.open();
    });

});