<?php

namespace App\Http\Requests;

use App\Actions\CalculateEstimatedReading;
use App\Rules\WithinEstimatedRange;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreMeterReadingRequest extends FormRequest
{
    protected ?int $originalReadingValue;

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
                },
            ],
            'reading_value' => ['nullable', 'integer', 'min:1', new WithinEstimatedRange($this->originalReadingValue)],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->originalReadingValue = $this->reading_value;

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
