{include file='elements/election-summary.tpl'}

<section>
    <h2>Резултати</h2>
    <div class="row">Разпределение на мандати между <a href="#independent" class="bold">независими кандидати</a></div>
    <div class="row"><a href="#step1" class="bold">Стъпка 1</a>: разпределение на мандати на национално ниво</div>

    {if !$lottingParties|@count}
        <div class="row"><a href="#step2" class="bold">Стъпка 2</a>: разпределение на мандати в МИР за всяка партия/коалиция</div>
    {/if}

    {if isset($redistribution)}
        <div class="row"><a href="#step3" class="bold">Стъпка 3</a>: преразпределение на мандатите (<em>{$redistribution|@count} итерации</em>)</div>
    {/if}

    {if isset($constituenciesMandates)}
        <div class="row"><a href="#final" class="bold">Окончателно разпределение</a> на мандати по райони</div>
    {/if}
    
</section>

{include file='elements/final-results-independent-candidates.tpl'}
{include file='elements/final-results-global-mandate-distribution.tpl'}
{include file='elements/final-results-local-mandate-distribution.tpl'}
{include file='elements/final-results-local-mandate-REdistribution.tpl'}
{include file='elements/final-results-all-parties-constituencies.tpl'}
