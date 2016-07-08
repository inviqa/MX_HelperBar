define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mx.helperbar', {
        options: {
            //selectors
            closeSelector: "",
            commandSearchSelector: "",

            //command text settings
            commandArgumentSeparator: "for:",

            //command clear cache settings
            commandPrefix: "Clear Cache",
            massRefreshUrl: "",
            cacheTypes: "",
            cacheTypeLabelAll: "All"
        },

        initAutocomplete: function(commandSearch) {
            var cacheTypes = this.options.cacheTypes;
            var preCommandString = this.options.commandPrefix + " " + this.options.commandArgumentSeparator + " ";
            var searchSource = [preCommandString + this.options.cacheTypeLabelAll];
            for(var key in cacheTypes) {
                if (cacheTypes.hasOwnProperty(key)) {
                    searchSource.push(preCommandString + cacheTypes[key]);
                }
            }
            commandSearch.autocomplete({
                source: searchSource
            });
        },

        _create: function() {
            this.setKeyAliases();
            this.combinationHideKeys = {};
            this.setDefaultCombinationHideKeys();

            var commandSearch = this.element.find(this.options.commandSearchSelector);
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
            var me = e.data.scope;
            if(e.keyCode == me.ENTER) {

                $.ajax({
                    url: me.options.massRefreshUrl,
                    dataType: 'json',
                    data: {
                        types: 'config,layout,block_html,collections,reflection,db_ddl,eav,customer_notification,target_rule,full_page,config_integration,config_integration_api,translate,config_webservice',
                    }
                }).done($.proxy(function(data) {
                    if (data.error) {
                        console.log("ERROR");
                    } else {
                        console.log("OK");
                    }
                }, this));
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
            this.ENTER = 13;
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