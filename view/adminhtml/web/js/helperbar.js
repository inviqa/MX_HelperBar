define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mx.helperbar', {
        options: {
            closeSelector: "",
            commandSearchSelector: ""
        },

        initAutocomplete: function(commandSearch) {
            commandSearch.autocomplete({
                source: [
                    "Cache Refresh for: All Page Types",
                    "Cache Refresh for: Configuration",
                    "Cache Refresh for: Layouts",
                    "Cache Refresh for: Blocks HTML output",
                    "Cache Refresh for: Collections Data",
                    "Cache Refresh for: Reflection Data",
                    "Cache Refresh for: Database DDL operations",
                    "Cache Refresh for: EAV types and attributes",
                    "Cache Refresh for: Customer Notification",
                    "Cache Refresh for: Target Rule",
                    "Cache Refresh for: Page Cache",
                    "Cache Refresh for: Integrations Configuration",
                    "Cache Refresh for: Integrations API Configuration",
                    "Cache Refresh for: Translations",
                    "Cache Refresh for: Web Services Configuration"]
            });
        },

        _create: function() {
            this.setKeyAliases();
            this.combinationHideKeys = {};
            this.setDefaultCombinationHideKeys();

            var commandSearch = $(this.options.commandSearchSelector);
            var closeButton = this.element.find(this.options.closeSelector);

            this.initAutocomplete(commandSearch);

            commandSearch.on(
                "keydown",
                {scope: this},
                this.onCommandSearchKeyDownCallback);

            closeButton.on(
                'click',
                {scope: this},
                this.onCloseIconClick);

            $(document).on(
                "keydown",
                {scope: this},
                this.onDocumentKeyDownCallback);

            $(document).on(
                "keyup",
                {scope: this},
                this.onDocumentKeyUpCallback);
        },

        onCloseIconClick: function(e) {
            e.data.scope.toggleHelpBar();
        },

        onCommandSearchKeyDownCallback: function(e) {
            if (e.keyCode == e.data.scope.options.ENTER) {
                console.log("send ajax request");
            }
        },

        onDocumentKeyDownCallback: function(e) {
            e.data.scope.recordKeyPressed(e, e.data.scope);
        },

        onDocumentKeyUpCallback: function(e) {
            e.data.scope.recordKeyPressed(e, e.data.scope);
        },

        setKeyAliases: function() {
            this.CTRL = 17;
            this.BACK_TICK = 192;
            this.ENTER = 11;
        },

        setDefaultCombinationHideKeys: function() {
            this.combinationHideKeys[this.CTRL] = false;
            this.combinationHideKeys[this.BACK_TICK] = false;
        },

        recordKeyPressed: function(e, scope) {
            e = e || event;
            if(scope.combinationHideKeys[e.keyCode] === undefined) return true;
            scope.combinationHideKeys[e.keyCode] = e.type == 'keydown';

            if(scope.combinationHideKeys[scope.CTRL] && scope.combinationHideKeys[scope.BACK_TICK]) {
                scope.toggleHelpBar();
                scope.setDefaultCombinationHideKeys();
            }
            return false;
        },

        toggleHelpBar: function() {
            this.element.toggle();
        }
    });

    return $.mx.helperbar;
});
