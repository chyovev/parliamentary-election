var App = {
    hasInited: false,
    colors: ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4',
             '#f032e6', '#bfef45', '#fabed4', '#469990', '#dcbeff', '#9A6324', '#fffac8',
             '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#a9a9a9'],
    qs1: false,
    qs2: false,

    ///////////////////////////////////////////////////////////////////////////
    init: function() {
        if (App.hasInited) {
            return;
        }
        App.bind();

        App.initSortable();
        App.initSpectrumColorPicker();
        App.initQuickSearch();
    },

    ///////////////////////////////////////////////////////////////////////////
    bind: function() {
        $(document).on('click', '.ms-elem-selectable', App.addParty);
        $(document).on('click', '.edit-votes', App.editVotes);
        $(document).on('click', '.remove-party', App.removeParty);
    },

    ///////////////////////////////////////////////////////////////////////////
    addParty: function(e) {
        e.preventDefault();

        var $this             = $(this),
            id                = $this.attr('data-id'),
            party             = $this.find('.title').text(),
            abbr              = $this.find('.abbr').text() || party,
            html              = App.getTemplate('#party-template', {id: id, label: party, abbr: abbr}),
            addedPartiesCount = $('.ms-elem-selection').length;

        // add new element to the right column
        $('.ms-selection .ms-list').append(html);

        // mark the element in the left column as selected
        $this.addClass('ms-selected');

        // set the ord field of the last added element
        $('input[name$="[ord]"]:last').val(addedPartiesCount);

        App.updateQuickSearchCache();
        App.updatePartiesCount();
    },

    ///////////////////////////////////////////////////////////////////////////
    // render a JS template by passing variables to replace
    getTemplate: function(templateId, data) {
        var template = $(templateId).html();

        return template.replace(/%(\w*)%/g,
            function(m, key) {
                return data.hasOwnProperty(key) ? data[key] : "";
            }
        );
    },

    ///////////////////////////////////////////////////////////////////////////
    removeParty: function(e) {
        e.preventDefault();

        var $this     = $(this),
            $wrapper  = $this.closest('.ms-elem-selection'),
            id        = $(this).next().val(),
            $selected = $('.ms-elem-selectable[data-id="'+id+'"]');

        // completely remove element from right column
        // and un-select its corresponding element in left column
        $wrapper.remove();
        $selected.removeClass('ms-selected');

        App.updateQuickSearchCache();
        App.updatePartiesCount();
    },

    ///////////////////////////////////////////////////////////////////////////
    updatePartiesCount: function() {
        var count = $('.ms-elem-selection').length;

        $('#parties-count').html(count);
    },

    ///////////////////////////////////////////////////////////////////////////
    editVotes: function(e) {
        e.preventDefault();

        var $this        = $(this),
            $wrapper     = $this.closest('.ms-elem-selection'),
            party        = $wrapper.find('.title').text().trim(),
            votes        = prompt('Общ брой гласове в страната и чужбина за:\n' + party),
            votes_number = isNaN(parseInt(votes)) ? 0 : parseInt(votes);

        // update the values only if the dialog window was *not* dismissed
        if (votes !== null) {
            $wrapper.find('.count').html(votes_number);
            $wrapper.find('input[name$="[total_votes]"]').val(votes_number);
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    initSortable: function() {
        $('.ms-selection .ms-list').sortable({
            handle:      '.handle',
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            axis: 'y',
            stop: App.onSortableStop
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    // update ord field for all parties
    onSortableStop: function() {
        $('input[name$="[ord]"]').each(function(index) {
            $(this).val(index);
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    initSpectrumColorPicker: function($element) {
        if ( ! $element || $element.length === 0) {
            var $element = $('.color-picker');
        }

        $element.spectrum({
            showAlpha:             false,
            allowEmpty:            false,
            showButtons:           false,
            showPaletteOnly:       true,
            togglePaletteOnly:     true,
            togglePaletteMoreText: 'Повече цветове',
            togglePaletteLessText: 'По-малко цветове',
            hideAfterPaletteSelect: true,
            type:        'color',
            palette: [
                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
            ]
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    initQuickSearch: function() {
        var 
        $selectableSearch      = $('.ms-selectable .search-input'),
        $selectionSearch       = $('.ms-selection .search-input'),
        selectableSearchString = '.ms-elem-selectable:not(.ms-selected)',
        selectionSearchString  = '.ms-elem-selection';

        App.qs1 = $selectableSearch.quicksearch(selectableSearchString);
        App.qs2 = $selectionSearch.quicksearch(selectionSearchString);
    },

    ///////////////////////////////////////////////////////////////////////////
    // toggling selected classes in both columns messes up quicksearch's cache
    // which in turn needs to be refreshed manually
    updateQuickSearchCache: function() {
        App.qs1.cache();
        App.qs2.cache();
    },

}

$(document).ready(function() {
    App.init();
});