<div class="row independent-item"{if !isset($static)} style="display: none"{/if}>
    <span class="independent-box"></span>
    <input type="text" name="independent_candidates[{$iterator|default:'%iterator%'}][name]" placeholder="Име" value="{$independentCandidateName|escape|default:''}" />
    <input type="text" size="5" name="independent_candidates[{$iterator|default:'%iterator%'}][votes]" value="{$independentCandidateVotes|escape|default:'0'}" placeholder="0" /> гласа
    <input type="hidden" name="independent_candidates[{$iterator|default:'%iterator%'}][constituency_id]" value="{$const['id']|default:'%constituency_id%'}" />
    <img src="{$_root}img/delete.png" title="Изтрий" class="remove-independent" />
</div>