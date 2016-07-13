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

            commands: []
        },

        initAutocomplete: function(commandSearch) {
            var searchSource = [];

            for (var prefix in this.options.commands) {
                if(!this.options.commands.hasOwnProperty(prefix)) continue;
                var beforeArguments = prefix + " " + this.options.commandArgumentSeparator + " ";
                var command = this.options.commands[prefix];
                for (var key in command.options) {
                    if (!command.options.hasOwnProperty(key)) continue;
                    searchSource.push(beforeArguments + command.options[key]);
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

                var selectedOption = e.srcElement.value;
                var ajaxController = me.getAjaxController(selectedOption);

                if (ajaxController === false) return false;

                $.ajax({
                    dataType: 'json',
                    url: ajaxController.url,
                    data: ajaxController.data
                }).done($.proxy(function(data) {
                    if (data.error || data.success == false) {
                        console.log("Something wrong happened");
                        console.log(data.message);
                    } else {
                        alert(data.message);
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
        },

        getAjaxController: function(selectedOption) {
            var splitCommandArgument = selectedOption.split(this.options.commandArgumentSeparator).map(function(ele){return ele.trim()});
            var command = splitCommandArgument[0];
            var argument = splitCommandArgument[1];

            if (command === undefined || argument === undefined) return false;

            var url = this.options.commands[command].url;

            return {
                url: url,
                data:
                {
                    labels: argument,
                    massaction_prepare_key: 'labels'
                }
            };
        }
    });

    return $.mx.helperbar;
});