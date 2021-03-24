<form method="post">
    <section>
        <h2>Обща информация</h2>
        <div class="row">
            <span>Парламентарни избори за:</span>
            <select name="assembly_type">
                <option value="1" data-percentage="4">Народно събрание (240 мандата)</option>
                <option value="2" data-percentage="6">Велико Народно събрание (240 мандата)</option>
            </select>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от:</span>
            <select name="population_census">
                <option value="1">2011 г. (7 364 570 души)</option>
            </select>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас:</span>
            <input type="text" size="5" name="active_suffrage" placeholder="0" />
        </div>

        <div class="row">
            <span>Долна граница за представителство:</span>
            <strong><span class="threshold">4</span>%</strong>
        </div>
    </section>

    <section>
        <h2>Партии, участващи в изборите</h2>

        <script type="text/template" id="party-template">
            {include file='elements/party-list-template.tpl'}
        </script>

        <div class="ms-container">
           <div class="ms-selectable">
              <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br /><br />
              <ul class="ms-list" tabindex="-1">
                 <li class="ms-elem-selectable" data-id="1"><span class="title">Алтернатива за Българско Възраждане</span><span class="abbr none">АБВ</span></li>
                 <li class="ms-elem-selectable" data-id="2"><span class="title">Атака</span></li>
                 <li class="ms-elem-selectable" data-id="8"><span class="title">Воля</span></li>
                 <li class="ms-elem-selectable" data-id="9"><span class="title">Глас Народен</span><span class="abbr none">ГН</span></li>
                 <li class="ms-elem-selectable" data-id="10"><span class="title">Граждани за европейско развитие на България</span><span class="abbr none">ГЕРБ</span></li>
                 <li class="ms-elem-selectable" data-id="12"><span class="title">Движение за права и свободи</span><span class="abbr none">ДПС</span></li>
                 <li class="ms-elem-selectable" data-id="15"><span class="title">Зелените</span></li>
                 <li class="ms-elem-selectable" data-id="19"><span class="title">Да България</span><span class="abbr none">ДБ</span></li>
                 <li class="ms-elem-selectable" data-id="32"><span class="title">Патриотичен фронт</span><span class="abbr none">ПФ</span></li>
                 <li class="ms-elem-selectable" data-id="40"><span class="title">Реформаторски блок</span><span class="abbr none">РБ</span></li>
              </ul>
           </div>

           <div class="ms-selection">
              <input type="text" class="search-input w-100" autocomplete="off" placeholder="Търсене по име на партия или абревиатура"><br><br>
              <ul class="ms-list">
              </ul>
           </div>
        </div>
    </section>
    <section>
        <div class="center"><button type="submit">Резултати</button></div>
    </section>
</form>