<?php

namespace App\Jobs;

use App\Mail\InvalidMeterReadingUploads;
use App\Models\Meter;
use App\Models\MeterReading;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ProcessMeterReadingsCsvNoChunking implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $filePath,
    ) {}

    /**
     * Execute the job.
     *
     * Other ideas of how we could handle the processing of CSVs
     *
     * - If files were extremely large, we could split the original csv uploaded
     * into multiple files and allow multiple workers to pick up each chunk of readings
     *
     * - We could use DB transaction to ensure that we only store the readings if they all pass
     * validation so that the user can re-upload the same CSV but with the errors fixed
     *
     * - If we weren't going to skip rows with errors and use the DB transaction approach
     * mentioned above, then we could add a public $tries variable on this job to ensure if the job
     * failed then we could retry it x number of times and the fact we was using DB transaction means
     * we wouldn't risk duplicating data
     *
     */
    public function handle(): void
    {
        $fullPath = storage_path('app/private/' . $this->filePath);

        // if file exists, process it
        if(($fileStream = fopen($fullPath, 'r')) !== false){

            $headerRow = fgetcsv($fileStream);

            $invalidRows = collect();

            // process each row of the CSV
            while (($row = fgetcsv($fileStream)) !== false) {

                $errors = $this->validateRow($row);

                // if row has validation errors, add to collection to send email then continue to next row
                if(!empty($errors)){
                    $invalidRows->push([
                        'data' => $row,
                        'errors' => $errors,
                    ]);

                    continue;
                }

                $mpxn = $row[0];
                $readingDate = $row[1];
                $readingValue = $row[2];

                $associatedMeter = Meter::query()
                    ->where('mpxn', $mpxn)
                    ->pluck('id')
                    ->first();

                MeterReading::create([
                    'meter_id' => $associatedMeter,
                    'reading_date' => Carbon::createFromFormat('d/m/Y', $readingDate),
                    'reading_value' => $readingValue,
                    'bulk_uploaded' => true,
                ]);
            }

            fclose($fileStream);

            // if we have rows with errors, email user to let them know
            if($invalidRows->isNotEmpty()){
                Mail::to('user1@example.net')->send(new InvalidMeterReadingUploads($invalidRows));
            }

            // possibly delete the file after processing
//            Storage::delete($this->filePath);
        } else {
            // TODO: throw error here and fail job
            dd('file not found');
        }

    }

    protected function validateRow(array $row)
    {
        $rowErrors = [];

        $mpxn = $row[0];
        $readingDate = $row[1];
        $readingValue = $row[2];

        $meterFound = Meter::query()
            ->where('mpxn', $mpxn)
            ->exists();

        if(!$meterFound){
            $rowErrors[] = 'Meter not found for MPXN '.$mpxn;
        }

        try {
            Carbon::createFromFormat('d/m/Y', $readingDate);
        } catch(\Exception $e) {
            $rowErrors[] = 'Reading date format incorrect';
        }

        // TODO: possibly add estimated consumption action check here to ensure reading value is within 25% threshold

        return $rowErrors;
    }
}
