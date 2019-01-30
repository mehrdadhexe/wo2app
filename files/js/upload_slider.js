jQuery(document).ready(function(){
    
    var custom_uploader_hami;
    jQuery('#add_slider_hami_div').on('click','#btn_url_slider_hami',function(e) {
        e.preventDefault();
        custom_uploader_hami = wp.media.frames.custom_uploader_hami = wp.media({
				title: 'انتخاب تصویر اسلایدر',
				library: {type: 'image'},
				button: {text: 'انتخاب'},
				multiple: false
        });
        custom_uploader_hami.on('select', function() {  
			attachment = custom_uploader_hami.state().get('selection').first().toJSON();
            jQuery('#txt_url_slider_hami').val(attachment.url);
            url_image = attachment.url;
            
            jQuery('#div_image_slider_hami').css('background-image', 'url(' + url_image + ')');
            jQuery('#div_image_slider_hami').css('background-size', '100% 100%');
            jQuery('#div_image_slider_hami').css('background-repeat', 'no-repeat');
            jQuery('#div_image_slider_hami').removeClass('hide');
        });
        custom_uploader_hami.open();
    });
});