<x-guest-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow rounded p-6">

            <div class="mb-4">
                <a href="{{ route('meters.index') }}" class="text-blue-500 hover:underline text-sm">
                    &larr; Back to All Meters
                </a>
            </div>

            <h3 class="text-xl mb-4 text-gray-800">View Meter</h3>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                <label for="mpxn" class="text-gray-700">MPXN</label>
                <span id="mpxn">{{ $meter->mpxn }}</span>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                <label for="type" class="text-gray-700">Meter Type</label>
                <span id="type">{{ ucfirst($meter->type) }}</span>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                <label for="installation_date" class="text-gray-700">Installation Date</label>
                <span id="installation_date">{{ $meter->installation_date->format('d-m-Y') }}</span>
            </div>

            <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                <label for="estimated_annual_consumption" class="text-gray-700">Estimated Annual Consumption</label>
                <span id="estimated_annual_consumption">{{ $meter->estimated_annual_consumption }}</span>
            </div>

            <hr class="p-3" />

            <h4 class="text-lg text-gray-800 font-medium mb-4">Meter Readings</h4>

            @if($meter->readings->isEmpty())
                <p class="text-gray-500">No readings for this meter found</p>
            @else
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Reading Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Value</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Estimated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($meter->readings as $reading)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $reading->reading_date->format('d-m-Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $reading->reading_value }}kw</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $reading->estimated ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <h4 class="mt-6 text-lg text-gray-800 font-medium">Add Reading</h4>

            @error('reading_value')
                <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
            @enderror

            <form class="mt-4" method="POST" action="{{route('meters.readings.store', $meter)}}">
                @csrf

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="reading_value">Value <span class="text-sm text-gray-600">(leave blank for estimation)</span></label>
                    <input
                        id="reading_value"
                        type="text"
                        name="reading_value"
                        value="{{ old('reading_value') }}"
                        class="@error('reading_value') text-red-600 @enderror"
                    />
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="reading_date">Reading Date</label>
                    <input
                        id="reading_date"
                        type="date"
                        name="reading_date"
                        class="@error('reading_date') is-invalid @enderror"
                        value="{{ old('reading_date') }}"
                        required
                    />
                </div>

                @error('reading_date')
                    <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
                @enderror

                <div class="grid justify-items-end">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-guest-layout>
