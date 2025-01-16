<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeterReadingRequest;
use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeterReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeterReadingRequest $request, Meter $meter): RedirectResponse
    {
        $meter->readings()->create(
            array_merge(
                $request->validated(),
                [
                    'estimated' => !is_null($request->input('estimated'))
                ]
            )
        );

        session()->flash('success', 'The reading has been successfully added');

        return redirect()->route('meters.show', $meter);
    }

    /**
     * Display the specified resource.
     */
    public function show(MeterReading $meterReading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeterReading $meterReading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeterReading $meterReading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeterReading $meterReading)
    {
        //
    }

    public function bulkUpload()
    {
        return view('meters.bulk-upload');
    }
}
