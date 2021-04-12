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

            <table class="results fullwidth sortable">
                <thead>
                    <tr class="heading">
                        <th class="center sortable asc"><span>#</span></th>
                        <th>Цвят</th>
                        <th class="left sortable"><span>Партия/коалиция</span></th>
                        <th class="sortable"><span>Гласове</span></th>
                        <th class="sortable"><span>Процент</span></th>
                        <th class="sortable"><span>Мандати</span></th>
                    </tr>
                </thead>
                
                {assign var=mandates value=0}
                {assign var=percentages value=0}
                {assign var=votes value=0}

                <tbody>
                    {foreach $passedParties as $item}
                        {$mandates    = $mandates + $item[HareNiemeyerInterface::MANDATES_COLUMN]}
                        {$percentages = $percentages + $item['votes_percentage']}
                        {$votes       = $votes + $item['total_votes']}
                        <tr>
                            <td class="center">{$item['ord']}</td>
                            <td class="center"><input type="hidden" class="color-picker" name="parties[{$item['party_id']}][party_color]" value="{$item['party_color']}" data-colors-index="{$item@index}" /></td>
                            <td>{$item['party_title']|escape}</td>
                            <td class="center" data-value="{$item['total_votes']}">{$item['total_votes']|number}</td>
                            <td class="center" data-value="{$item['votes_percentage']}">{$item['votes_percentage']|percentage}%</td>
                            <td class="center">{$item[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
                        </tr>
                    {/foreach}
                </tbody>

                <tfoot>
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
                </tfoot>
            </table>

            <div class="chart-wrapper relative">
                {include file='elements/piechart.tpl'}
            </div>
            
            {if $passedParties|@count > 1}
                <p><strong title="Nota bene">NB!</strong> За крайните резултати трябва във всеки МИР <a href="#map">да въведете</a> получените гласове за всяка от партиите, преминали границата за представителство, както и за независимите кандидати.</p>
            {/if}
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
        <h2 class="center">Гласове за <span class="mmc"></span></h2>
        <section class="parties"></section>
    </div>
</form>
