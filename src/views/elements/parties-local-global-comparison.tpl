<table class="results{if $centered} centered{/if}">
    {if isset($constituencyTitle) && $constituencyTitle && isset($showRemainder) && $showRemainder}
    <tr>
        <th colspan="{if $showRemainder}6{else}5{/if}" class="center uppercase">{$constituencyTitle}</th>
    </tr>
    {/if}
    <tr>
        <th class="center">#</th>
        <th>Партия/коалиция</th>
        {if isset($showRemainder) && $showRemainder}
            <th>Остатък</th>
        {/if}
        <th>Брой мандати на национално ниво</th>
        <th>Брой мандати сумартно от всички МИР</th>
        <th>Разлика</th>
    </tr>
    {foreach $parties as $party}
        {assign var=local        value=$party[HareNiemeyerInterface::LOCAL_MANDATES_COLUMN]}
        {assign var=global       value=$party[HareNiemeyerInterface::MANDATES_COLUMN]}
        
        {assign var=remainder    value=$localSnapshot[$party['party_id']]['hare_niemeyer_remainder']}
        {assign var=has_received value=$localSnapshot[$party['party_id']]['has_received_hare_niemeyer_mandate']}

        {assign var=diff value=$local - $global}
        <tr {if $receivingPartyId} class="{if $receivingPartyId == $party['party_id']}receiving{elseif $givingPartyId == $party['party_id']}giving{/if}"{/if}>
            <td class="center">{$party['ord']+1}</td>
            <td>{$party['party_abbreviation']|default:$party['party_title']|escape}</td>
            {if isset($showRemainder) && $showRemainder}
                <td{if $has_received} class="blue"{/if}>{if $remainder}{$remainder|number:15}{/if}</td>
            {/if}
            <td class="center">{$global}</td>
            <td class="center">{$local}</td>
            <td class="center{if $diff > 0} green{elseif $diff < 0} red{/if}">{if $diff > 0}+{/if}{$diff}</td>
        </tr>
    {/foreach}
</table>