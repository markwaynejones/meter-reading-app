<?php

namespace App\Jobs;

use App\Mail\InvalidMeterReadingUploads;
use App\Models\Meter;
use App\Models\MeterReading;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class ProcessMeterReadingsCsv implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $filePath,
        protected Collection $invalidRows = new Collection()
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
     * mentioned above, then we could use $this->retries logic on this job to ensure if the job
     * failed then we could retry it x number of times and the fact we was using DB transaction means
     * we wouldn't risk duplicating data
     */
    public function handle(): void
    {
        $fullPath = storage_path('app/private/' . $this->filePath);

        // if file exists, process it
        if(($fileStream = fopen($fullPath, 'r')) !== false){

            $headerRow = fgetcsv($fileStream);

            $chunkSize = 1;
            $chunk = [];

            // process each row of the CSV
            while (($row = fgetcsv($fileStream)) !== false) {

                $chunk[] = $row;

                if(count($chunk) === $chunkSize){
                    $this->processChunk($chunk);
                    $chunk = []; // reset chunk to clear memory
                }
            }

            // process any remaining rows
            if (!empty($chunk)) {
                $this->processChunk($chunk);
            }

            fclose($fileStream);

            // if we have rows with errors, email user to let them know
            if($this->invalidRows->isNotEmpty()){
                Mail::to('user1@example.net')->send(new InvalidMeterReadingUploads($this->invalidRows));
            }

            // possibly delete the file after processing
//            Storage::delete($this->filePath);
        } else {
            // TODO: throw error here and fail job
            dd('file not found');
        }

    }

    protected function processChunk(array $chunk): void
    {
        $mpxns = collect($chunk)->pluck(0)->unique();

        $meters = Meter::query()
            ->whereIn('mpxn', $mpxns)
            ->select('id', 'mpxn')
            ->get()
            ->keyBy('mpxn');

        foreach($chunk as $row){

            $errors = $this->validateRow($row, $meters);

            // if row has validation errors, add to collection to send email then continue to next row
            if(!empty($errors)){
                $this->invalidRows->push([
                    'data' => $row,
                    'errors' => $errors,
                ]);

                continue;
            }

            $mpxn = $row[0];
            $readingDate = $row[1];
            $readingValue = $row[2];

            $associatedMeter = $meters[$mpxn];

            MeterReading::create([
                'meter_id' => $associatedMeter->id,
                'reading_date' => Carbon::createFromFormat('d/m/Y', $readingDate),
                'reading_value' => $readingValue,
                'bulk_uploaded' => true,
            ]);

        }
    }

    protected function validateRow(array $row, Collection $meters)
    {
        $rowErrors = [];

        $mpxn = $row[0];
        $readingDate = $row[1];
        $readingValue = $row[2];

        $meterFound = $meters[$mpxn] ?? null;

        if(is_null($meterFound)){
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
