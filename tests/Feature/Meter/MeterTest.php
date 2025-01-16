<?php

namespace Tests\Feature\Meter;

 use App\Actions\CalculateEstimatedReading;
 use App\Models\Meter;
 use Carbon\Carbon;
 use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_meter_can_be_added(): void
    {
        $formData = Meter::factory()->make()->only(['mpxn', 'type', 'installation_date', 'estimated_annual_consumption']);
        $formData['installation_date'] = $formData['installation_date']->format('Y-m-d');

        $response = $this->post(route('meters.store'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('meters.index'));

        $this->assertDatabaseHas('meters', $formData);
    }

    public function test_a_meter_reading_can_be_added(): void
    {
        $meter = Meter::factory()->create(['installation_date' => Carbon::now()]);
        $readingDate = Carbon::now()->addMonth();

        $estimatedReading = app(CalculateEstimatedReading::class)->execute($meter, $readingDate);

        $formData = [
            'reading_value' => $estimatedReading,
            'reading_date' => $readingDate->format('Y-m-d'),
        ];

        $response = $this->post(route('meters.readings.store', $meter), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('meters.show', $meter));

        $this->assertDatabaseHas('meter_readings', array_merge(['meter_id' => $meter->id], $formData));
    }
}
