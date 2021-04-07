<section id="step1">
    <h2>Първа стъпка: разпределение на мандати на национално ниво</h2>

    <div class="row">Квота на Хеър-Ниймайер: <strong>{$globalHareNiemeyerQuota|number:3}</strong></div>
    <div class="row">Партиите, обозначени със <span class="blue">син цвят</span>, са получили допълнителен мандат от остатък.</div>
    <table class="results fullwidth sortable">
        <thead>
            <tr>
                <th class="center sortable asc"><span>#</span></th>
                <th class="sortable"><span>Партия/коалиция</span></th>
                <th class="center sortable"><span>Действителни гласове</span></th>
                <th class="center sortable"><span>Остатък</span></th>
                <th class="center sortable"><span>Мандати</span></th>
            </tr>
        </thead>

        {assign var=partyMandates       value=0}
        {assign var=independentMandates value=0}
        {assign var=totalVotes          value=0}
        {assign var=lastPartyOrd        value=0}

        <tbody>
        {foreach $passedParties as $item}
            <tr>
                <td class="center">{$item['ord']+1}</td>
                <td>{$item['party_title']|escape}</td>
                <td class="center" data-value="{$item['total_votes']}">{$item['total_votes']|number}</td>
                <td class="center{if $item[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}" data-value="{$item[HareNiemeyerInterface::REMAINDER_COLUMN]}">{$item[HareNiemeyerInterface::REMAINDER_COLUMN]|number:15}</td>
                <td class="center">{$item[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
            </tr>
            {$totalVotes    = $totalVotes    + $item['total_votes']}
            {$partyMandates = $partyMandates + $item[HareNiemeyerInterface::MANDATES_COLUMN]}
            {$lastPartyOrd  = $item['ord']}
        {/foreach}

        {foreach $candidates as $item}
            {if !$item['is_elected']}
                {continue}
            {/if}

            <tr>
                <td class="center blue" data-value="{$lastPartyOrd + $item@iteration}"><abbr title="Независим кандидат">Н</abbr></td>
                <td>{$item['name']|escape} (<em>{$constituencies[$item['constituency_id']]['id']}. {$constituencies[$item['constituency_id']]['title']|escape}</em>)</td>
                <td class="center" data-value="{$item['votes']}">{$item['votes']|number}</td>
                <td class="center" data-value="0">&mdash;</td>
                <td class="center">1</td>
            </tr>

            {$independentMandates = $independentMandates + 1}
            {$totalVotes          = $totalVotes + $item['votes']}
        {/foreach}
        </tbody>

        {assign var=totalMandates value=$partyMandates+$independentMandates}

        <tfoot>
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
        </tfoot>
    </table>

    <div class="chart-wrapper relative">
        {include file='elements/piechart.tpl' includeCandidates=true}
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