<li class="ms-elem-selection">
    <span class="handle"></span>
    <span class="title">
        <abbr title="{$label|default:'%label%'}">{$abbr|default:'%abbr%'}</abbr>
    </span>
    <span class="none">{$label|default:'%label%'}</span>
    <span class="votes">
        (<em><span class="count">0</span> гласа</em>)
        <input type="hidden" value="0" name="parties[{$id|default:'%id%'}][total_votes]" />
    </span>
    <span class="edit-votes" title="Промени броя гласове"></span>
    <span class="remove-party" title="Премахни"></span>
    <input type="hidden" name="parties[{$id|default:'%id%'}][party_id]" value="{$id|default:'%id%'}" />
    <input type="hidden" name="parties[{$id|default:'%id%'}][ord]" value="0" />
</li>