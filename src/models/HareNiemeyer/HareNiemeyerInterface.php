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

    ///////////////////////////////////////////////////////////////////////////
    public function getVotes(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates);
    
}