// serialize the form to JSON
jQuery.fn.serializeObject=function(){"use strict";var a={},b=function(b,c){var d=a[c.name];"undefined"!=typeof d&&d!==null?jQuery.isArray(d)?d.push(c.value):a[c.name]=[d,c.value]:a[c.name]=c.value};return jQuery.each(this.serializeArray(),b),a};
(function( $ ) {
    "use strict";

    $(function(){
        var $feedback_popup = $('.aux-feedback-notice'),
            $theme_menu     = $('.toplevel_page_auxin-welcome');

        // skip if feedback popup is not present
        if( ! $feedback_popup.length ){
            return;
        }

        // apply animation and position to popup
        var $feedback_popup_top_offset = $theme_menu.length ? $theme_menu.offset().top -20 : '130';
        $theme_menu.addClass('aux-highlight-focus');
        $feedback_popup.css('top', $feedback_popup_top_offset ).removeClass('aux-not-animated');

        // highlight selected rating
        $feedback_popup.find('.aux-rate-cell').on( 'click', function(){
            $('.aux-feedback-notice .aux-rate-cell').removeClass('checked');
            $(this).addClass('checked');
        });

        $feedback_popup.find('input[name="theme_rate"]').on('change', function(){
            var rateValue = $(this).val(),
                $status_progress       = $('.aux-sending-status'),
                $conditional_sections    = $('.aux-conditional-section');

            $status_progress.removeClass('aux-hide');
            $conditional_sections.addClass('aux-disappear');

            $.ajax({
                url: auxin.ajaxurl,
                type: "post",
                data: {
                    form   : $('.aux-popup-feedback-form').serializeObject(),
                    action : 'send_feedback' // the ajax handler,
                }
            }).done( function( response ){

                if ( response.success ) {
                    $status_progress.addClass('aux-hide');

                    if ( rateValue > 8 ) {
                        $('.aux-rate-on-market').removeClass('aux-disappear');
                    } else {
                        $('.aux-feedback-detail').removeClass('aux-disappear');
                    }
                }

            });
        });

        $('.aux-close-form:not(.btn-submit)').on('click', function(){

            $.ajax({
                url: auxin.ajaxurl,
                type: 'post',
                data: {
                    action: 'aux-remove-feedback-notice',
                    form   : $('.aux-popup-feedback-form').serializeObject(),
                }
            }).done(function(response){
                $('.aux-feedback-notice').remove();
            });

        });

        $('.aux-close-form.btn-submit').on('click', function(){

            $.ajax({
                url: auxin.ajaxurl,
                type: 'post',
                data: {
                    action: 'send_feedback',
                    form  : $('.aux-popup-feedback-form').serializeObject(),
                }
            }).done(function(response){
                $('.aux-feedback-notice').remove();
            });

        });

        $('.aux-remind-later').on('click', function(){

            $.ajax({
                url: auxin.ajaxurl,
                type: 'post',
                data: {
                    action: 'remind_feedback',
                    form  : $('.aux-popup-feedback-form').serializeObject()
                }
            });

            $('.aux-feedback-notice').remove();

        });
    })

})( jQuery );
