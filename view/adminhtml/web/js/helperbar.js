define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";

    $.widget('mx.helperbar', {

        options: {
            keys: {
                17: false, // CTRL
                192: false // `
            }
        },

        _create: function () {
            this.keys = this.options.keys;

            this.element.on('click', function(e) {
                this.toggleHelpBar();
            });

            var me = this;
            $(document).on("keydown keyup", function(e){
                me.recordKeyPressed(e);
            });
        },

        recordKeyPressed: function(e) {
            e = e || event;
            if (this.keys[e.keyCode] === undefined) return true;
            this.keys[e.keyCode] = e.type == 'keydown';

            if(this.keys[17] && this.keys[192]) {
                this.toggleHelpBar();
                this.keys = this.options.keys;
            }
            return false;
        },


        toggleHelpBar: function() {
            $('.helper-bar').toggle();
        }
    });

    return $.mx.helperbar;
});
