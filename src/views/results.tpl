<form class="ajax-form" method="post" action="{url controller='validation' action='constituencies'}" data-success-action="App.goToPage('{url controller='results' action='definitive'}')">
    <script type="text/javascript">var independent_counter = {$election['independent_candidates_count']|default:0};</script>
    
    {include file='elements/election-summary.tpl'}

    {if isset($passedParties)}
    <section>
        <h2>Предварителни резултати</h2>

        {if $passedParties|@count === 0}
            <div class="row">Нито една партия не е преминала долната граница за представителство.</div>
        {else}
            <div class="row">
                <span>Общ брой на партиите и коалициите (без независими кандидати): <strong>{$election['election_parties_count']}</strong></span>
            </div>

            <div class="row">
                <span>Общ брой на партиите и коалициите, преминали долната граница: <strong>{$passedParties|@count}</strong></span>
            </div>

            <table class="results fullwidth">
                <tr class="heading">
                    <th class="center">#</th>
                    <th>Цвят</th>
                    <th class="left">Партия/коалиция</th>
                    <th>Гласове</th>
                    <th>Процент</th>
                    <th>Мандати</th>
                </tr>
                
                {assign var=mandates value=0}
                {assign var=percentages value=0}
                {assign var=votes value=0}

                {foreach $passedParties as $item}
                    {$mandates    = $mandates + $item[HareNiemeyerInterface::MANDATES_COLUMN]}
                    {$percentages = $percentages + $item['votes_percentage']}
                    {$votes       = $votes + $item['total_votes']}
                    <tr>
                        <td class="center">{$item['ord']+1}</td>
                        <td class="center"><input type="hidden" class="color-picker" name="parties[{$item['party_id']}][party_color]" value="{$item['party_color']}" data-colors-index="{$item@index}" /></td>
                        <td>{$item['party_title']|escape}</td>
                        <td class="center">{$item['total_votes']|number}</td>
                        <td class="center">{$item['votes_percentage']|percentage}%</td>
                        <td class="center">{$item[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
                    </tr>
                {/foreach}

                <tr class="bold">
                    <td class="center">&mdash;</td>
                    <td class="center">&mdash;</td>
                    <td>Общо</td>
                    <td class="center">{$votes|number}</td>
                    <td class="center">{min($percentages, 100)|percentage}%</td>
                    {if $mandates > 0 && $mandates < $assembly['total_mandates']}
                        <td class="center red"><em><abbr title="Възможен е жребий на ЦИК за преразпределяне на липсващите мандати">{$mandates}</abbr></em></td>
                    {else}
                        <td class="center">{$mandates}</td>
                    {/if}
                </tr>
            </table>

            <div class="chart-wrapper">
                <div id="piechart" class="condensed"></div>

                <script type="text/javascript">
                var piechart_data   = [],
                    piechart_colors = [];
                    
                {foreach $passedParties as $item}
                    piechart_data.push(['{$item['party_title']|escape}', {$item["votes_percentage"]|number_format:2}, '{$item['party_abbreviation']|default:$item['party_title']|escape}', {$item[HareNiemeyerInterface::MANDATES_COLUMN]}]);
                    piechart_colors.push('{$item['party_color']}');
                {/foreach}
                </script>

                {if $passedParties|@count > 1}
                    <p><strong title="Nota bene">NB!</strong> За крайните резултати трябва във всеки МИР <a href="#map">да въведете</a> получените гласове за всяка от партиите, преминали границата за представителство, както и за независимите кандидати.</p>
                {/if}
            </div>
        {/if}

    </section>
    {/if}

    {if isset($passedParties) && $passedParties|@count > 1}
        {include file='elements/map-constituencies-bulgaria.tpl'}
    {/if}

</form>

<script type="text/template" id="independent-template">
    {include file='elements/independent-candidate-template.tpl'}
</script>

<form class="popup-wrapper ajax-form" method="post" action="{url controller='validation' action='constituencies'}" data-success-action="App.closePopupForm()">
    <div id="popup">
        <img src="{$_root}img/close.svg" title="Затвори" class="close-popup" />
        <h2 class="center">Попълване на мандати за <span class="mmc"></span></h2>
        <section class="parties"></section>
    </div>
</form>
