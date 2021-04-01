<?php

/**
 *  In order for the Hare-Niemeyer algorithm to work properly,
 *  all classes it gets applied to need to implement the following methods
 */

interface HareNiemeyerInterface {

    /**
     * use a virtual column to store mandates information
     * @var string
     */
    const MANDATES_COLUMN = 'hare_niemeyer_mandates';

    /**
     * same as above, but remains the same throughout the whole session
     * @var string
     */
    const TOTAL_MANDATES_COLUMN = 'total_hare_niemeyer_mandates';

    /**
     * election parties receive mandates both on country level and local level
     * eventually, both numbers get compared and in case of differences
     * a local mandate redistribution takes place
     * @var string
     */
    const LOCAL_MANDATES_COLUMN = 'local_hare_niemeyer_mandates';

    /**
     * use a virtual column to store party's remainder
     * @var string
     */
    const REMAINDER_COLUMN = 'hare_niemeyer_remainder';

    /**
     * whether the party has received a mandate based on remainder
     * @var string
     */
    const RECEIVED_MANDATE_COLUMN = 'has_received_hare_niemeyer_mandate';


    ///////////////////////////////////////////////////////////////////////////
    public function getVotes(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates);

    ///////////////////////////////////////////////////////////////////////////
    public function getTotalHareNiemeyerMandates(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function setTotalHareNiemeyerMandates(int $mandates);

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerRemainder(): float;

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerRemainder(float $remainder);

    ///////////////////////////////////////////////////////////////////////////
    public function hasPartyReceivedAdditionalMandate(): bool;

    ///////////////////////////////////////////////////////////////////////////
    public function markPartyAsHavingReceivedAdditionalMandate(bool $status);
    
}