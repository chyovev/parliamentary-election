{if isset($candidates) && $candidates}
<section id="independent">
    <h2><span class="independent-box"></span> Независими кандидати</h2>
     <table class="results fullwidth sortable">
        <thead>
            <tr class="heading">
                <th class="center sortable asc"><span>#</span></th>
                <th class="left sortable"><span>Име</span></th>
                <th class="sortable"><span>Гласове</span></th>
                <th class="sortable"><span>МИР</span></th>
                <th class="sortable"><span>Действителни гласове в МИР</span></th>
                <th class="sortable"><span>Мандати</span></th>
                <th class="sortable"><span><abbr title="Необходими гласове за избиране на независим кандидат">Квота</abbr></span></th>
                <th class="sortable"><span>Статус</span></th>
            </tr>
        </thead>
        <tbody>
        {assign var=electedIndependent value=0}
        {assign var=independentVotes value=0}
        {foreach $candidates as $item}
            {$independentVotes = $independentVotes + $item['votes']}
            <tr{if $item['is_elected']} class="receiving"{/if}>
                <td class="center">{$item@iteration}</td>
                <td>{$item['name']|escape}</td>
                <td class="center" data-value="{$item['votes']}">{$item['votes']|number}</td>
                <td data-value="{$constituencies[$item['constituency_id']]['id']}">{$constituencies[$item['constituency_id']]['id']}. {$constituencies[$item['constituency_id']]['title']|escape}</td>
                <td class="center" data-value="{$constituencies[$item['constituency_id']]['total_valid_votes']}">{$constituencies[$item['constituency_id']]['total_valid_votes']|number}</td>
                <td class="center">{$constituencies[$item['constituency_id']][HareNiemeyerInterface::TOTAL_MANDATES_COLUMN]}</td>
                <td class="center" data-value="{$constituencies[$item['constituency_id']]['quota']}">{$constituencies[$item['constituency_id']]['quota']|number:3}</td>
                <td class="center" data-value="{($item['is_elected']*$item@iteration) + $item@iteration}">{if $item['is_elected']}<span class="green">Избран</span>{$electedIndependent = $electedIndependent + 1}{else}<span class="red">Неизбран</span>{/if}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
            <tr class="bold">
                <td class="center">&mdash;</td>
                <td>Общо</td>
                <td class="center" data-value="{$independentVotes}">{$independentVotes|number}</td>
                <td>&mdash;</td>
                <td class="center">&mdash;</td>
                <td class="center">&mdash;</td>
                <td class="center">&mdash;</td>
                <td class="center">&mdash;</td>
        </tfoot>
    </table>
    <div class="row">Избрани независими кандидати: <strong>{$electedIndependent}</strong></div>
    <div class="row">Оставащи мандати: <strong>{$assembly['total_mandates'] - $electedIndependent}</strong></div>
</section>
{/if}