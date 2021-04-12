<section class="center">
    <form class="ajax-form" method="post" action="{url controller='results' action='save'}">
        <button type="submit" class="save" id="save-data" title="Генерира се уникален адрес, на който настоящата информация ще е достъпна.">Запазване на резултатите</button>
        <div class="error-message mt-2 save_message"></div>
    </form>
</section>

<div class="popup-wrapper">
    <div id="popup" class="url-popup">
        <img src="{$_root}img/close.svg" title="Затвори" class="close-popup" />
        <h2 class="center">Запазването е успешно!</h2>
        <div class="row center">
            <span>Резултатите се намират на адрес:</span>
            <input type="text" readonly="readonly" id="results-url" />
            <span class="copy" data-target="#results-url" title="Копиране на адреса"></span>
            <span class="copy-success none">&#10004;</span>
        </div>
    </div>
</div>