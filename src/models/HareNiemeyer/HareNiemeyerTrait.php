<?php

/**
 *  Implement methods defined in the Hare-Niemeyer Interface
 */

trait HareNiemeyerTrait {

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int {
        return $this->getVirtualColumn(HareNiemeyerInterface::MANDATES_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates): self {
        $this->setVirtualColumn(HareNiemeyerInterface::MANDATES_COLUMN, $mandates);

        return $this;
    }

}