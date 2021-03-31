<?php

use Propel\Runtime\Collection\ObjectCollection;

/**
 * Step 1. Get country population
 * Step 2. Get global quota (country population / total mandates)
 * Step 3. For each constituency, calculate how many mandates it should get
 *         (constituency population / global quota)
 *
 * Step 4. If mandates amount from Step 3. is fewer than
           the minimum mandates per constituency:
 *           a) give constituency minimum mandates
 *           b) subtract minimum mandates from total mandates
 *           c) subtract constituency population from country population (optional, population gets recalculated anyway)
 *           d) exclude constituency from subsequent mandate distribution
 *
 * Step 5. Using the Hare-Niemeyer algorithm, distribute remaining mandates
 *         from step 4.b. between parties which do NOT fall under 4.d.
 */

class ConstituencyMandatesDistributor {

    /**
     * Calculate how many mandates a constituency should get
     * based on total mandates count and a minimum mandates per constituency
     *
     * @param  ObjectCollection $constituencies             – all constituencies which compete for mandates
     * @param  int              $totalMandates              – how many mandates should be distributed
     * @param  int              $minMandatesPerConstituency – a constituency cannot get any less mandates than this number
     *
     * @throws LogicException: if HareNiemeyer competing constituencies < 2 or mandates to distribute < 2
     */
    public function distribute(ObjectCollection $constituencies, int $totalMandates, int $minMandatesPerConstituency): void {

        // which constituencies should compete for mandates, by default it's all of them
        $redistributionConstituencies = clone $constituencies;
        $mandatesToDistribute         = $totalMandates;

        $totalPopulation              = $this->getCountryPopulation($constituencies);
        $globalQuota                  = $this->getGlobalQuota($totalPopulation, $totalMandates);

        foreach ($constituencies as $key => $item) {
            $constituencyPopulation = $item->getPopulation();

            // calculage how many mandates a constituency should get
            // based on its population and the country quota (taking the quotient)
            $constituencyMandates   = floor($constituencyPopulation / $globalQuota);

            // if these mandates are equal or more than the absolute minimum for a constituency,
            // the constituency will compete for an additional mandate using the Hare-Niemeyer method
            if ($constituencyMandates >= $minMandatesPerConstituency) {
                continue;
            }

            // otherwise, current constituency gets the minimum amount of mandates by law,
            // but it gets excluded from the Hare-Niemeyer “competition”
            $mandatesToDistribute -= $minMandatesPerConstituency;
            $redistributionConstituencies->remove($key);
            $item->setHareNiemeyerMandates($minMandatesPerConstituency)
                 ->setTotalHareNiemeyerMandates($minMandatesPerConstituency);
        }

        // redistribute remaining mandates using the Hare-Niemeyer method
        $hareNiemeyer = new HareNiemeyer();
        $hareNiemeyer->distributeMandates($redistributionConstituencies, $mandatesToDistribute);

        $this->mergeConstituenciesMandates($constituencies, $redistributionConstituencies);
    }

    /**
     * Merge the mandates of the constituencies which took part in the redistribution
     * with the ones of the constituencies which received minimum mandates
     *
     * @param ObjectCollection $constituencies
     * @param ObjectCollection $redistributionConstituencies
     */
    private function mergeConstituenciesMandates(ObjectCollection $constituencies, ObjectCollection $redistributionConstituencies): void {
        $allMandates           = $constituencies->getData();
        $redistributedMandates = $redistributionConstituencies->getData();
        $mergedMandates        = array_replace($allMandates, $redistributedMandates);

        $constituencies->setData($mergedMandates);
    }

    /**
     * Sum all constituencies' populations to get the country population
     *
     * @param ObjectCollection $constituencies
     * @return int $population
     */
    public function getCountryPopulation(ObjectCollection $constituencies): int {
        $population = 0;

        foreach ($constituencies as $item) {
            $population += $item->getPopulation();
        }

        return $population;
    }

    /**
     * Calculates global quota for the whole country
     *
     * @param  int   $population – total country population
     * @param  int   $mandates   – total mandates for the whole country
     * @return float
     */
    public function getGlobalQuota(int $population, int $mandates): float {
        return $population / $mandates;
    }

}
