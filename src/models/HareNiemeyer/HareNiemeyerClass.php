<?php

use Propel\Runtime\Collection\ObjectCollection;

class HareNiemeyer {

    /**
     * how many digits to use after the decimal point for the quota
     * @var int
     */
    const PRECISION = 16;

    /**
     * Hare-Niemeyer quota equal to $totalVotes / $mandatesToDistribute
     * @var float
     */
    private $quota = 0.0;

    /**
     * how many mandates were distributed in the end
     * @var int
     */
    private $distributedMandates  = 0;

    /**
     * if two or more parties should get a single remaining mandate,
     * the decision is made by lot between those parties
     * @var ObjectCollection
     */
    private $lotBetweenParties;

    public function __construct() {
        $this->lotBetweenParties        = new ObjectCollection();
    }

    /**
     * Distributes mandates between a collection of parties
     * using the largest remainder method (a.k.a. as HareNiemeyer method)
     *
     * @param  ObjectCollection $parties
     * @param  int              $mandatesToDistribute – how many mandates should be distributed
     *
     * @throws ArithmeticError: minimum parties and minimum mandates are 1 each
     */
    public function distributeMandates(ObjectCollection $parties, int $mandatesToDistribute): void {
        if ($parties->count() < 1 || $mandatesToDistribute < 1) {
            throw new ArithmeticError('There need to be at least one party and at least one mandate to distribute.');
        }

        $totalVotes        = $this->getAllPartiesVotes($parties);
        $this->quota       = $totalVotes / $mandatesToDistribute;
        $partiesRemainders = $this->getPartiesRemainders($parties, $this->quota);

        while ($this->distributedMandates < $mandatesToDistribute) {
            $partiesToReceiveAMandate = $this->getPartiesToReceiveAMandate($partiesRemainders);

            // if the distribution won't exceed the amount of total mandates,
            // give each marked party a mandate and exclude it from the list
            // (it should not receive yet another mandate on following iterations)
            if ($this->distributedMandates + count($partiesToReceiveAMandate) <= $mandatesToDistribute) {
                foreach ($partiesToReceiveAMandate as $index) {
                    $this->giveAdditionalMandateToParty($parties[$index], $index);
                   
                    unset($partiesRemainders[$index]);
                }
            }
            elseif (count($partiesToReceiveAMandate) > 1) {
                $this->setLotBetweenParties($parties, $partiesToReceiveAMandate);
                break;
            }
        }
    }

    /**
     * Sum the votes of all elements in the collection
     *
     * @param  ObjectCollection $parties
     * @return int              $sum
     */
    public static function getAllPartiesVotes(ObjectCollection $parties): int {
        $sum = 0;

        foreach ($parties as $item) {

            // all items in the collection should have the same methods
            // declared in the HareNiemeyerInterface
            if ( ! $item instanceof HareNiemeyerInterface) {
                throw new TypeError(sprintf("Class '%s' is not an instance of HareNiemeyerInterface", get_class($item)));
            }

            $sum += $item->getVotes();
        }

        return $sum;
    }

    /**
     * For each party, get its votes, divide them by the Hare-Niemeyer quota
     * give the party mandates equal to the quotient
     * and store the remainder in an array
     *
     * @param  ObjectCollection $parties
     * @param  float            $quota
     * @return array            $partiesRemainders – key: index in collection; value: remainder
     */
    private function getPartiesRemainders(ObjectCollection $parties, float $quota): array {
        $partiesRemainders = [];

        foreach ($parties as $index => $item) {
            $partyId = $item->getId();
            $votes   = $item->getVotes();

            $division  = $votes / $quota;
            $quotient  = floor($division);
            $remainder = round($division - $quotient, self::PRECISION);

            $item->setHareNiemeyerMandates($quotient)
                 ->setTotalHareNiemeyerMandates($quotient)
                 ->setHareNiemeyerRemainder($remainder)
                 ->markPartyAsHavingReceivedAMandate(false);

            $partiesRemainders[ $index ] = $remainder;
            $this->distributedMandates   += $quotient;
        }

        return $partiesRemainders;
    }


    /**
     * Get the party with the highest remainder,
     * check if any other parties have the same remainder
     * and return all these parties indices
     *
     * @param  array $partiesRemainders – key: index in collection; value: remainder
     * @return array $pendingParties    – value: index in collection
     */
    private function getPartiesToReceiveAMandate(array $partiesRemainders): array {

        // sort remainders in descending order
        asort($partiesRemainders);

        // cycle through all remainders, set the first one as max
        // and mark the first party as a potential mandate-receiver
        foreach ($partiesRemainders as $index => $remainder) {

            // the first element has no prev element
            if (prev($partiesRemainders) === false) {
                $pendingParties = [ $index ];
                $maxRemainder   = $remainder;
                continue;
            }

            // if any of the following parties have the same remainder,
            // they should also get a mandate
            if ($remainder === $maxRemainder) {
                $pendingParties[] = $index;
            }

            // if the next remainder has a smaller value,
            // get out of the cycle (remainders are desc sorted)
            else {
                break;
            }
        }

        return $pendingParties;
    }

    /**
     * When two or more countries are competing for a single left mandate,
     * store these parties in the property $lotBetweenParties
     *
     * @param  array $allParties
     * @param  array $partiesToReceiveAMandate – value: index in collection
     */
    private function setLotBetweenParties(ObjectCollection $allParties, array $partiesToReceiveAMandate): void {
        foreach ($partiesToReceiveAMandate as $index) {
            $party = $allParties[$index];
            $this->lotBetweenParties->append($party);
        }
    }

    /**
     * Give a party an additional mandate,
     * increment the total distributed mandates by 1
     * and mark the party as having received a mandate based on its remainder
     *
     * @param  object $party: instance of HareNiemeyerInterface
     * @param  int    $index: order of party in the collection
     */
    private function giveAdditionalMandateToParty(HareNiemeyerInterface $party, int $index): void {
        $partyMandates = $party->getHareNiemeyerMandates();

        $party->setHareNiemeyerMandates($partyMandates + 1)
              ->setTotalHareNiemeyerMandates($partyMandates + 1)
              ->markPartyAsHavingReceivedAMandate(true);

        $this->distributedMandates += 1;

        $this->partiesReceivingMandates[ $index ] = 1;
    }

    /**
     * Get the Hare-Niemeyer quota property
     *
     * @return float $quota
     */
    public function getQuota(): float {
        return $this->quota;
    }
    
    /**
     * Get parties lotting for a mandate (if any)
     *
     * @return ObjectCollection $lotBetweenParties
     */
    public function getLottingParties(): ObjectCollection {
        return $this->lotBetweenParties;
    }

    /**
     * Get total of distributed mandates;
     * should be equal to $mandatesToDistribute,
     * but could also be less in case of lotting parties
     *
     * @return ObjectCollection $lotBetweenParties
     */
    public function getDistributedMandates(): int {
        return $this->distributedMandates;
    }
}