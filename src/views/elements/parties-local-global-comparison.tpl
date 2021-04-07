<table class="results{if $centered} centered{/if} sortable">
    <thead>
        {if isset($constituencyTitle) && $constituencyTitle && isset($showRemainder) && $showRemainder}
        <tr>
            <th colspan="{if $showRemainder}6{else}5{/if}" class="center uppercase">{$constituencyTitle}</th>
        </tr>
        {/if}
        <tr>
            <th class="center sortable asc"><span>#</span></th>
            <th class="sortable"><span>Партия/коалиция</span></th>
            {if isset($showRemainder) && $showRemainder}
                <th class="sortable"><span>Остатък</span></th>
            {/if}
            <th class="sortable"><span>Брой мандати на национално ниво</span></th>
            <th class="sortable"><span>Брой мандати сумартно от всички МИР</span></th>
            <th class="sortable"><span>Разлика</span></th>
        </tr>
    </thead>
    <tbody>
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
                <td data-value="{$remainder|default:0}"{if $has_received} class="blue"{/if}>{if $remainder}{$remainder|number:15}{/if}</td>
            {/if}
            <td class="center">{$global}</td>
            <td class="center">{$local}</td>
            <td data-value="{$diff}" class="center{if $diff > 0} green{elseif $diff < 0} red{/if}">{if $diff > 0}+{/if}{$diff}</td>
        </tr>
    {/foreach}
    </tbody>
</table>