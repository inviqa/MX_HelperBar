define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mx.helperbar', {
        _create: function() {
            this.element.on('click', function(e){
                $('.helper-bar').toggle();
            });
        }

    });

    return $.mx.helperbar;
});
