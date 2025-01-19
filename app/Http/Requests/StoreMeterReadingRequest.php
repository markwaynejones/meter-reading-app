<?php

namespace App\Http\Requests;

use App\Actions\CalculateEstimatedReading;
use App\Rules\WithinEstimatedRange;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreMeterReadingRequest extends FormRequest
{
    protected ?int $originalReadingValue;
    protected ?Carbon $previousReadingDate;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $installationDate = request()->route('meter')->installation_date;

        return [
            'reading_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($installationDate) {
                    if (Carbon::parse($value)->lte(Carbon::parse($installationDate))) {
                        $fail('The reading date must be after the installation date.');
                    }
                    // don't allow submitting a reading date thats before the previous reading date
                    if($this->previousReading && Carbon::parse($value)->lte($this->previousReading->reading_date)){
                        $fail('The reading date must be after the previous reading date.');
                    }
                },
            ],
            'reading_value' => ['nullable', 'integer', 'min:1', new WithinEstimatedRange($this->originalReadingValue)],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->originalReadingValue = $this->reading_value;

        $this->previousReading = request()->route('meter')->readings()->latest('reading_date')->first();

        $readingDate = Carbon::createFromFormat('Y-m-d', $this->reading_date);

        // if there is a previous reading and the future reading date submitted is before the previous reading date, then return
        if ($this->previousReading && $this->previousReading->reading_date->gt($readingDate)) {
            return;
        }

        // check if reading_value is null and compute it if necessary
        if (is_null($this->reading_value) && $this->filled('reading_date')) {

            $readingDate = Carbon::createFromFormat('Y-m-d', $this->reading_date);

            if($readingDate->isFuture()){
                $this->merge([
                    'reading_value' => app(CalculateEstimatedReading::class)
                        ->execute(request()->route('meter'), $readingDate),
                    'estimated' => true
                ]);
            }
        }
    }
}
