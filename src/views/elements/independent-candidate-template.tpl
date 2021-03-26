<div class="row independent-item">
    <input type="text" name="independent_candidate[%iterator%][name]" placeholder="Име" required="true" />
    <input type="number" min="0" size="5" name="independent_candidate[%iterator%][votes]" placeholder="0" required="true" /> гласа
    <input type="hidden" name="independent_candidate[%iterator%][constituency_id]" value="%constituency_id%" />
    <img src="{$_root}img/delete.png" title="Изтрий" class="remove-independent" />
</div>