<form class="ajax-form" method="post" action="{url controller='validation' action='constituencies'}" data-success-action="App.goToPage('{url controller='results' action='preliminary'}')">
    <script type="text/javascript">var independent_counter = {$independentCount|default:0};</script>
    
    <section>

        {if isset($passedParties)}
            <div id="activity-chart"></div>
        {/if}

        <h2>Обща информация</h2>
        <div class="row">
            <span>Парламентарни избори за: <strong>{$assembly['title']|escape} ({$assembly['total_mandates']} мандата)</strong></span>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от {$census['year']} г.: <strong>{$census['population']|number} души</strong></span>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас: <strong>{$election[FieldManager::SUFFRAGE_FIELD]|number}</strong></span>
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина: <strong>{$election[FieldManager::VALID_VOTES_FIELD]|number}</strong></span>
        </div>

        <div class="row">
            <span>Брой <em>недействителни</em> гласове в страната и чужбина: <strong>{$election[FieldManager::INVALID_VOTES_FIELD]|number}</strong></span>
        </div>

        <div class="row">
            <span>Избирателна активност: <strong>{$election['activity']|percentage}%</strong></span>
        </div>

        <div class="row">
            <span>Долна граница за представителство: <strong>{$election[FieldManager::THRESHOLD_FIELD]}%</strong></span>
        </div>

    </section>

    {if isset($passedParties)}
    <section>
        <h2>Предварителни резултати</h2>

        {if $passedParties|@count === 0}
            <div class="row">Нито една партия не е преминала долната граница за представителство.</div>
        {else}
            <div class="row">
                <span>Общ брой на партиите и коалициите (без независими кандидати): <strong>{$electionParties->count()}</strong></span>
            </div>

            <div class="row">
                <span>Общ брой на партиите и коалициите, преминали долната граница: <strong>{$passedParties|@count}</strong></span>
            </div>

            <table class="results">
                <tr class="heading">
                    <th>#</th>
                    <th>Цвят</th>
                    <th class="left">Партия/коалиция</th>
                    <th>Гласове</th>
                    <th>Процент</th>
                    <th>Мандати</th>
                </tr>
                
                {assign var=votes value=0}
                {assign var=mandates value=0}
                {assign var=percentages value=0}

                {foreach $passedParties as $item}
                    {$votes       = $votes + $item['votes']}
                    {$mandates    = $mandates + $item['mandates']}
                    {$percentages = $percentages + $item['votes_percentage']}
                    <tr>
                        <td class="center">{$item@iteration}</td>
                        <td class="center"><input type="hidden" class="color-picker" name="parties[{$item['party_id']}][{FieldManager::PARTY_COLOR}]" value="{$item[FieldManager::PARTY_COLOR]}" data-colors-index="{$item@index}" /></td>
                        <td>{$item['title']|escape}</td>
                        <td class="center">{$item['votes']|number}</td>
                        <td class="center">{$item['votes_percentage']|percentage}%</td>
                        <td class="center">{$item['mandates']}</td>
                    </tr>
                {/foreach}

                <tr class="bold">
                    <td class="center">&mdash;</td>
                    <td class="center">&mdash;</td>
                    <td>Общо</td>
                    <td class="center">{$votes|number}</td>
                    <td class="center">{min($percentages, 100)|percentage}%</td>
                    {if $mandates > 0 && $mandates < $assembly['total_mandates']}
                        <td class="center red"><em><abbr title="Възможен е жребий на ЦИК за преразпределяне на липсващите мандати">{$mandates}</abbr></em></td>
                    {else}
                        <td class="center">{$mandates}</td>
                    {/if}
                </tr>
            </table>

            <div class="chart-wrapper">
                <div id="piechart"></div>

                <script type="text/javascript">
                var piechart_data   = [],
                    piechart_colors = [],
                    barchart_data   = [['Активност', {$election['activity']|percentage}], ['Преминали партии ({$passedParties|@count})', {($votes/$election[FieldManager::SUFFRAGE_FIELD]*100)|percentage}]];
                    
                {foreach $passedParties as $item}
                    piechart_data.push(['{$item["title"]|escape}', {$item["votes_percentage"]|percentage}, '{$item["abbreviation"]|escape}', {$item['mandates']}]);
                    piechart_colors.push('{$item[FieldManager::PARTY_COLOR]}');
                {/foreach}
                </script>

                {if $passedParties|@count > 1}
                    <p><strong title="Nota bene">NB!</strong> За крайните резултати трябва във всеки МИР <a href="#map">да въведете</a> получените гласове за всяка от партиите, преминали границата за представителство, както и за независимите кандидати.</p>
                {/if}
            </div>
        {/if}

    </section>
    {/if}

    {if isset($passedParties) && $passedParties|@count > 1}
        {include file='elements/map-constituencies-bulgaria.tpl'}
    {/if}

</form>

<script type="text/template" id="independent-template">
    {include file='elements/independent-candidate-template.tpl'}
</script>

<form class="popup-wrapper ajax-form" method="post" action="{url controller='validation' action='constituencies'}" data-success-action="App.closePopupForm()">
    <div id="popup">
        <img src="{$_root}img/close.svg" title="Затвори" class="close-popup" />
        <h2 class="center">Попълване на мандати за <span class="mmc"></span></h2>
        <section class="parties"></section>
    </div>
</form>
