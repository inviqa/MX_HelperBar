define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    var _this;

    $.widget('mx.helperbar', {
        options: {
            //selectors
            closeSelector: "",
            commandSearchSelector: "",

            //command text settings
            commandArgumentSeparator: "for:",

            commands: []
        },
        CTRL: 17,
        BACK_TICK: 192,
        ENTER: 13,
        combinationHideKeys: {},

        _create: function() {
            this.setDefaultCombinationHideKeys();
            _this = this;

            var commandSearch = $(this.options.commandSearchSelector),
                closeButton = $(this.options.closeSelector);

            this.initAutocomplete(commandSearch);

            closeButton.on('click', this.onCloseIconClick);
            $(document).on("keydown", this.onDocumentKeyDownCallback);
            $(document).on("keyup", this.onDocumentKeyUpCallback);
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
                source: searchSource,
                //appendTo: '#' + _this.element.attr('id'),
                position: { my: "left bottom", at: "left top", collision: "flip" },
                open: function() {
                    $('body, html').addClass('overflow-y-hidden');
                },
                close: function() {
                    $('body, html').removeClass('overflow-y-hidden');
                },
                select: function(e, ui) {
                     var selectedOption = ui.item.value,
                     ajaxController = _this.getAjaxController(selectedOption);

                     if (ajaxController === false) return false;

                     $.ajax({
                         dataType: 'json',
                         url: ajaxController.url,
                         data: ajaxController.data
                         }).done($.proxy(function(data) {
                             if (data.error || data.success === false) {
                             console.log("Something wrong happened");
                             console.log(data.message);
                         } else {
                            alert(data.message);
                         }
                     }, this));
                }
            });
        },

        onCloseIconClick: function() {
            _this.toggleHelpBar();
        },

        onDocumentKeyDownCallback: function(e) {
            _this.recordKeyPressed(e);
        },

        onDocumentKeyUpCallback: function(e) {
            _this.recordKeyPressed(e);
        },

        setDefaultCombinationHideKeys: function() {
            this.combinationHideKeys[this.CTRL] = false;
            this.combinationHideKeys[this.BACK_TICK] = false;
        },

        recordKeyPressed: function(e) {
            e = e || event;
            if(_this.combinationHideKeys[e.keyCode] === undefined) return true;
            _this.combinationHideKeys[e.keyCode] = e.type == 'keydown';

            if(_this.combinationHideKeys[_this.CTRL] && _this.combinationHideKeys[_this.BACK_TICK]) {
                _this.toggleHelpBar();
                _this.setDefaultCombinationHideKeys();
            }
            return false;
        },

        toggleHelpBar: function() {
            _this.element.toggle();
        },

        getAjaxController: function(selectedOption) {
            var splitCommandArgument = selectedOption.split(this.options.commandArgumentSeparator).map(function(ele){return ele.trim()}),
                command = splitCommandArgument[0],
                argument = splitCommandArgument[1];

            if (command === undefined || argument === undefined) return false;

            var url = this.options.commands[command].url;

            return {
                url: url,
                data: {
                    labels: argument,
                    massaction_prepare_key: 'labels'
                }
            };
        }
    });

    return $.mx.helperbar;
});