jQuery(function(jQuery){

    jQuery('.js-example-basic-single').select2();
    // multiple select with AJAX search
    jQuery('.js-data-example-ajax').select2({
        ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 250, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                if(jQuery(this).attr('data-id') == 'product'){
					//alert(params.term);
                    return {
                        action: 'woo2app_product', // AJAX action for admin-ajax.php
                        q: params.term // search query
                    };
                }
                else{
                    return {
                        action: 'woo2app_posts', // AJAX action for admin-ajax.php
                        q: params.term // search query
                    };
                }
            },
            processResults: function( data ) {
                console.log(data);
                var options = [];
                if ( data ) {
                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                        options.push( { id: text[0], text: text[1]  } );
                    });
                }
                return {
                    results: options
                };
            }
            // cache: true
        },
        minimumInputLength: 3 // the minimum of symbols to input before perform a search
    });
});
