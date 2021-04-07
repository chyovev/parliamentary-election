{if !$lottingParties|@count}
    <section id="step2">
        <h2>Втора стъпка: разпределение на мандати в МИР за всяка партия/коалиция</h2>
        <div class="row">Партиите, обозначени със <span class="blue">син цвят</span>, са получили допълнителен мандат от остатък.</div>

        {foreach $constituencies as $item}
            <hr />
            <h3>МИР {'%02d'|sprintf:$item['id']}. {$item['title']|escape}</h3>
            <div class="row">Действителни гласове за партиите, участвали в разпределението: {$localDistribution[$item['id']]['votes']|number}</div>
            <div class="row">Брой мандати в МИР: {$item[HareNiemeyerInterface::TOTAL_MANDATES_COLUMN]|number}</div>
            <div class="row">Квота на Хеър-Ниймайер: {$localDistribution[$item['id']]['quota']|number_format:16:'.':''}</div>

            <table class="results sortable">
                <thead>
                    <tr>
                        <th class="center sortable asc"><span>#</span></th>
                        <th class="sortable"><span>Партия/коалиция</span></th>
                        <th class="center sortable"><span>Гласове в МИР</span></th>
                        <th class="center sortable"><span>Остатък</span></th>
                        <th class="center sortable"><span>Мандати</span></th>
                    </tr>
                </thead>

                {assign var=mandates value=0}
                {assign var=votes    value=0}

                <tbody>
                {foreach $localDistribution[$item['id']]['parties'] as $party}
                    <tr>
                        <td class="center">{$party['ord']+1}</td>
                        <td>{$party['party_title']|escape}</td>
                        <td class="center" data-value="{$party['total_votes']}">{$party['total_votes']|number}</td>
                        <td class="center{if $party[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}" data-value="{$party[HareNiemeyerInterface::REMAINDER_COLUMN]}">{$party[HareNiemeyerInterface::REMAINDER_COLUMN]|number:15}</td>
                        <td class="center">{$party[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
                    </tr>
                    {$mandates = $mandates + $party[HareNiemeyerInterface::MANDATES_COLUMN]}
                    {$votes    = $votes    + $party['total_votes']}
                {/foreach}
                </tbody>

                <tfoot>
                    <tr class="bold">
                        <td class="center">&mdash;</td>
                        <td>Общо</td>
                        <td class="center">{$votes|number}</td>
                        <td class="center">&mdash;</td>
                        <td class="center">{$mandates|number}</td>
                    </tr>
                </tfoot>
            </table>

            <br />
            <div class="row bold">Подредба на партиите по (<a href="https://www.lex.bg/laws/ldoc/2136112596#i_2867" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">т.4.5.9</a>):</div>

            <table class="results sortable">
                <thead>
                    <tr>
                        <th class="center sortable"><span>#</span></th>
                        <th class="sortable"><span>Партия/коалиция</span></th>
                        <th class="sortable desc"><span>Остатък</span></th>
                    </tr>
                </thead>
                <tbody>
                {foreach $localDistribution[$item['id']]['remainders'] as $party}
                <tr>
                    <td class="center">{$party['ord']+1}</td>
                    <td>{$party['party_title']|escape}</td>
                    <td class="center{if $party[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}" data-value="{$party[HareNiemeyerInterface::REMAINDER_COLUMN]}">{$party[HareNiemeyerInterface::REMAINDER_COLUMN]|number:15}</td>
                </tr>
                {/foreach}
                </tbody>
            </table>

            {if $localDistribution[$item['id']]['lotting']}
                <strong>Следните партии са получили мандат по <a href="https://www.lex.bg/laws/ldoc/2136112596#i_2867" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">т.4.5.11</a>:</strong>
                <ul>
                {foreach $localDistribution[$item['id']]['parties'] as $party}
                    {if !isset($party['drawn_lot'])}{continue}{/if}
                    <li><strong>{$party['party_title']}</strong></li>
                {/foreach}
                </ul>
            {/if}

            <br /><br />
        {/foreach}

        <hr />
        <h3 class="center">Разпределение на мандати на национално и местно ниво между партиите</h3>

        {include file='./parties-local-global-comparison.tpl' centered=true parties=$passedParties receivingPartyId=false localSnapshot=false}

    </section>
{/if}