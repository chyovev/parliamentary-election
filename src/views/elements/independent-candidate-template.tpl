<div class="row independent-item"{if !isset($static)} style="display: none"{/if}>
    <input type="text" name="{FieldManager::CANDIDATES_FIELD}[{$iterator|default:'%iterator%'}][{FieldManager::CAND_NAME_FIELD}]" placeholder="Име" value="{$independentCandidateName|escape|default:''}" />
    <input type="text" size="5" name="{FieldManager::CANDIDATES_FIELD}[{$iterator|default:'%iterator%'}][{FieldManager::CAND_VOTES_FIELD}]" value="{$independentCandidateVotes|escape|default:'0'}" placeholder="0" /> гласа
    <input type="hidden" name="{FieldManager::CANDIDATES_FIELD}[{$iterator|default:'%iterator%'}][{FieldManager::CAND_CONST_FIELD}]" value="{$const['id']|default:'%constituency_id%'}" />
    <img src="{$_root}img/delete.png" title="Изтрий" class="remove-independent" />
</div>