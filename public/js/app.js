var App = {
    hasInited: false,
    pieChart: false,
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
        App.drawPieChart();
    },

    ///////////////////////////////////////////////////////////////////////////
    bind: function() {
        $(document).on('click', '.ms-elem-selectable', App.addParty);
        $(document).on('click', '.edit-votes', App.editVotes);
        $(document).on('click', '.remove-party', App.removeParty);
        $(document).on('mouseenter', 'a[data-constituency-id]', App.hoverConstituency);
        $(document).on('mouseleave', 'a[data-constituency-id]', App.unhoverConstituency);
        $(document).on('click', '[data-constituency-id]', App.mapConstituencyClick);
        $(document).keyup(App.onKeyUp);
        $(document).on('click', '.close-modal', App.dismissModal);
        $(document).on('submit', '.modal-wrapper', App.saveConstituencyData);
        $(document).on('click', '.add-independent', App.addIndependentCandidate);
        $(document).on('click', '.remove-independent', App.removeIndependentCandidate);
        $(document).on('click', 'a[href^="#"]', App.scrollToElement); // keep last in bind function
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
            ],
            change: function(color) {
                // redraw the piechart with the new color
                var index = $(this).attr('data-colors-index');
                piechart_colors[index] = color.toHexString();
                App.pieChart.destroy();
                App.drawPieChart();
            }
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

    ///////////////////////////////////////////////////////////////////////////
    drawPieChart: function() {
        if (typeof piechart_data === 'undefined') {
            return;
        }

        App.pieChart = $.jqplot('piechart', [piechart_data], {
            title: '',
            grid: {
                shadow: false,
                background: 'transparent',
                borderWidth: 0,
            },
            seriesDefaults: {
                renderer: $.jqplot.PieRenderer,
                rendererOptions: {
                    showDataLabels: true,
                    padding: 10,
                    sliceMargin: 0,
                    shadow: false
                }
            },
            series: [
                {seriesColors: piechart_colors},
            ],
            legend: {
                location: 'nw',
                renderer: $.jqplot.EnhancedLegendRenderer,
                rendererOptions: {
                    numberColumns: 1
                },
                show: true
            },
            highlighter: {
                show: true,
                useAxesFormatters: false,
                tooltipFormatString: '%s',
                tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                    var el       = jqPlot.data[seriesIndex][pointIndex],
                        label    = el[0],
                        mandates = el[2]
                        suffix   = el[2] > 1 ? 'мандата' : 'мандат';

                    return label + ': ' + mandates + ' ' + suffix;
                }
            }
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    hoverConstituency: function() {
        var id = $(this).attr('data-constituency-id');
        $('path[data-constituency-id="'+id+'"]').addClass('hover');
    },

    ///////////////////////////////////////////////////////////////////////////
    unhoverConstituency: function() {
        var id = $(this).attr('data-constituency-id');
        $('path[data-constituency-id="'+id+'"]').removeClass('hover');
    },

    ///////////////////////////////////////////////////////////////////////////
    mapConstituencyClick: function(e) {
        e.preventDefault();

        var $this            = $(this),
            id               = $this.attr('data-constituency-id'),
            title            = $this.attr('data-title'),
            $fieldsRepo      = $('#constituency-' + id),
            $modalForm       = $('.modal-wrapper'),
            $modalFormTitle  = $modalForm.find('.mmc'),
            $modalFormBody   = $modalForm.find('.parties'),
            $target          = ($this.prop('getName') === 'path')
                             ? $this
                             : $('path[data-constituency-id="'+id+'"]');
                    
        $target.addClass('active');

        if ($modalFormBody.html() === '') {
            $modalFormTitle.html(title);
            $modalFormBody.html($fieldsRepo.html());
            $modalForm.attr('data-const-id', id);
        }

        // reset the form before showing it to get rid of potential
        // previous red borders of required validation
        $modalForm.trigger('reset').fadeIn();
    },

    ///////////////////////////////////////////////////////////////////////////
    saveConstituencyData: function(e) {
        var $modalForm     = $(this),
            constId        = $modalForm.attr('data-const-id'),
            $modalFormBody = $modalForm.find('.parties'),
            $fieldsRepo    = $('#constituency-' + constId),
            $mapItem       = $('path[data-constituency-id="'+constId+'"]'),
            $listItem      = $('a[data-constituency-id="'+constId+'"]'),
            independent    = $modalForm.find('.independent-item').length;

        // update input fields' values to DOM before using html() function
        $modalFormBody.find('input').each(function() {
            $(this).attr('value', $(this).val());
        });

        // take modal body and set it in the main form
        $fieldsRepo.html($modalFormBody.html());

        // mark the constituency as completed on the map
        $mapItem.addClass('completed');

        // mark the constituency as completed on the list, too
        $listItem.addClass('completed');

        // if there are any independent candidates,
        // show an icon next to the list
        independent
            ? $listItem.addClass('independent')
            : $listItem.removeClass('independent');

        App.dismissModal();

        App.toggleFormSubmitButton();

        e.preventDefault();
    },

    ///////////////////////////////////////////////////////////////////////////
    dismissModal: function() {
        var $modalForm     = $('.modal-wrapper'),
            $modalFormBody = $modalForm.find('.parties');

        $modalForm.fadeOut();
        $modalFormBody.html('');
        $('path').removeClass('active');
    },

    ///////////////////////////////////////////////////////////////////////////
    toggleFormSubmitButton: function() {
        var $mainForm             = $('form:first'),
            missingConstituencies = $('path:not([class$=border]):not(.completed)').length,
            $submitButton         = $mainForm.find('button'),
            tooltip               = $submitButton.attr('title') || $submitButton.attr('data-title');

        if (missingConstituencies) {
            $submitButton.attr('disabled', 'disabled').attr('title', tooltip);
        }
        else {
            $submitButton.removeAttr('disabled').attr('data-title', tooltip).removeAttr('title');
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    onKeyUp: function(e) {
        if (e.key === 'Escape') {
            App.dismissModal();
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    addIndependentCandidate: function(e) {
        e.preventDefault();

        var iterator   = independent_counter;
            $modalForm = $(this).closest('.modal-wrapper'),
            constId    = $modalForm.attr('data-const-id'),
            $counter   = $modalForm.find('.local-ind-counter'),
            html       = App.getTemplate('#independent-template', {iterator: iterator, constituency_id: constId}),
            $wrapper   = $modalForm.find('.independent-list');

        $wrapper.append(html);

        $counter.html(parseInt($counter.html()) + 1);

        // increase global counter which makes sure there are no collisions
        independent_counter++;
    },

    ///////////////////////////////////////////////////////////////////////////
    removeIndependentCandidate: function(e) {
        e.preventDefault();

        var $this      = $(this),
            $row       = $this.closest('.row'),
            $modalForm = $this.closest('.modal-wrapper'),
            $counter   = $modalForm.find('.local-ind-counter');

        $row.remove();

        $counter.html(parseInt($counter.html()) - 1);
    },

    ///////////////////////////////////////////////////////////////////////////
    scrollToElement: function(e) {
        var target = $(this).attr('href');

        if (target !== '#' && $(target).length) {
            e.stopImmediatePropagation();
            $('body, html').animate({scrollTop: $(target).offset().top - 20 });
            return false;
        }
    },

}

$(document).ready(function() {
    App.init();
});