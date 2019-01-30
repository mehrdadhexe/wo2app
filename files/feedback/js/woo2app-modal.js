
var $ = jQuery;
$(document).ready(function(){

    var $woo2appModal = $(".woo2app-Modal");
    if($woo2appModal){
        new Modalwoo2app($woo2appModal);
    }
});

function send_feedback(url) {
    var $reason = $('#woo2app-reason').val();
    var $details = $('#woo2app-details').val();
    if($reason != ""){
        $.post(
            ajaxurl,
            {
                action: 'woo2app_send_feedback',
                reason : $reason,
                detail : $details
            },
            function(response) {
                //var data = JSON.parse(response);
                console.log(response)
                location.href = url;
            }
        );
    }
}


function Modalwoo2app(aElem) {

    var refThis = this;

    this.elem = aElem;
    this.overlay = $('.woo2app-Modal-overlay');
    this.radio = $('input[name=reason]', aElem);
    this.closer = $('.woo2app-Modal-close, .woo2app-Modal-cancel', aElem);
    this.return = $('.woo2app-Modal-return', aElem);
    this.opener = $('.plugins [data-slug="woo2app"] .deactivate');
    this.question = $('.woo2app-Modal-question', aElem);
    this.button = $('.button-primary', aElem);
    this.title = $('.woo2app-Modal-header h2', aElem);
    this.textFields = $('input[type=text], textarea',aElem);
    this.hiddenReason = $('#woo2app-reason', aElem);
    this.hiddenDetails = $('#woo2app-details', aElem);
    this.titleText = this.title.text();

    // Open
    this.opener.click(function() {
        refThis.open();
        return false;
    });

    // Close
    this.closer.click(function() {
        refThis.close();
        return false;
    });


    // Back
    this.return.click(function() {
        refThis.returnToQuestion();
        return false;
    });

    // Click on radio
    this.radio.change(function(){
        refThis.change($(this));
        refThis.button.removeClass('woo2app-isDisabled');
        refThis.button.removeAttr("disabled");
    });

    this.textFields.keyup(function() {
        refThis.hiddenDetails.val($(this).val());
    });

}


/*
* Change modal state
*/
Modalwoo2app.prototype.change = function(aElem) {

    var id = aElem.attr('id');
    var refThis = this;

    // Reset values
    this.hiddenReason.val(aElem.val());
    this.hiddenDetails.val('');
    this.textFields.val('');

    $('.woo2app-Modal-fieldHidden').removeClass('woo2app-isOpen');
    $('.woo2app-Modal-hidden').removeClass('woo2app-isOpen');
    this.button.removeClass('woo2app-isDisabled');
    this.button.removeAttr("disabled");
    var field = aElem.siblings('.woo2app-Modal-fieldHidden');
    field.addClass('woo2app-isOpen');
    field.find('input, textarea').focus();
    refThis.button.addClass('woo2app-isDisabled');
    refThis.button.attr("disabled", true);

};



/*
* Return to the question
*/
Modalwoo2app.prototype.returnToQuestion = function() {

    $('.woo2app-Modal-fieldHidden').removeClass('woo2app-isOpen');
    $('.woo2app-Modal-hidden').removeClass('woo2app-isOpen');
    this.question.addClass('woo2app-isOpen');
    this.return.removeClass('woo2app-isOpen');
    this.title.text(this.titleText);

    // Reset values
    this.hiddenReason.val('');
    this.hiddenDetails.val('');

    this.radio.attr('checked', false);
    this.button.addClass('woo2app-isDisabled');
    this.button.attr("disabled", true);

};


/*
* Open modal
*/
Modalwoo2app.prototype.open = function() {
    //alert(1)
    this.elem.css('display','block');
    this.overlay.css('display','block');
    localStorage.setItem('woo2app-hash', '');
};


/*
* Close modal
*/
Modalwoo2app.prototype.close = function() {

    this.returnToQuestion();
    this.elem.css('display','none');
    this.overlay.css('display','none');

};
