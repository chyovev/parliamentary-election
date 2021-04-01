{if isset($candidates)}
<section id="independent">
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