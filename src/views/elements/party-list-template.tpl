<li class="ms-elem-selection">
    <span class="handle"></span>
    <div class="row-wrapper">
        <div class="title-wrapper">
            <div class="title" title="{$label|default:'%label%'|escape}">
                <span class="ord">{$ord|default:1}</span>. {$label|default:'%label%'|escape}
                <span class="none">{$abbr|default:'%abbr%'|escape}</span>
            </div>
        </div>
        <div class="votes">
            (<span class="count">{$votes|number|default:0}</span> гласа)
        </div>
        <input type="text" class="votes-input none" />
        <input type="hidden" value="{$votes|default:0}" name="parties[{$id|default:'%id%'}][total_votes]" />
    </div>
    <div class="actions">
        <span class="edit-votes" title="Редакция на гласовете"></span>
        <span class="remove-party" title="Премахване на партия"></span>
    </div>
    <input type="hidden" name="parties[{$id|default:'%id%'}][party_id]" value="{$id|default:'%id%'}" />
    <input type="hidden" name="parties[{$id|default:'%id%'}][ord]" value="{$ord|default:1}" />
    {if isset($color) && $color}
    <input type="hidden" name="parties[{$id|default:'%id%'}][color]" value="{$color}" />
    {/if}
</li>