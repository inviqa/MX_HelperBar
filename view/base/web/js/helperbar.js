define([
    "jquery",
    'Magento_Ui/js/modal/alert',
    "jquery/ui"
], function($, alert) {
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

        _create: function() {
            _this = this;
            //setup binds and initAutocomplete
            this.setupBinds();
            this.initAutocomplete($(this.options.commandSearchSelector));
        },
        /**
         * Setup Binds for clicks and keypress
         */
        setupBinds: function() {
            $(this.options.closeSelector).on('click', this.toggleHelpBar);
            $(document).on("keypress", this.onDocumentKeyCallback);
        },
        /**
         * Initizalize autocomplete jQuery UI Widget
         * @param commandSearch
         */
        initAutocomplete: function(commandSearch) {
            var searchSource = [],
                $bodyHtml = $('body, html');

            for (var prefix in this.options.commands) {
                if(!this.options.commands.hasOwnProperty(prefix)) continue;
                var beforeArguments = prefix + " " + this.options.commandArgumentSeparator + " ";
                var command = this.options.commands[prefix];
                for (var key in command.options) {
                    if (!command.options.hasOwnProperty(key)) continue;
                    searchSource.push(beforeArguments + command.options[key]);
                }
            }

            //auto complete initialize function
            commandSearch.autocomplete({
                source: searchSource,
                position: { my: "left bottom", at: "left top", collision: "flip" },
                open: function() {
                    $bodyHtml.addClass('overflow-y-hidden');
                },
                close: function() {
                    $bodyHtml.removeClass('overflow-y-hidden');
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
                             alert({
                                 title: 'Success',
                                 content: data.message
                             });
                         }
                     }, this));
                },
                messages: {
                    noResults: '',
                    results: function() {}
                }
            });
        },
        /**
         * Handle keypress callback combinations
         * @param e
         * @returns {boolean}
         */
        onDocumentKeyCallback: function(e) {
            e = e || event;
            if(e.ctrlKey && ( e.which === 96 )) {
                _this.toggleHelpBar();
            }
        },
        /**
         * Toggle toolbar
         */
        toggleHelpBar: function() {
            _this.element.toggle();
        },
        /**
         * Return Ajax controller for selected option from list
         * @param selectedOption
         * @returns {*}
         */
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
