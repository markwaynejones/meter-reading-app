<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessMeterReadingsCsv;

class UploadMeterReadingsController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
        'csv_file' => ['required', 'file', 'mimes:csv', 'max:10240']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filePath = $request->file('csv_file')
            ->storeAs('meter_reading_bulk_uploads', 'readings_' . time() . '.csv');

        ProcessMeterReadingsCsv::dispatch($filePath);

        session()->flash('success', 'CSV file successfully uploaded to be processed');

        return redirect()->route('meters.index');
    }
}
