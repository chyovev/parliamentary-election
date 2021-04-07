{if isset($redistribution)}
<section id="step3">
    <h2>Трета стъпка: преразпределение на мандатите</h2>

    {foreach $redistribution as $iteration => $data}
        {assign var=receivingPartyId value=$data['receiving_party_id']}
        {assign var=givingPartyId    value=$data['giving_party_id']}

        <h3>Итерация #{$iteration}</h3>
        <div class="row bold">Преразпределяне на мандати по (<a href="https://www.lex.bg/laws/ldoc/2136112596#i_2867" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">т.4.6.8</a>):</div>

        {if isset($data['iteration_remainders'])}
        <table class="results sortable">
            <thead>
                <tr>
                    <th class="sortable"><span>Партия/коалиция</span></th>
                    <th class="sortable"><span>Избирателен район</span></th>
                    <th class="sortable asc"><span>Остатък</span></th>
                </tr>
            </thead>

            <tbody>
            {foreach $data['iteration_remainders'] as $item}
                {assign var=constituency value=$constituencies[$item['constituency_id']]}
                <tr{if $item@first} class="blue"{/if}>
                    <td>{$data['parties'][$item['party_id']]['party_abbreviation']|default:$data['parties'][$item['party_id']]['party_title']|escape}</td>
                    <td data-value="{$constituency['id']}">{$constituency['id']}. {$constituency['title']|escape}</td>
                    <td data-value="{$item['remainder']}">{$item['remainder']|number:15}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        {/if}


        {capture assign=constituencyTitle}{$constituencies[$data['constituency_id']]['id']}. {$constituencies[$data['constituency_id']]['title']|escape}{/capture}
        {assign  var=showRemainder value=!isset($data['final'])} {* don't show remainder column if final is set to true *}

        {include file='elements/parties-local-global-comparison.tpl' parties=$data['parties'] centered=false receivingPartyId=$receivingPartyId givingPartyId=$givingPartyId constituencyTitle=$constituencyTitle showRemainder=$showRemainder localSnapshot=$data['local_snapshot']}

        {if !isset($data['final'])}
            {if !$receivingPartyId}
                <div class="row"><span class="bold">МИР {$constituencyTitle}:</span> Не е намерена партия, на която да се даде мандат</div>
            {else}
                <div class="row"><span class="bold">МИР {$constituencyTitle}:</span> отнемане на мандат от <span class="red bold">{$data['parties'][$givingPartyId]['party_title']|escape}</span> → даване на мандат на <span class="green bold">{$data['parties'][$receivingPartyId]['party_title']|escape}</span></div>
            {/if}
        {/if}

        <br /><br /><hr />
    {/foreach}

    {if isset($noMoreRemainders)}
        <h4 class="center">Изчерпани са всички остатъци, няма как да се преразпределят оставащите мандатите.</h4>
    {/if}

</section>
{/if}