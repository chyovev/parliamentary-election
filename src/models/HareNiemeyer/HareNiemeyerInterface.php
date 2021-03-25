<?php

/**
 *  In order for the Hare-Niemeyer algorithm to work properly,
 *  all classes it gets applied to need to implement the following methods
 */

interface HareNiemeyerInterface {

    ///////////////////////////////////////////////////////////////////////////
    public function getVotes(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int;

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates);
    
}