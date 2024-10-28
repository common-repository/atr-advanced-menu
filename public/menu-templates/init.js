if (jQuery) {
    (function (jQuery) {
        "use strict";
        jQuery(document).ready(function () {
            // initialize the megamenu
            //jQuery('.megamenu').accessibleMegaMenu();

            // hack so that the megamenu doesn't show flash of css animation after the page loads.
            setTimeout(function () {
                jQuery('body').removeClass('init');
            }, 500);
            jQuery(".sub-list-0").each(function (index) {
                var jQuerythis_panel_class = 'cols-' + jQuery("> li", this).length;
                if (jQuery(this).parent().hasClass('cols-4')){
                    //jQuery(this).parent().hasClass('cols-4').removeClass('cols-4').addClass(jQuerythis_panel_class);
                    //alert(jQuery(this).parent().hasClass('cols-4'));
                }
                
            });
        });
    }(jQuery));
}