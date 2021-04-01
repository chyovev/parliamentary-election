<?php

/**
 *  Implement methods defined in the Hare-Niemeyer Interface
 */

trait HareNiemeyerTrait {

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerRemainder(): float {
        return $this->getVirtualColumn(HareNiemeyerInterface::REMAINDER_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerRemainder(float $remainder): self {
        $remainder = $remainder;
        $this->setVirtualColumn(HareNiemeyerInterface::REMAINDER_COLUMN, $remainder);

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function hasPartyReceivedAdditionalMandate(): bool {
        return $this->getVirtualColumn(HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function markPartyAsHavingReceivedAdditionalMandate(bool $status): self {
        $this->setVirtualColumn(HareNiemeyerInterface::RECEIVED_MANDATE_COLUMN, $status);

        return $this;
    }

    // NB! these get modified when mandates get distributed between parties and candidates

    ///////////////////////////////////////////////////////////////////////////
    public function getHareNiemeyerMandates(): int {
        return $this->getVirtualColumn(HareNiemeyerInterface::MANDATES_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setHareNiemeyerMandates(int $mandates): self {
        $this->setVirtualColumn(HareNiemeyerInterface::MANDATES_COLUMN, $mandates);

        return $this;
    }

    // NB! these remain the same the whole time

    ///////////////////////////////////////////////////////////////////////////
    public function getTotalHareNiemeyerMandates(): int {
        return $this->getVirtualColumn(HareNiemeyerInterface::TOTAL_MANDATES_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setTotalHareNiemeyerMandates(int $mandates): self {
        $this->setVirtualColumn(HareNiemeyerInterface::TOTAL_MANDATES_COLUMN, $mandates);

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getLocalHareNiemeyerMandates(): int {
        return $this->getVirtualColumn(HareNiemeyerInterface::LOCAL_MANDATES_COLUMN);
    }

}