<form class="ajax-form" method="post" action="{url controller='validation' action='election'}" data-success-action="App.goToPage('{url controller='results' action='preliminary'}')">
    <section>
        <h2>Обща информация</h2>
        <div class="row">
            <span>Парламентарни избори за:</span>
            <select name="{FieldManager::ASSEMBLY_FIELD}" class="{FieldManager::ASSEMBLY_FIELD}">
            {foreach $assemblies as $item}
                <option value="{$item['id']}"{if isset($election) && $election[FieldManager::ASSEMBLY_FIELD] === $item['id']} selected="selected"{/if}>{$item['title']|escape} ({$item['total_mandates']} мандата)</option>
            {/foreach}
            </select>
            <div class="error-message {FieldManager::ASSEMBLY_FIELD}_message"></div>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от:</span>
            <select name="{FieldManager::CENSUS_FIELD}" class="{FieldManager::CENSUS_FIELD}">
            {foreach $censuses as $item}
                <option value="{$item['id']}"{if isset($election) && $election[FieldManager::CENSUS_FIELD] === $item['id']} selected="selected"{/if}>{$item['year']} г. ({$item['population']|number} души)</option>
            {/foreach}
            </select>
            <div class="error-message {FieldManager::CENSUS_FIELD}_message"></div>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас:</span>
            <input type="text" size="5" name="{FieldManager::SUFFRAGE_FIELD}" class="{FieldManager::SUFFRAGE_FIELD}" placeholder="0" value="{$election[FieldManager::SUFFRAGE_FIELD]|default:'0'}" />
            <div class="error-message {FieldManager::SUFFRAGE_FIELD}_message"></div>
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина:</span>
            <input type="text" size="5" name="{FieldManager::VALID_VOTES_FIELD}" class="{FieldManager::VALID_VOTES_FIELD}" placeholder="0" value="{$election[FieldManager::VALID_VOTES_FIELD]|default:'0'}" />
            <div class="error-message {FieldManager::VALID_VOTES_FIELD}_message"></div>
        </div>

        <div class="row">
            <span>Брой <em>недействителни</em> гласове в страната и чужбина:</span>
            <input type="text" size="5" name="{FieldManager::INVALID_VOTES_FIELD}" class="{FieldManager::INVALID_VOTES_FIELD}" placeholder="0" value="{$election[FieldManager::INVALID_VOTES_FIELD]|default:0}" />
            <div class="error-message {FieldManager::INVALID_VOTES_FIELD}_message"></div>
        </div>

        <div class="row">
            <span>Долна граница за представителство:</span>
            <input type="text" size="5" name="{FieldManager::THRESHOLD_FIELD}" class="{FieldManager::THRESHOLD_FIELD}" max="100" placeholder="1" value="{$election[FieldManager::THRESHOLD_FIELD]|default:4}" />
            <div class="error-message {FieldManager::THRESHOLD_FIELD}_message"></div>
        </div>
    </section>

    <section>
        <h2>Партии, участващи в изборите (<span id="parties-count">{$selectedParties|@count|default:0}</span>)</h2>

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
                        {include file='elements/party-list-template.tpl' label=$item['title']|escape abbr=$item['abbreviation']|escape id=$item['id'] ord=$item@index votes=$item[FieldManager::PARTY_TOTAL_VOTES] color=$item[FieldManager::PARTY_COLOR]}
                    {/foreach}
                {/if}
                </ul>
                <div class="error-message parties_message"></div>
            </div>
        </div>
    </section>
    <section>
        <div class="center"><button type="submit">Резултати</button></div>
    </section>
</form>