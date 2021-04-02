<section id="step1">
    <h2>Първа стъпка: разпределение на мандати на национално ниво</h2>

    <div class="row">Квота на Хеър-Ниймайер: <strong>{$globalHareNiemeyerQuota|number:3}</strong></div>
    <div class="row">Партиите, обозначени със <span class="blue">син цвят</span>, са получили допълнителен мандат от остатък.</div>
    <table class="results fullwidth">
        <tr>
            <th class="center">#</th>
            <th>Партия/коалиция</th>
            <th class="center">Действителни гласове</th>
            <th class="center">Остатък</th>
            <th class="center">Мандати</th>
        </tr>

        {assign var=partyMandates       value=0}
        {assign var=independentMandates value=0}
        {assign var=totalVotes          value=0}

        {foreach $passedParties as $item}
            <tr>
                <td class="center">{$item['ord']+1}</td>
                <td>{$item['party_title']|escape}</td>
                <td class="center">{$item['total_votes']|number}</td>
                <td class="center{if $item[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}">{$item[HareNiemeyerInterface::REMAINDER_COLUMN]|number:15}</td>
                <td class="center">{$item[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
            </tr>
            {$totalVotes    = $totalVotes    + $item['total_votes']}
            {$partyMandates = $partyMandates + $item[HareNiemeyerInterface::MANDATES_COLUMN]}
        {/foreach}

        {foreach $candidates as $item}
            {if !$item['is_elected']}
                {continue}
            {/if}

            <tr>
                <td class="center blue"><abbr title="Независим кандидат">Н</abbr></td>
                <td>{$item['name']|escape} (<em>{$constituencies[$item['constituency_id']]['id']}. {$constituencies[$item['constituency_id']]['title']|escape}</em>)</td>
                <td class="center">{$item['votes']|number}</td>
                <td class="center">&mdash;</td>
                <td class="center">1</td>
            </tr>

            {$independentMandates = $independentMandates + 1}
            {$totalVotes          = $totalVotes + $item['votes']}
        {/foreach}

        {assign var=totalMandates value=$partyMandates+$independentMandates}

        <tr class="bold">
            <td class="center">&mdash;</td>
            <td>Общо</td>
            <td class="center">{$totalVotes|number}</td>
            <td class="center">&mdash;</td>
            {if $totalMandates > 0 && $totalMandates < $assembly['total_mandates']}
                <td class="center red"><em><abbr title="Необходим е жребий на ЦИК за преразпределяне на липсващите мандати">{$totalMandates|number}</abbr></em></td>
            {else}
                <td class="center">{$totalMandates|number}</td>
            {/if}
        </tr>
    </table>

    <div class="chart-wrapper">
        <div id="piechart"></div>

        <script type="text/javascript">
        var piechart_data   = [],
            piechart_colors = [];
            
        {foreach $passedParties as $item}
            piechart_data.push(['{$item['party_title']|escape}', {$item["votes_percentage"]|number_format:2}, '{$item['party_abbreviation']|default:$item['party_title']|escape}', {$item[HareNiemeyerInterface::MANDATES_COLUMN]}]);
            piechart_colors.push('{$item['party_color']}');
        {/foreach}
        
        {foreach $candidates as $item}
            {if !$item['is_elected']}{continue}{/if}
            piechart_data.push(['{$item['name']|escape}', {1|percentage}, '{$item['name']|escape}', {1}]);
        {/foreach}
        </script>
    </div>

</section>

{if $lottingParties|@count}
    {assign var=remaining value=$assembly['total_mandates']-$totalMandates}
    <section>
        {if $remaining == 1}
            <div class="row">За разпределянето на последния {$remaining} мандат ЦИК трябва да тегли жребий, тъй като следните партии/коалиции имат еднакъв остатък.</div>
        {else}
            <div class="row">За разпределянето на последните {$remaining} мандата ЦИК трябва да тегли жребий, тъй като следните партии/коалиции имат еднакъв остатък.</div>
        {/if}

        <ol>
        {foreach $lottingParties as $item}
            <li><strong>{$item['party_title']|escape}</strong>: <span>{$item[HareNiemeyerInterface::REMAINDER_COLUMN]|number:15}</span></li>
        {/foreach}
        </ol>
    </section>
{/if}