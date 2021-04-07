<div id="piechart"></div>
<a href="#" download="election-results.png" class="download-chart" title="Сваляне на графиката като изображение"><img src="{$_root}img/download.svg" /> Свали</a>

<script type="text/javascript">
var piechart_data   = [],
    piechart_labels = [],
    piechart_colors = [];

{foreach $passedParties as $item}{assign var=suffix value=($item[HareNiemeyerInterface::MANDATES_COLUMN] == 1)?'мандат':'мандата'}
    piechart_data.push(['{$item['party_abbreviation']|default:$item['party_title']|escape} &mdash; {$item[HareNiemeyerInterface::MANDATES_COLUMN]} {$suffix}', {$item[HareNiemeyerInterface::MANDATES_COLUMN]}, '{$item['party_abbreviation']|default:$item['party_title']|escape}', {$item[HareNiemeyerInterface::MANDATES_COLUMN]}]);
    piechart_labels.push('{$item["votes_percentage"]|percentage}%');
    piechart_colors.push('{$item['party_color']}');
{/foreach}

{if isset($includeCandidates) && $includeCandidates}
    {foreach $candidates as $item}
        {if !isset($item['is_elected']) || !$item['is_elected']}{continue}{/if}
        piechart_data.push(['{$item['name']|escape}', 5, '{$item['name']|escape}', {1}]);
        piechart_labels.push('1%');
    {/foreach}
{/if}
</script>