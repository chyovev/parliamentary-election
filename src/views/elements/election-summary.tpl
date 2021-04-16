<section>

    {if isset($passedParties)}
        {assign var=votes value=0}

        {foreach $passedParties as $item}
            {$votes = $votes + $item['total_votes']}
        {/foreach}
    {/if}

    <div id="activity-chart"></div>
    <script type="text/javascript">
        var barchart_data   = [['Активност', {$election['activity']|percentage}]{if isset($votes)}, ['Преминали партии ({$passedParties|@count})', {($votes/$election['active_suffrage']*100)|percentage}]{/if}];
    </script>

    <h2>Обща информация</h2>
    <div class="row">
        <span>Парламентарни избори за: <strong>{$assembly['title']|escape} ({$assembly['total_mandates']} мандата)</strong></span>
    </div>

    <div class="row">
        <span>Население на Република България по данни на НСИ от {$census['year']} г.: <strong>{$census['population']|number} души</strong></span>
    </div>

    <div class="row">
        <span>Брой души, имащи право на глас: <strong>{$election['active_suffrage']|number}</strong></span>
    </div>

    <div class="row">
        <span>Брой действителни гласове в страната и чужбина: <strong>{$election['total_valid_votes']|number}</strong></span>
    </div>

    <div class="row">
        <span>&nbsp; &nbsp; &nbsp; &rdsh; от тях гласували с „Не подкрепям никого“: <strong>{$election['trust_no_one_votes']|number}</strong>
        {include file='elements/trust-no-one-tooltip.tpl'}
    </div>

    <div class="row">
        <span>Брой <em>недействителни</em> гласове в страната и чужбина: <strong>{$election['total_invalid_votes']|number}</strong></span>
    </div>

    <div class="row">
        <span>Избирателна активност: <strong>{$election['activity']|percentage}%</strong></span>
    </div>

    <div class="row">
        <span>Долна граница за представителство: <strong>{$election['threshold_percentage']}%</strong> (<em>{$election['threshold_votes']|number} глас{if $election['threshold_votes'] !== 1}а{/if}</em>)</span>
    </div>

</section>