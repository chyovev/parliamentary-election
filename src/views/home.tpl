<form method="post" action="{url controller='results' action='preliminary'}">
    <section>
        <h2>Обща информация</h2>
        <div class="row">
            <span>Парламентарни избори за:</span>
            <select name="assembly_type">
            {foreach $assemblies as $item}
                <option value="{$item->getId()}">{$item->getTitle()|escape} ({$item->getTotalMandates()} мандата)</option>
            {/foreach}
            </select>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от:</span>
            <select name="population_census">
            {foreach $censuses as $item}
                <option value="{$item->getId()}">{$item->getYear()} г. ({$item->getPopulation()|number} души)</option>
            {/foreach}
            </select>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас:</span>
            <input type="number" size="5" name="active_suffrage" min="0" placeholder="0" required="true" />
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина:</span>
            <input type="number" size="5" name="total_valid_votes" min="0" placeholder="0" required="true" />
        </div>

        <div class="row">
            <span>Брой <em>недействителни</em> гласове в страната и чужбина:</span>
            <input type="number" size="5" name="total_invalid_votes" min="0" value="0" placeholder="0" required="true" />
        </div>

        <div class="row">
            <span>Долна граница за представителство:</span>
            <input type="number" size="5" name="threshold_percentage" min="1" max="100" value="4" placeholder="1" required="true" />
        </div>
    </section>

    <section>
        <h2>Партии, участващи в изборите (<span id="parties-count">0</span>)</h2>

        <script type="text/template" id="party-template">
            {include file='elements/party-list-template.tpl'}
        </script>

        <div class="ms-container">
            <div class="ms-selectable">
                <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br /><br />
                <ul class="ms-list" tabindex="-1">
                {foreach $allParties as $item}
                    <li class="ms-elem-selectable" data-id="{$item->getId()}"><span class="title">{$item->getTitle()|escape}</span><span class="abbr none">{$item->getAbbreviation()|escape}</span></li>
                {/foreach}
              </ul>
           </div>

           <div class="ms-selection">
              <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br><br>
              <ul class="ms-list" tabindex="-1">
              </ul>
           </div>
        </div>
    </section>
    <section>
        <div class="center"><button type="submit">Резултати</button></div>
    </section>
</form>