<?php

/**
 *  Implement methods defined in the Hare-Niemeyer Interface
 */

trait HareNiemeyerTrait {

    private $hareNiemeyerMandates = 0;

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int {
        return $this->hareNiemeyerMandates;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates): self {
        $this->hareNiemeyerMandates = $mandates;

        return $this;
    }

}