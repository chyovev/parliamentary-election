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
        App.drawBarChart();
        App.detectScroll();
    },

    ///////////////////////////////////////////////////////////////////////////
    bind: function() {
        $(window).scroll(App.detectScroll);
        $(document).on('click', '.ms-elem-selectable', App.addParty);
        $(document).on('click', '.edit-votes', App.editVotes);
        $(document).on('click', '.remove-party', App.removeParty);
        $(document).on('mouseenter', 'a[data-constituency-id]', App.hoverConstituency);
        $(document).on('mouseleave', 'a[data-constituency-id]', App.unhoverConstituency);
        $(document).on('click', '[data-constituency-id]', App.mapConstituencyClick);
        $(document).on('mouseenter', 'path[data-constituency-id]', App.mapTooltipShow);
        $(document).on('mouseleave', 'path[data-constituency-id]', App.mapTooltipHide);
        $(document).on('mousemove',  'path[data-constituency-id]', App.mapTooltipMove);
        $(document).keyup(App.onKeyUp);
        $(document).on('click', '.close-popup', App.dismissModal);
        $(document).on('submit', '.ajax-form', App.submitFormData);
        $(document).on('click', '.add-independent', App.addIndependentCandidate);
        $(document).on('click', '.remove-independent', App.removeIndependentCandidate);
        $(document).on('click', '.reset-form', App.resetForm);
        $(document).on('click', '.download-chart', App.downloadChart);
        $(document).on('click', 'table.sortable th.sortable', App.sortTable);
        $(document).on('click', '#scroll-top', App.scrollToTop);
        $(document).on('click', 'a[href^="#"]', App.scrollToElement); // keep last in bind function
    },

    ///////////////////////////////////////////////////////////////////////////
    detectScroll: function() {
        App.toggleScrollToTopButton();
    },

    ///////////////////////////////////////////////////////////////////////////
    toggleScrollToTopButton: function() {
        if (App.animateScrollInProgress) {
            return;
        }

        var bodyTopPosition = $('body')[0].getBoundingClientRect().top,
            viewportHeight  = $(window).height(),
            thresholdY      = -400, // arbitrary
            $scrollTopBtn   = $('#scroll-top');

        // if body is 400px scrolled down
        // show the scroll-top button
        (bodyTopPosition < thresholdY)
            ? $scrollTopBtn.fadeIn()
            : $scrollTopBtn.fadeOut();
    },

    ///////////////////////////////////////////////////////////////////////////
    addParty: function(e) {
        e.preventDefault();

        var $this             = $(this),
            id                = $this.attr('data-id'),
            party             = $this.find('.title').text(),
            abbr              = $this.find('.abbr').text() || party,
            html              = App.getTemplate('#party-template', {id: id, label: party, abbr: abbr}),
            addedPartiesCount = $('.ms-elem-selection').length,
            newPartyOrd       = addedPartiesCount + 1;

        // add new element to the right column
        $('.ms-selection .ms-list').append(html);

        // mark the element in the left column as selected
        $this.addClass('ms-selected');

        // set the ord field of the last added element
        $('input[name$="[ord]"]:last').val(newPartyOrd);
        $('.ord:last').html(newPartyOrd);

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
        App.setPartiesOrd();
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
            $counter     = $wrapper.find('.count'),
            $input       = $wrapper.find('input[name$="[total_votes]"]'),
            old_votes    = parseInt($input.val())
            votes        = prompt('Общ брой гласове в страната и чужбина за:\n' + party, old_votes),
            votes_number = isNaN(parseInt(votes)) ? 0 : parseInt(votes);

        // update the values only if the dialog window was *not* dismissed
        if (votes !== null) {
            $counter.html(App.prettyNumber(votes_number));
            $input.val(votes_number);
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    prettyNumber: function(number) {
        return parseInt(number).toLocaleString('de-DE').replace(/\./gi, ' ');
    },

    ///////////////////////////////////////////////////////////////////////////
    initSortable: function() {
        $('.ms-selection .ms-list').sortable({
            handle:      '.handle',
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            axis: 'y',
            stop: App.setPartiesOrd
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    // update ord field for all parties
    setPartiesOrd: function() {
        $('input[name$="[ord]"]').each(function(index) {
            $(this).val(index+1);
        });
        $('.ord').each(function(index) {
            $(this).html(index+1);
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    initSpectrumColorPicker: function($element) {
        if ( ! $element || $element.length === 0) {
            var $element = $('.color-picker');
        }

        $element.spectrum({
            preferredFormat:       'hex',
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
                ["#ff0000","#ff9900","#ffff00","#00ff00","#00ffff","#0000ff","#9900ff","#ff00ff"],
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
                    dataLabels: piechart_labels,
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
                    var el = jqPlot.data[seriesIndex][pointIndex];

                    return el[0];
                }
            }
        });
    },

    ///////////////////////////////////////////////////////////////////////////
    drawBarChart: function() {
        if (typeof barchart_data === 'undefined') {
            return;
        }
        
        $('#activity-chart').jqplot([barchart_data], {
            title: 'Избирателна активност',
            seriesColors: ['green', 'yellow'],
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                rendererOptions: {
                    varyBarColor: true
                }
            },
            axes:{
                xaxis:{
                    renderer: $.jqplot.CategoryAxisRenderer
                },
                yaxis:{
                    min: 0,
                    max: 100
                }
            },
            highlighter: {
                show: true,
                useAxesFormatters: false,
                tooltipFormatString: '%s',
                tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                    return jqPlot.data[seriesIndex][pointIndex][0] + ': ' + jqPlot.data[seriesIndex][pointIndex][1] + '%';
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
            popupTitle       = title.match(/[0-9]/) ? title : 'МИР ' + id + '. ' + title;
            $fieldsRepo      = $('#constituency-' + id + '-data'),
            $popupForm       = $('.popup-wrapper'),
            $popupFormTitle  = $popupForm.find('.mmc'),
            $popupFormBody   = $popupForm.find('.parties'),
            $target          = ($this.prop('getName') === 'path')
                             ? $this
                             : $('path[data-constituency-id="'+id+'"]');
                    
        $target.addClass('active');

        if ($popupFormBody.html() === '') {
            $popupFormTitle.html(popupTitle);
            $popupFormBody.html($fieldsRepo.html());
            $popupForm.attr('data-const-id', id);
        }

        // add color boxes in front of each party
        $popupFormBody.find('.party-color').remove();
        $popupFormBody.find('.parties-list').find('.row').each(function() {
            var party_id = $(this).attr('data-party-id'),
                colorBox = App.generatePartyColorBox(party_id);

            $(this).prepend(colorBox);
        });

        // reset the form before showing it to get rid of potential
        // previous red borders of required validation
        $popupForm.trigger('reset').fadeIn();
    },

    ///////////////////////////////////////////////////////////////////////////
    mapTooltipShow: function(e) {
        var $this   = $(this),
            title   = $this.attr('data-title'),
            id      = $this.attr('data-constituency-id'),
            html    = title.match(/[0-9]/) ? title : ('МИР ' + id + ' ' + title),
            $repo   = $('#constituency-' + id + '-data'),
            x       = e.pageX + 20 + 'px',
            y       = e.pageY - 10 + 'px';

        html = '<span class="title center">' + html + '</span>';

        // add parties and candidates votes to popup
        $repo.find('.row').each(function() {
            var $row   = $(this),
                votes  = $row.find('input[type="text"]:last').val() || '0';

            // if the row is a party, add a color box
            if ( ! $row.hasClass('independent-item')) {
                var party     = $row.find('input[type="text"]:first').attr('data-title'),
                    party_id  = $row.find('input[type="text"]:first').attr('data-party-id'),
                    box       = App.generatePartyColorBox(party_id);
            }
            else {
                var party = $row.find('input[type="text"]:first').val().trim(),
                    box   = '<span class="independent-box"></span>';
            }

            html += '<div class="row">' + box + '<strong>'+ party + '</strong>: ' + App.prettyNumber(votes) + '</div>';
        });

        $('.map-tooltip').html(html).css({left: x, top: y}).fadeIn(100);
    },

    ///////////////////////////////////////////////////////////////////////////
    generatePartyColorBox: function(party_id) {
        var color = App.getPartyColor(party_id);

        return '<span class="party-color" style="background-color: ' + color + '"></span>';
    },

    ///////////////////////////////////////////////////////////////////////////
    getPartyColor: function(party_id) {
        return $('input[name="parties['+party_id+'][party_color]"]').val();
    },

    ///////////////////////////////////////////////////////////////////////////
    mapTooltipHide: function() {
        $('.map-tooltip').stop().hide();
    },

    ///////////////////////////////////////////////////////////////////////////
    mapTooltipMove: function(e) {
        e.stopPropagation();

        var x = e.pageX + 5 + 'px',
            y = e.pageY + 'px';
        $('.map-tooltip').css({left: x, top: y});
    },

    ///////////////////////////////////////////////////////////////////////////
    submitFormData: function(e) {
        e.preventDefault();

        var $form       = $(this),
            type        = $form.attr('method'),
            data        = $form.serialize(),
            url         = $form.attr('action'),
            successFunction = $form.attr('data-success-action');

        $('.invalid-field').removeClass('invalid-field');

        // when submitting constituency map, add its id at the end of the URL
        if ($form.attr('data-const-id')) {
            url += '/' + $form.attr('data-const-id');
        }
        
        // wait for previous error messages to be hidden,
        // and then proceed with the new AJAX request
        $.when( $('.error-message').slideUp('fast') ).done(function() {
            return $.ajax({
                url:      url,
                type:     type,
                data:     data,
                dataType: 'JSON',

                success: function(response) {
                    // if the status was true, redirect to next page
                    if (response.status) {
                        eval(successFunction);
                    }

                    // otherwise show errors
                    else {
                        $.each(response.errors, function(index, pair) {
                            var field   = pair[0],
                                message = pair[1];

                            $('.' + field).addClass('invalid-field');
                            $('.' + field + '_message').html(message).slideDown();
                        });
                    }
                },

                // if the request fails, notify the user
                error: function () {
                    alert('Възникна грешка. Моля, опитайте по-късно');
                }
            })
        });

    },

    ///////////////////////////////////////////////////////////////////////////
    goToPage: function(url) {
        window.location = url;
    },

    ///////////////////////////////////////////////////////////////////////////
    closePopupForm: function() {
        var $popupForm     = $('.popup-wrapper'),
            constId        = $popupForm.attr('data-const-id'),
            $popupFormBody = $popupForm.find('.parties'),
            $fieldsRepo    = $('#constituency-' + constId + '-data'),
            $mapItem       = $('path[data-constituency-id="'+constId+'"]'),
            $listItem      = $('a[data-constituency-id="'+constId+'"]'),
            independent    = $popupForm.find('.independent-item').length;

        // update input fields' values to DOM before using html() function
        $popupFormBody.find('input').each(function() {
            $(this).attr('value', ($(this).val() || '0'));
        });

        // take popup body and set it in the main form
        $fieldsRepo.html($popupFormBody.html());

        // mark the constituency as completed only if there are any votes
        $mapItem.addClass('completed');
        $listItem.addClass('completed');

        // if there are any independent candidates,
        // show an icon next to the list
        independent
            ? $listItem.addClass('independent')
            : $listItem.removeClass('independent');

        App.dismissModal();
    },

    ///////////////////////////////////////////////////////////////////////////
    dismissModal: function() {
        var $popupForm     = $('.popup-wrapper'),
            $popupFormBody = $popupForm.find('.parties');

        $popupForm.fadeOut();
        $popupFormBody.html('');
        $('path').removeClass('active');
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
            $popupForm = $(this).closest('.popup-wrapper'),
            constId    = $popupForm.attr('data-const-id'),
            $counter   = $popupForm.find('.local-ind-counter'),
            html       = App.getTemplate('#independent-template', {iterator: iterator, constituency_id: constId}),
            $wrapper   = $popupForm.find('.independent-list');

        $wrapper.append(html);

        $wrapper.find('.row:hidden').slideDown('normal');

        $counter.html(parseInt($counter.html()) + 1);

        // increase global counter which makes sure there are no collisions
        independent_counter++;
    },

    ///////////////////////////////////////////////////////////////////////////
    removeIndependentCandidate: function(e) {
        e.preventDefault();

        var $this      = $(this),
            $row       = $this.closest('.row'),
            $popupForm = $this.closest('.popup-wrapper'),
            $counter   = $popupForm.find('.local-ind-counter');

        // reduce opacity, slide up and then remove from DOM
        $row.animate({opacity: 0}, 300, function() {
            $row.slideUp('normal', function() {
                $row.remove();
            });
        });

        $counter.html(parseInt($counter.html()) - 1);
    },

    ///////////////////////////////////////////////////////////////////////////
    resetForm: function(e) {
        e.preventDefault();

        var $this       = $(this),
            url         = $this.attr('data-url'),
            currentStep = $this.attr('data-step'),
            constId     = $this.attr('data-constituency')
            data        = constId ? {constituency: constId} : {step: currentStep};

        if (constId) {
            var $popupForm    = $('.popup-wrapper'),
                $repoForm     = $('#constituency-'+constId+'-data'),
                $mapIndicator = $('[data-constituency-id="'+constId+'"]');

            $popupForm.find('input[type="text"][name*="votes"]').val(0);
            $popupForm.find('.independent-list').html('');
            $popupForm.find('.local-ind-counter').html('0');

            $repoForm.find('input[name*="votes"]').val(0).attr('value', 0);
            $repoForm.find('.independent-list').html('');
            $repoForm.find('.local-ind-counter').html('0');

            $mapIndicator.removeClass('completed').removeClass('independent');
        }
        else if (currentStep == 2) {
            $('.completed').removeClass('completed');
            $('.independent').removeClass('independent');
            $('input[name^="parties_votes"]').val(0).attr('value', 0);
            $('.independent-list').html('');
            $('.local-ind-counter').html('0');
            $('.next-page').fadeOut('normal', function() { $(this).remove(); });
        }
        else {
            $('input[type="text"]:not(.search-input)').val(0);
            $('.ms-list.parties').html('');
            $('.ms-elem-selectable').removeClass('ms-selected');
            $('#parties-count').html('0');
            $('.threshold_percentage').val(4);
            $('.assembly_type_id').val($('.assembly_type_id option:first').val());
            $('.population_census_id').val($('.population_census_id option:first').val());
            $('.next-page').fadeOut('normal', function() { $(this).remove(); });

            App.updateQuickSearchCache();
        }

        // response is not relevant
        return $.ajax({
            url:  url,
            data: data,
            type: 'GET'
        });

    },

    ///////////////////////////////////////////////////////////////////////////
    downloadChart: function(e) {
        var chartSrc = $('#piechart').jqplotToImageStr({backgroundColor: '#F2F3F4'});

        $(this).attr('href', chartSrc);
    },

    ///////////////////////////////////////////////////////////////////////////
    sortTable: function(e) {
        e.preventDefault();

        var $th     = $(this),
            thIndex = $th.index(),
            $table  = $th.closest('table'),
            $tbody  = $table.find('tbody').eq(0),
            rows    = $tbody.find('tr').toArray().sort(App.comparer(thIndex));

        // if the current cell heading doesn't have desc class,
        // add it, remove asc class and reverse all rows
        if ( ! $th.hasClass('desc')) {
            rows = rows.reverse();
            $th.removeClass('asc').addClass('desc');
        }
        else {
            $th.removeClass('desc').addClass('asc');
        }

        // remove asc and desc class from all other cell headings
        $th.siblings().removeClass('asc desc');

        for (var i = 0; i < rows.length; i++){
            $tbody.append(rows[i])
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    comparer: function(index) {
        return function(a, b) {
            var valA = App.getCellValue(a, index),
                valB = App.getCellValue(b, index);

            // if both cells' values are numbers,
            // use their difference for the sorting
            if ($.isNumeric(valA) && $.isNumeric(valB)) {
                return valA - valB;
            }

            // otherwise compare them as strings
            return valA.toString().localeCompare(valB);
        }
    },

    ///////////////////////////////////////////////////////////////////////////
    getCellValue: function(row, index) {
        var $cell = $(row).children('td').eq(index);

        return $cell.attr('data-value') || $cell.text()
    },

    ///////////////////////////////////////////////////////////////////////////
    scrollToTop: function() {
        // set flag to true to avoid animate
        // from firing the toggleScrollToTopButton function
        App.animateScrollInProgress = true;

        // once the animation is finished, reset the flag and retire the arrow
        $('html, body').animate({ scrollTop: 0}, 500, function() {
            App.animateScrollInProgress = false;
            $('#scroll-top').fadeOut();
        });
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