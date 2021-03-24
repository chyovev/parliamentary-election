<form method="post" action="#">
    <section>
        <h2>Обща информация</h2>
        <div class="row">
            <span>Парламентарни избори за: <strong>{$assemblyType->getTitle()|escape} ({$assemblyType->getTotalMandates()} мандата)</strong></span>
        </div>

        <div class="row">
            <span>Население на Република България по данни на НСИ от {$census->getYear()} г.: <strong>{$census->getPopulation()|number_format:0:',':' '} души</strong></span>
        </div>

        <div class="row">
            <span>Брой души, имащи право на глас: <strong>{$entitledToVote|number_format:0:',':' '}</strong></span>
        </div>

        <div class="row">
            <span>Брой действителни гласове в страната и чужбина: <strong>{$totalValidVotes|number_format:0:',':' '}</strong></span>
        </div>

        <div class="row">
            <span><abbr title="В това число не влизат недействителните гласове">Приблизителна</abbr> избирателна активност: <strong>~{$electionActivity|number_format:2:',':' '|regex_replace:'/(,0+)$/':''}%</strong></span>
        </div>

        <div class="row">
            <span>Общ брой на партиите и коалициите (без независимите кандидати): <strong>{$electionParties->count()}</strong></span>
        </div>

        <div class="row">
            <span>Долна граница за представителство: <strong>{$thresholdPercentage}%</strong></span>
        </div>

        <div class="row">
            <span>Общ брой на партиите и коалициите, преминали долната граница: <strong>{$passedParties->count()}</strong></span>
        </div>

    </section>
</form>