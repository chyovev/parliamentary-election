<li class="ms-elem-selection">
    <span class="handle"></span>
    <div class="row-wrapper">
        <div class="title-wrapper">
            <div class="title">
                <span class="ord">{($ord+1)|default:1}</span>. {$label|default:'%label%'}
                <span class="none">{$abbr|default:'%abbr%'}</span>
            </div>
        </div>
        <div class="votes">
            (<span class="count">{$votes|default:0}</span> гласа)
            <input type="hidden" value="{$votes|default:0}" name="parties[{$id|default:'%id%'}][total_votes]" />
        </div>
    </div>
    <div class="actions">
        <span class="edit-votes" title="Промени броя гласове"></span>
        <span class="remove-party" title="Премахни"></span>
    </div>
    <input type="hidden" name="parties[{$id|default:'%id%'}][party_id]" value="{$id|default:'%id%'}" />
    <input type="hidden" name="parties[{$id|default:'%id%'}][ord]" value="{$ord|default:0}" />
    {if isset($color) && $color}
    <input type="hidden" name="parties[{$id|default:'%id%'}][color]" value="{$color}" />
    {/if}
</li>