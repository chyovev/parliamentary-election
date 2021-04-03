<form class="ajax-form" method="post" action="{url controller='validation' action='election'}" data-success-action="App.goToPage('{url controller='results' action='preliminary'}')">
    {if isset($official)}
    <section>
        <h3>Показване на резултати от парламентарни избори:</h3>
        <ul>
            {foreach $official as $slug}
                <li><a class="bold" href="{url controller='home' action='index' slug=$slug}">{$slug}</a></li>
            {/foreach}
        </ul>
    </section>
    {/if}
    <section>
        <h2>Обща информация <a href="#" class="reset-form" data-url="{url controller='validation' action='reset'}" data-step="{$currentStep}" title="Изтрий полетата и премахни избраните партии">Нулиране</a></h2>
        <div class="row">
            <span>Парламентарни избори за:</span>
            <select name="assembly_type_id" class="assembly_type_id">
            {foreach $assemblies as $item}
                <option value="{$item['id']}"{if isset($election) && $election['assembly_type_id'] === $item['id']} selected="selected"{/if}>{$item['title']|escape} ({$item['total_mandates']} мандата)</option>
            {/foreach}
            </select>
            <div class="error-message assembly_type_id_message"></div>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от:</span>
            <select name="population_census_id" class="population_census_id">
            {foreach $censuses as $item}
                <option value="{$item['id']}"{if isset($election) && $election['population_census_id'] === $item['id']} selected="selected"{/if}>{$item['year']} г. ({$item['population']|number} души)</option>
            {/foreach}
            </select>
            <div class="error-message population_census_id_message"></div>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас:</span>
            <input type="text" size="5" name="active_suffrage" class="active_suffrage" placeholder="0" value="{$election['active_suffrage']|default:'0'}" />
            <div class="error-message active_suffrage_message"></div>
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина:</span>
            <input type="text" size="5" name="total_valid_votes" class="total_valid_votes" placeholder="0" value="{$election['total_valid_votes']|default:'0'}" />
            <div class="error-message total_valid_votes_message"></div>
        </div>

        <div class="row">
            <span>Брой <em>недействителни</em> гласове в страната и чужбина:</span>
            <input type="text" size="5" name="total_invalid_votes" class="total_invalid_votes" placeholder="0" value="{$election['total_invalid_votes']|default:0}" />
            <div class="error-message total_invalid_votes_message"></div>
        </div>

        <div class="row">
            <span>Долна граница за представителство:</span>
            <input type="text" size="5" name="threshold_percentage" class="threshold_percentage" max="100" placeholder="1" value="{$election['threshold_percentage']|default:4}" />
            <div class="error-message threshold_percentage_message"></div>
        </div>
    </section>

    <section>
        <h2>Партии, участващи в изборите (<span id="parties-count">{$selectedParties|@count|default:0}</span>)</h2>
        <div class="row">Желателно е подредбата на партиите да съответства на номерата им,<br /> тъй като това играе роля при преразпределянето на мандати.<br /><br /></div>

        <script type="text/template" id="party-template">
            {include file='elements/party-list-template.tpl'}
        </script>

        <div class="ms-container">
            <div class="ms-selectable">
                <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br /><br />
                <ul class="ms-list" tabindex="-1">
                {foreach $allParties as $item}
                    <li class="ms-elem-selectable{if isset($selectedParties[$item['id']])} ms-selected{/if}" data-id="{$item['id']}"><span class="title">{$item['title']|escape}</span><span class="abbr none">{$item['abbreviation']|escape}</span></li>
                {/foreach}
                </ul>
            </div>

            <div class="ms-selection">
                <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br><br>
                <ul class="ms-list parties" tabindex="-1">
                {if isset($selectedParties)}
                    {foreach $selectedParties as $item}
                        {include file='elements/party-list-template.tpl' label=$item['title']|escape abbr=$item['abbreviation']|escape id=$item['id'] ord=$item@index votes=$item['total_votes'] color=$item['party_color']}
                    {/foreach}
                {/if}
                </ul>
                <div class="error-message parties_message"></div>
            </div>
        </div>
    </section>
    <section>
        <h2>Брой действителни гласове във всеки <abbr title="Многомандатен избирателен район">МИР</abbr></h2>
        <ol class="constituencies-list">
            {foreach $constituencies as $item}
                <li>
                    <strong>{$item['title']|escape}</strong>:
                    <input type="text" size="2" class="constituency_votes-{$item['id']}" name="constituency_votes[{$item['id']}]" value="{$item['total_valid_votes']|default:0}" /> гласа
                </li>
            {/foreach}
        </ol>
        <div class="error-message center constituencies_fields_message"></div>
    </section>
    <section>
        <div class="center"><button type="submit">Резултати</button></div>
    </section>
</form>