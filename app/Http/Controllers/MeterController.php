<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeterRequest;
use App\Models\Meter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Application|View|Factory
    {
        $meters = Meter::query()
            ->orderBy('installation_date', 'desc')
            ->get();

        return view('meters.index', compact('meters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Application|View|Factory
    {
        return view('meters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeterRequest $request): RedirectResponse
    {
        Meter::create($request->validated());

        session()->flash('success', 'The meter has been successfully added');

        return redirect()->route('meters.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meter $meter): Application|View|Factory
    {
        $meter->load('readings');

        return view('meters.show', compact('meter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meter $meter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meter $meter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meter $meter)
    {
        //
    }
}
