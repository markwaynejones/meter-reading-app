<?php

namespace App\Rules;

use App\Actions\CalculateEstimatedReading;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Meter;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WithinEstimatedRange implements DataAwareRule, ValidationRule
{
    protected array $data = [];
    protected ?int $originalReadingValue;

    public function __construct(?int $originalReadingValue)
    {
        $this->originalReadingValue = $originalReadingValue;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // if user wants estimated reading value to be used, then skip this validation
        if(is_null($this->originalReadingValue)){
            return;
        }

        $meter = request()->route('meter');

        $readingDate = Carbon::createFromFormat('Y-m-d', $this->data['reading_date']);

        $estimatedValue = app(CalculateEstimatedReading::class)
            ->execute($meter, $readingDate);

        $lowerBound = $estimatedValue - ($estimatedValue * 0.25);
        $upperBound = $estimatedValue + ($estimatedValue * 0.25);

        if(!((int)$value >= $lowerBound && (int)$value <= $upperBound)){
            $fail("The :attribute must be within 25% of the annual estimated consumption (between {$lowerBound} and {$upperBound})");
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
