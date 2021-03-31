{include file='elements/election-summary.tpl'}

{if isset($candidates)}
<section>
    <h2>Независими кандидати</h2>
     <table class="results fullwidth">
        <tr class="heading">
            <th class="center">#</th>
            <th class="left">Име</th>
            <th>Гласове</th>
            <th>МИР</th>
            <th>Действителни гласове в МИР</th>
            <th>Мандати</th>
            <th><abbr title="Необходими гласове за избиране на независим кандидат">Квота</abbr></th>
            <th>Статус</th>
        </tr>
        {assign var=electedIndependent value=0}
        {foreach $candidates as $item}
            <tr>
                <td class="center">{$item@iteration}</td>
                <td>{$item['name']|escape}</td>
                <td class="center">{$item['votes']|number}</td>
                <td>{$constituencies[$item['constituency_id']]['id']}. {$constituencies[$item['constituency_id']]['title']|escape}</td>
                <td class="center">{$constituencies[$item['constituency_id']]['total_valid_votes']|number}</td>
                <td class="center">{$constituencies[$item['constituency_id']][HareNiemeyerInterface::TOTAL_MANDATES_COLUMN]}</td>
                <td class="center">{$constituencies[$item['constituency_id']]['quota']|number:3}</td>
                <td class="center">{if $item['is_elected']}<span class="green">Избран</span>{$electedIndependent = $electedIndependent + 1}{else}<span class="red">Неизбран</span>{/if}</td>
            </tr>
        {/foreach}
    </table>
    <div class="row">Избрани независими кандидати: <strong>{$electedIndependent}</strong></div>
    <div class="row">Оставащи мандати: <strong>{$assembly['total_mandates'] - $electedIndependent}</strong></div>
</section>
{/if}
<section>
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
        {assign var=votes value=0}
        {assign var=mandates value=0}
        {foreach $passedParties as $item}
            <tr>
                <td class="center">{$item['ord']+1}</td>
                <td>{$item['party_title']|escape}</td>
                <td class="center">{$item['total_votes']|number}</td>
                <td class="center{if $item[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}">{$item[HareNiemeyerInterface::REMAINDER_COLUMN]}</td>
                <td class="center">{$item[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
            </tr>
            {$votes    = $votes + $item['total_votes']}
            {$mandates = $mandates + $item[HareNiemeyerInterface::MANDATES_COLUMN]}
        {/foreach}

        {assign var=independentMandates value=0}
        {foreach $candidates as $item}
            {if !$item['is_elected']}{continue}{/if}
            {$independentMandates = $independentMandates + 1}
            {$votes = $votes + $item['votes']}
            <tr>
                <td class="center blue"><abbr title="Независим кандидат">Н</abbr></td>
                <td>{$item['name']|escape} (<em>{$constituencies[$item['constituency_id']]['id']}. {$constituencies[$item['constituency_id']]['title']|escape}</em>)</td>
                <td class="center">{$item['votes']|number}</td>
                <td class="center">&mdash;</td>
                <td class="center">1</td>
            </tr>
        {/foreach}

        {assign var=totalMandates value=$mandates+$independentMandates}
        <tr class="bold">
            <td class="center">&mdash;</td>
            <td>Общо</td>
            <td class="center">{$votes|number}</td>
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
            piechart_data.push(['{$item['party_title']|escape}', {$item["votes_percentage"]|percentage}, '{$item['party_abbreviation']|default:$item['party_title']|escape}', {$item[HareNiemeyerInterface::MANDATES_COLUMN]}]);
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
            <li><strong>{$item['party_title']|escape}</strong>: <span>{$item[HareNiemeyerInterface::REMAINDER_COLUMN]}</span></li>
        {/foreach}
        </ol>
    </section>
{/if}

{if !$lottingParties|@count}
<section>
    <h2>Втора стъпка: разпределение на мандати в МИР за всяка партия/коалиция</h2>
    <div class="row">Партиите, обозначени със <span class="blue">син цвят</span>, са получили допълнителен мандат от остатък.</div>

    {foreach $constituencies as $item}
        <hr />
        <h3>МИР {'%02d'|sprintf:$item['id']}. {$item['title']|escape}</h3>
        <div class="row">Действителни гласове за партиите, участвали в разпределението: {$localMandateDistribution[$item['id']]['votes']|number}</div>
        <div class="row">Брой мандати в МИР: {$item[HareNiemeyerInterface::TOTAL_MANDATES_COLUMN]|number}</div>
        <div class="row">Квота на Хеър-Ниймайер: {$localMandateDistribution[$item['id']]['quota']|number_format:16:'.':''}</div>
        <table class="results">
        <tr>
            <th class="center">#</th>
            <th>Партия/коалиция</th>
            <th class="center">Гласове в МИР</th>
            <th class="center">Остатък</th>
            <th class="center">Мандати</th>
        </tr>
        {assign var=votes value=0}
        {assign var=mandates value=0}
        {foreach $localMandateDistribution[$item['id']]['parties'] as $party}
            {$votes    = $votes    + $party['total_votes']}
            {$mandates = $mandates + $party[HareNiemeyerInterface::MANDATES_COLUMN]}
            <tr>
                <td class="center">{$party['ord']+1}</td>
                <td>{$party['party_title']|escape}</td>
                <td class="center">{$party['total_votes']|number}</td>
                <td class="center{if $party[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}">{$party[HareNiemeyerInterface::REMAINDER_COLUMN]}</td>
                <td class="center">{$party[HareNiemeyerInterface::MANDATES_COLUMN]}</td>
            </tr>
        {/foreach}
        <tr class="bold">
            <td class="center">&mdash;</td>
            <td>Общо</td>
            <td class="center">{$votes|number}</td>
            <td class="center">&mdash;</td>
            <td class="center">{$mandates|number}</td>
        </tr>
        </table>
        <br />
        <div class="row bold">Подредба на партиите по т.4.5.9 (<a href="https://dv.parliament.bg/DVWeb/showMaterialDV.jsp;jsessionid=667EF821DAFB81E32E84C547775D7B62?idMat=20345" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">чл. 21, ал. 5</a>):</div>
        <table class="results">
            <tr>
                <th class="center">#</th>
                <th>Партия/коалиция</th>
                <th>Остатък</th>
            </tr>
            {foreach $localMandateDistribution[$item['id']]['remainders'] as $party}
            <tr>
                <td class="center">{$party['ord']+1}</td>
                <td>{$party['party_title']|escape}</td>
                <td class="center{if $party[HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN]} blue{/if}">{$party[HareNiemeyerInterface::REMAINDER_COLUMN]}</td>
            </tr>
            {/foreach}
        </table>

        {if $localMandateDistribution[$item['id']]['lotting']}
            <strong>Следните партии са получили мандат по <a href="https://dv.parliament.bg/DVWeb/showMaterialDV.jsp;jsessionid=667EF821DAFB81E32E84C547775D7B62?idMat=20345" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">чл. 21, ал. 6</a>:</strong>
            <ul>
            {foreach $localMandateDistribution[$item['id']]['parties'] as $party}
                {if !isset($party['drawn_lot'])}{continue}{/if}
                <li><strong>{$party['party_title']}</strong></li>
            {/foreach}
            </ul>
        {/if}

        <br /><br />
    {/foreach}

    <hr />
    <h3 class="center">Разпределение на мандати на национално и местно ниво между партиите</h3>
    <div class="row center">
        <attr class="bold" title="Nota bene">NB!</attr> Партии и коацлии с нулева разлика (отбелязани в жълто)<br /> <strong>не</strong> участват в преразпределението на мандати.
    </div>
    <table class="results centered">
        <tr>
            <th class="center">#</th>
            <th>Партия/коалиция</th>
            <th>Брой мандати на национално ниво</th>
            <th>Брой мандати сумартно от всички МИР</th>
            <th>Разлика</th>
        </tr>
        {foreach $passedParties as $party}
            {assign var=local  value=$party[HareNiemeyerInterface::LOCAL_MANDATES_COLUMN]}
            {assign var=global value=$party[HareNiemeyerInterface::MANDATES_COLUMN]}
            {assign var=diff value=$local - $global}
            <tr{if $diff === 0} class="excluded"{/if}>
                <td class="center">{$party['ord']+1}</td>
                <td>{$party['party_title']|escape}</td>
                <td class="center">{$global}</td>
                <td class="center">{$local}</td>
                <td class="center{if $diff > 0} green{elseif $diff < 0} red{/if}">{if $diff > 0}+{/if}{$diff}</td>
            </tr>
        {/foreach}
    </table>
</section>
{/if}