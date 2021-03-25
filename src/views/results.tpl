<form method="post" action="#">
    <section>
        <h2>Обща информация</h2>

        {if isset($assembly)}
        <div class="row">
            <span>Парламентарни избори за: <strong>{$assembly->getTitle()|escape} ({$assembly->getTotalMandates()} мандата)</strong></span>
        </div>
        {/if}

        {if isset($census)}
        <div class="row">
            <span>Население на Република България по данни на НСИ от {$census->getYear()} г.: <strong>{$census->getPopulation()|number} души</strong></span>
        </div>
        {/if}

        {if isset($election)}
        <div class="row">
            <span>Брой души, имащи право на глас: <strong>{$election->getActiveSuffrage()|number_format:0:'.':' '}</strong></span>
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина: <strong>{$election->getTotalValidVotes()|number_format:0:'.':' '}</strong></span>
        </div>

        <div class="row">
            <span>Брой <em>недействителни</em> гласове в страната и чужбина: <strong>{$election->getTotalInvalidVotes()|number_format:0:'.':' '}</strong></span>
        </div>

        <div class="row">
            <span>Избирателна активност: <strong>{$election->getActivity()|percentage}%</strong></span>
        </div>

        <div class="row">
            <span>Долна граница за представителство: <strong>{$election->getThresholdPercentage()}%</strong></span>
        </div>
        {/if}

    </section>

    {if isset($error)}
        <section>
            <div class="center bold">{$error}</div>
        </section>
    {/if}

    {if isset($passedParties)}
    <section>
        <h2>Предварителни резултати</h2>


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
                    <td class="center"><input type="hidden" class="color-picker" value="{$item['color']}" data-colors-index="{$item@index}" /></td>
                    <td>{$item['party']|escape}</td>
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
                {if $assembly->getTotalMandates() > $mandates}
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
                piechart_colors = [];
                
            {foreach $passedParties as $item}
                piechart_data.push(['{$item["party"]|escape}', {$item["votes_percentage"]|percentage}]);
                piechart_colors.push('{$item['color']}');
            {/foreach}
            </script>

            <p><strong title="Nota bene">NB!</strong>За крайните резултати трябва във всеки МИР да въведете получените гласове за всяка от партиите, преминали границата за представителство, и за независимите кандидати.</p>
        </div>

    </section>
    {/if}
</form>