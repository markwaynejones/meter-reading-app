<?php

namespace App\Actions;

use App\Models\Meter;
use Carbon\Carbon;

class CalculateEstimatedReading
{
    /**
     * Execute the action to calculate the
     * estimated meter reading based on a future date,
     * the estimated yearly consumption and the previous
     * meter reading value.
     *
     * @param Meter $meter
     * @param Carbon $futureDate
     * @return int
     */
    public function execute(Meter $meter, Carbon $futureDate): int
    {
        $estimateDailyConsumption = (int)($meter->estimated_annual_consumption / 365);

        $previousReading = $meter->readings()->latest('reading_date')->first();

        // if no previous readings, then use installation date for calculation instead
        if(!$previousReading){
            $daysBetweenDates = (int)$meter->installation_date->diffInDays($futureDate);
        }
        else {
            $daysBetweenDates = (int)$previousReading->reading_date->diffInDays($futureDate);
        }

        $estimatedConsumptionSinceLastReading = $daysBetweenDates * $estimateDailyConsumption;

        return ($previousReading->reading_value ?? 0) + $estimatedConsumptionSinceLastReading;
    }
}
