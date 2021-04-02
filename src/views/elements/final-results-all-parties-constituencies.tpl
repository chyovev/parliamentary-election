{if isset($constituenciesMandates)}
<section id="final">
    <h3>Окончателно разпределение на мандати между партии и коалиции по <a href="https://www.lex.bg/laws/ldoc/2136112596#i_2867" title="Методика за определяне на резултатите от гласуването за народни представители" target="_blank">т.4.6.9</a></h3>
    <table class="results fullwidth">
        <thead>
            <tr>
                <th rowspan="2" colspan="2">Район</th>
                <th colspan="{$passedParties|@count}" class="uppercase center">Партия/коалиция</th>
                <th rowspan="2" class="center">Общо</th>
            <tr>
                {foreach $passedParties as $item}
                    <th class="center"><abbr title="{$item['party_title']|escape}">{$item['party_abbreviation']|default:$item['party_title']|escape}</abbr></th>
                {/foreach}
            </tr>
        </thead>

        <tbody>
        {assign var=totalMandates   value=0}
        {foreach $constituencies as $item}
            <tr>
                <td class="center">{$item['id']}</td>
                <td>{$item['title']|escape}</td>
                {assign var=constituencyMandates value=0}
                {foreach $passedParties as $party}
                    {assign var=partyId  value=$party['party_id']}
                    {assign var=mandates value=$constituenciesMandates[$item['id']][$partyId]}

                    {if !isset($partiesMandates[$partyId])}{$partiesMandates[$partyId]=0}{/if}

                    {$totalMandates        = $totalMandates + $mandates}
                    {$constituencyMandates = $constituencyMandates + $mandates}

                    {$partiesMandates[$partyId] = $partiesMandates[$partyId] + $mandates}
                    <td class="center">{$mandates}</td>
                {/foreach}
                <td class="center">{$constituencyMandates}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr class="bold">
            <td colspan="2" class="center">Общо</td>
            {foreach $passedParties as $item}
                <td class="center">{$partiesMandates[$item['party_id']]}</td>
            {/foreach}
            <td class="center">{$totalMandates}</td>
        </tr>
        </tfoot>
    </table>
</section>
{/if}

